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

  ami              = var.ami 
  instance_type    = "t2.large"
  cloudinit_config = data.cloudinit_config.this.rendered

  name        = var.name
  root_domain = var.root_domain
  vpc_id      = var.vpc_id
  subnet_ids  = var.subnet_ids
  key_name    = var.key_name
}

data "cloudinit_config" "this" {
  gzip          = false
  base64_encode = false

  part {
    content_type = "text/x-shellscript"
    filename     = "apply_settings.sh"
    content      = <<-EOF
      #!/bin/bash

      declare -x PATH="/opt/bitnami/apache/bin:/opt/bitnami/apache2/bin:/opt/bitnami/canvas-rce-api/bin:/opt/bitnami/canvaslms/bin:/opt/bitnami/canvaslms/engines/audits/bin:/opt/bitnami/canvaslms/gems/canvas_i18nliner/bin:/opt/bitnami/canvaslms/gems/turnitin_api/bin:/opt/bitnami/canvaslms/packages/canvas-rce/bin:/opt/bitnami/canvaslms/packages/translations/bin:/opt/bitnami/common/bin:/opt/bitnami/git/bin:/opt/bitnami/gonit/bin:/opt/bitnami/node/bin:/opt/bitnami/postgresql/bin:/opt/bitnami/redis/bin:/opt/bitnami/ruby/bin:/opt/bitnami/nami/bin:/opt/bitnami/apache/bin:/opt/bitnami/apache2/bin:/opt/bitnami/canvas-rce-api/bin:/opt/bitnami/canvaslms/bin:/opt/bitnami/canvaslms/engines/audits/bin:/opt/bitnami/canvaslms/gems/canvas_i18nliner/bin:/opt/bitnami/canvaslms/gems/turnitin_api/bin:/opt/bitnami/canvaslms/packages/canvas-rce/bin:/opt/bitnami/canvaslms/packages/translations/bin:/opt/bitnami/common/bin:/opt/bitnami/git/bin:/opt/bitnami/gonit/bin:/opt/bitnami/node/bin:/opt/bitnami/postgresql/bin:/opt/bitnami/redis/bin:/opt/bitnami/ruby/bin:/opt/bitnami/nami/bin:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin"

      pushd /home/bitnami/stack/canvaslms
      # https://github.com/instructure/canvas-lms/wiki/Settings-%28customization%29
      # important for us because canvas is always embedded inside UnlockED app
      RAILS_ENV=production ./bin/rails r "Setting.set('block_html_frames', 'false')"

      # restart required to clear in-proc cache
      /opt/bitnami/ctlscript.sh restart apache
      popd
    EOF
  }
}
