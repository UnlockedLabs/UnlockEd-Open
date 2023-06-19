# STEPS
# generate a local key pair for sshing into box and pulling code from github onto that box 
# create deploy key in github so you have permission to clone from the box
# create an ec2 instance 
# clone the repo into the `~/htdocs` folder and checkout requested branch
# follow the [standard usage instructions](#Usage)
# setup a subdomain with route53
# issue and validate cert for that subdomain
# setup a secure load balancer that redirects http traffic to https and points https traffic at instance's port 80
# point subdomain to the load balancer

data "aws_route53_zone" "unlocked" {
  name = var.root_domain
}

# Instance Setup
resource "aws_instance" "unlocked" {
  ami                    = var.ami
  instance_type          = var.instance_type
  key_name               = var.key_name
  vpc_security_group_ids = [aws_security_group.unlocked.id]
  user_data              = var.cloudinit_config

  root_block_device {
    encrypted = true
  }

  tags = {
    Name = var.name
  }

}

resource "aws_security_group" "unlocked" {
  egress = [
    {
      cidr_blocks      = ["0.0.0.0/0", ]
      description      = "All Outbound"
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
      description      = "SSH"
      from_port        = 22
      ipv6_cidr_blocks = []
      prefix_list_ids  = []
      protocol         = "tcp"
      security_groups  = []
      self             = false
      to_port          = 22
    },
    {
      cidr_blocks      = ["0.0.0.0/0", ]
      description      = "HTTP"
      from_port        = 80
      ipv6_cidr_blocks = []
      prefix_list_ids  = []
      protocol         = "tcp"
      security_groups  = []
      self             = false
      to_port          = 80
    }
  ]
}

# Domain and Cert Setup
resource "aws_route53_record" "unlocked" {
  zone_id = data.aws_route53_zone.unlocked.zone_id
  name    = "${var.name}.${var.root_domain}"
  type    = "CNAME"
  ttl     = "300"
  records = [aws_lb.unlocked.dns_name]
}

resource "aws_acm_certificate" "unlocked" {
  domain_name       = aws_route53_record.unlocked.name
  validation_method = "DNS"

  # recommended because changes would brick the alb temporarily
  lifecycle {
    create_before_destroy = true
  }
}

resource "aws_route53_record" "unlocked_cert_validation" {
  name    = one(aws_acm_certificate.unlocked.domain_validation_options).resource_record_name
  type    = one(aws_acm_certificate.unlocked.domain_validation_options).resource_record_type
  zone_id = data.aws_route53_zone.unlocked.zone_id
  records = [one(aws_acm_certificate.unlocked.domain_validation_options).resource_record_value]
  ttl     = 60
}

resource "aws_acm_certificate_validation" "cert" {
  certificate_arn         = aws_acm_certificate.unlocked.arn
  validation_record_fqdns = [aws_route53_record.unlocked_cert_validation.fqdn]
}


# Load Balancer Setup
resource "aws_security_group" "unlocked_alb" {
  egress = [
    {
      cidr_blocks      = ["0.0.0.0/0"]
      protocol         = "tcp"
      from_port        = 80
      to_port          = 80
      description      = ""
      ipv6_cidr_blocks = []
      prefix_list_ids  = []
      security_groups  = []
      self             = false
    }
  ]
  ingress = [
    {
      cidr_blocks      = ["0.0.0.0/0", ]
      description      = ""
      from_port        = 80
      ipv6_cidr_blocks = []
      prefix_list_ids  = []
      protocol         = "tcp"
      security_groups  = []
      self             = false
      to_port          = 80
    },
    {
      cidr_blocks      = ["0.0.0.0/0", ]
      description      = ""
      from_port        = 443
      ipv6_cidr_blocks = []
      prefix_list_ids  = []
      protocol         = "tcp"
      security_groups  = []
      self             = false
      to_port          = 443
    }
  ]
}


resource "aws_lb" "unlocked" {
  name               = replace(var.name, ".", "-")
  internal           = false
  load_balancer_type = "application"
  security_groups    = [aws_security_group.unlocked_alb.id]
  subnets            = var.subnet_ids
}


# HTTP -> HTTPS Redirect 
resource "aws_lb_listener" "redirect" {
  load_balancer_arn = aws_lb.unlocked.arn
  port              = "80"
  protocol          = "HTTP"

  default_action {
    type = "redirect"

    redirect {
      port        = "443"
      protocol    = "HTTPS"
      status_code = "HTTP_301"
    }
  }
}

resource "aws_lb_listener" "unlocked" {
  load_balancer_arn = aws_lb.unlocked.arn
  port              = "443"
  protocol          = "HTTPS"
  ssl_policy        = "ELBSecurityPolicy-TLS-1-2-2017-01"

  certificate_arn = aws_acm_certificate.unlocked.arn

  # if you don't tell load balancer to wait until cert has validated you get 
  # Errror: UnsupportedCertificate
  depends_on = [aws_acm_certificate_validation.cert]

  default_action {
    type             = "forward"
    target_group_arn = aws_lb_target_group.unlocked.arn
  }
}

resource "aws_lb_target_group" "unlocked" {
  name     = replace(var.name, ".", "-")
  port     = 80
  protocol = "HTTP"
  vpc_id   = var.vpc_id
}

resource "aws_lb_target_group_attachment" "unlocked" {
  target_group_arn = aws_lb_target_group.unlocked.arn
  target_id        = aws_instance.unlocked.id
  port             = 80
}
