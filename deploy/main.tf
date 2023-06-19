terraform {
  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "~> 3.27"
    }
    github = {
      source  = "integrations/github"
      version = "~> 4.0"
    }
  }

  backend "s3" {
    bucket         = "239442854891-terraform-states"
    key            = "infrastructure"
    region         = "us-west-2"
    encrypt        = true
    dynamodb_table = "terraform-lock"
  }

  required_version = ">= 0.14.9"
}

provider "aws" {
  profile = "default"
  alias = "production"
  region  = "us-east-2"
}

provider "github" {
  owner = var.repo_owner
}


# STAGING
provider "aws" {
  profile = "default"
  region  = "us-west-2"
}

resource "tls_private_key" "staging" {
  algorithm = "RSA"
  rsa_bits  = 4096
}

resource "github_repository_deploy_key" "staging" {
  title      = "aws_ec2_clone_terraform_staging"
  repository = var.repo_name
  key        = tls_private_key.staging.public_key_openssh
  read_only  = "true"
}

resource "aws_key_pair" "staging" {
  key_name   = "key-staging"
  public_key = tls_private_key.staging.public_key_openssh

  provisioner "local-exec" {
    command = <<-EOF
        echo '${tls_private_key.staging.private_key_pem}' > "./.key-staging.pem"
        chmod 600 "./.key-staging.pem"
    EOF
  }
}

# Infra *not* managed by Terraform 
data "aws_vpcs" "west" {
  filter {
    name = "is-default"
    values = [
      "true"
    ]
  }
}

data "aws_subnet_ids" "west" {
  vpc_id = one(data.aws_vpcs.west.ids)
}

module "staging_app" {

  source = "./modules/app"

  name        = "staging"
  ami         = "ami-066f40a601e6721ed"
  repo_branch = var.repo_default_branch
  repo_owner  = var.repo_owner
  repo_name   = var.repo_name

  private_key = tls_private_key.staging.private_key_pem
  key_name    = aws_key_pair.staging.key_name

  root_domain = var.root_domain
  vpc_id      = one(data.aws_vpcs.west.ids)
  subnet_ids  = data.aws_subnet_ids.west.ids
}

module "staging_canvas" {

  source = "./modules/app_canvas"

  name     = "canvas.staging"

  # ami name bitnami-canvaslms-2022.2.16-1-4-r01-linux-debian-10-x86_64-hvm-ebs-nami-752b37a4-d309-4bda-b662-6bc16b5eaf49 
  ami      = "ami-0f4e0e2c12333b027"
  key_name = aws_key_pair.staging.key_name

  root_domain = var.root_domain
  vpc_id      = one(data.aws_vpcs.west.ids)
  subnet_ids  = data.aws_subnet_ids.west.ids
}


# Let's bring this back in once we have a minimum ci/cd pipeline
#module "fedramp" {
#  source = "./modules/fedramp"
#}

# PRODUCTION 
provider "aws" {
  alias = "east"
  profile = "default"
  region  = "us-east-2"
}

resource "tls_private_key" "washu" {
  algorithm = "RSA"
  rsa_bits  = 4096
}

resource "github_repository_deploy_key" "washu" {
  title      = "aws_ec2_clone_terraform_washu"
  repository = var.repo_name
  key        = tls_private_key.washu.public_key_openssh
  read_only  = "true"
}

resource "aws_key_pair" "washu" {
  provider = aws.east
  key_name   = "key-washu"
  public_key = tls_private_key.washu.public_key_openssh

  provisioner "local-exec" {
    command = <<-EOF
        echo '${tls_private_key.washu.private_key_pem}' > "./.key-washu.pem"
        chmod 600 "./.key-washu.pem"
    EOF
  }
}

# Infra *not* managed by Terraform 
data "aws_vpcs" "east" {
  provider = aws.east
  filter {
    name = "is-default"
    values = [
      "true"
    ]
  }
}

data "aws_subnet_ids" "east" {
  provider = aws.east
  vpc_id = one(data.aws_vpcs.east.ids)
}

module "washu_app" {

  source = "./modules/app"
  providers = {
    aws = aws.east
  }

  name        = "washu"
  ami         = "ami-03acf34f011588163"
  repo_branch = var.repo_default_branch
  repo_owner  = var.repo_owner
  repo_name   = var.repo_name

  private_key = tls_private_key.washu.private_key_pem
  key_name    = aws_key_pair.washu.key_name

  root_domain = var.root_domain
  vpc_id      = one(data.aws_vpcs.east.ids)
  subnet_ids  = data.aws_subnet_ids.east.ids
}


module "washu_canvas" {

  source = "./modules/app_canvas"
  providers = {
    aws = aws.east
  }

  name     = "canvas.washu"
  ami      = "ami-082c227a34dec1c80"
  key_name = aws_key_pair.washu.key_name

  root_domain = var.root_domain
  vpc_id      = one(data.aws_vpcs.east.ids)
  subnet_ids  = data.aws_subnet_ids.east.ids
}
