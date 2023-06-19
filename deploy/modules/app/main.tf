terraform {
  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = ">= 3.75.2"
    }
  }
}

module "ec2" {
  source = "../base_ec2"

  cloudinit_config = data.cloudinit_config.this.rendered

  name        = var.name
  ami         = var.ami 
  root_domain = var.root_domain
  vpc_id      = var.vpc_id
  subnet_ids  = var.subnet_ids
  key_name    = var.key_name
}


data "cloudinit_config" "this" {
  gzip          = false
  base64_encode = false

  part {
    content_type = "text/cloud-config"
    filename     = "write_ssh_pem.yaml"
    content = <<-EOF
    #cloud-config
    ${jsonencode({
    write_files = [
      {
        path        = "/home/bitnami/.ssh/id_rsa"
        permissions = "0600"
        owner       = "bitnami:bitnami"
        content     = var.private_key
      },
    ]
})}
  EOF  
}

part {
  content_type = "text/x-shellscript"
  filename     = "init_app_stack.sh"
  content      = <<-EOF
      #!/bin/bash
      sudo apt install git -y
      pushd /opt/bitnami/apache2/htdocs
      rm -rf *
      sudo su -c "ssh-keyscan github.com >> /home/bitnami/.ssh/known_hosts" bitnami
      sudo su -c "git clone git@github.com:${var.repo_owner}/${var.repo_name}.git ." bitnami
      sudo su -c "git checkout ${var.repo_branch}" bitnami

      # need to pull in the limitless stuff from other branches
      # what we're basically doing is leaning over into sprint-3 grabbing all the stuff in libs
      # then figure out which stuff alread exists in this branch and using that version
      pushd libs
      sudo su -c "git checkout origin/sprint-3 ." bitnami
      sudo su -c "git status | grep modified | awk '{ print $2 }' | xargs git reset HEAD" bitnami
      sudo su -c "git restore ." bitnami
      popd

      # notice the order of operations because cat will match for catalog 
      db_host="${aws_db_instance.this.address}"
      sudo su -c "sed -i 's/localhost/'""$db_host""'/g' config/database.php" bitnami

      db_name="${aws_db_instance.this.name}"
      sudo su -c "sed -i 's/learning_center_api_db/'""$db_name""'/g' config/database.php" bitnami

      db_password="${aws_db_instance.this.password}"
      sudo su -c "sed -i 's/learn/'""$db_password""'/g' config/database.php" bitnami

      db_username="${aws_db_instance.this.username}"
      # quotes included in this one cause unlocked is found in other places in the file
      sudo su -c "sed -i 's/\"unlocked\"/\"'""$db_username""'\"/g' config/database.php" bitnami


      # nfs utils so that we can mount the persistent media EFS volume
      # BUG, will not show up on reboot, fstab fix is here: https://medium.com/geekculture/ow-to-setup-amazon-elastic-file-system-efs-and-mount-on-to-ubuntu-ec2-b47346427d5
      sudo apt install nfs-common -y
      sudo mv media git_media
      sudo touch git_media/images/test
      sudo mkdir media
      sudo mount -t nfs4 -o nfsvers=4.1,rsize=1048576,wsize=1048576,hard,timeo=600,retrans=2,noresvport ${aws_efs_file_system.this.dns_name}:/ media
      # deamon is the process that runs php/apache so it needs permissions to upload files
      # this is gonna get expensive once we have more content
      sudo chmod -R 777 media
      sudo cp -R git_media/* media/.
      sudo rm -R git_media

      # hardcoded /demo folder creates problems for our standard subdomain deployment 
      sudo su -c "sed -i 's:.\"/demo/\" . :. \"/\" . :g' objects/lesson.php" bitnami

      # creds so we can place curl requests to canvas.unlockedlabs.net
      # replaced hardedcoded token with token generated at https://canvas.unlockedlabs.net/profile
      sudo su -c "sed -i 's/MPrFh2s4Xh9NWH0mQbK0BcJu4OiRSRI1itKUuWT68bCTKOXXkSITubjJa4796J2v/BmkdkUPbjr7zkuFfzUt7abHWY8oPzUf2VoiGODC8jUUcu9l2Amk0xHPlCD6bvr0E/g' objects/canvas_data.php" bitnami
      sudo su -c "sed -i 's/http:\/\/192.168.1.1:3000/https:\/\/canvas.unlockedlabs.net/g' objects/canvas_data.php" bitnami

      # disable caching because troubleshooting anything is nearly impossible otherwise
      sudo sed -i 's/opcache.enable = 1/opcache.enable = 0/g' /home/bitnami/stack/php/etc/php.ini
      sudo /opt/bitnami/ctlscript.sh restart

      popd
    EOF
}
}

# RDS Database Configuration

resource "aws_security_group" "db_default" {
  egress = [
    {
      cidr_blocks      = ["0.0.0.0/0", ]
      description      = ""
      from_port        = 0
      ipv6_cidr_blocks = []
      prefix_list_ids  = []
      protocol         = "-1"
      security_groups  = []
      self             = false
      to_port          = 0
    }
  ]
  ingress = [
    {
      cidr_blocks      = ["0.0.0.0/0", ]
      description      = ""
      from_port        = 3306
      ipv6_cidr_blocks = []
      prefix_list_ids  = []
      protocol         = "tcp"
      security_groups  = []
      self             = false
      to_port          = 3306
    }
  ]
}

resource "random_password" "this" {
  length  = 16
  special = false
}

resource "aws_db_instance" "this" {
  allocated_storage       = 10
  apply_immediately       = true
  engine                  = "mariadb"
  identifier              = var.name
  vpc_security_group_ids  = [aws_security_group.db_default.id, ]
  engine_version          = "10.6.8"
  instance_class          = "db.t3.micro"
  name                    = var.name
  password                = random_password.this.result
  username                = "admin"
  publicly_accessible     = true
  backup_retention_period = 30
  backup_window           = "05:00-07:00" # this is UTC, corresponds to roughty midnight to 2am central
  storage_encrypted       = true
  parameter_group_name    = "default.mariadb10.6"
  skip_final_snapshot     = true
}


# Persistent EFS Filesystem for our media
resource "aws_efs_file_system" "this" {
  creation_token = var.name
  encrypted      = true

  tags = {
    Name = var.name
  }
}

resource "aws_security_group" "efs_default" {
  ingress = [
    {
      cidr_blocks      = ["0.0.0.0/0"]
      description      = "EFS mount target"
      from_port        = 2049
      to_port          = 2049
      security_groups  = []
      self             = false
      ipv6_cidr_blocks = []
      prefix_list_ids  = []
      protocol         = "tcp"
    }
  ]
}

# Creating Mount target of EFS
resource "aws_efs_mount_target" "this" {
  # since efs is multi-az by default and ec2 creation is not constained to a specific az
  # we need to create a way to access the efs volume from all subnets in the vpc
  for_each = toset(var.subnet_ids)

  file_system_id  = aws_efs_file_system.this.id
  subnet_id       = each.value
  security_groups = [aws_security_group.efs_default.id]
}

resource "aws_efs_backup_policy" "policy" {
  file_system_id = aws_efs_file_system.this.id

  backup_policy {
    status = "ENABLED"
  }
}
