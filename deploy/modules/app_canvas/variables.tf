variable "root_domain" {
  description = "Base domain in route53 used by the hosted zone"
  type        = string
}

variable "name" {
  description = "Deployed to this subdomain, also tags ec2 instance with this name."
  type        = string
  default     = "app"
}

variable "ami" {
  description = "Amazon Machine Image for bitnami canvas."
  type        = string
}

variable "subnet_ids" {
  type = list(string)
}

variable "vpc_id" {
  type = string
}


variable "key_name" {
  description = "Name of the keypair resource in aws"
  type        = string
}
