variable "root_domain" {
  description = "Base domain in route53 used by the hosted zone"
  type        = string
}

variable "ami" {
  description = "The owner of the repo, make sure a git clone https://github.com/$repo_owner/$repo_name works"
  type        = string
}

variable "instance_type" {
  description = "Instance type defaults to a t2.micro"
  type        = string
  default     = "t2.micro"
}

variable "cloudinit_config" {
  description = "The whole compile cloudinit_config script"
  type        = string
  default     = ""
}

variable "key_name" {
  description = "Name of the keypair resource in aws"
  type        = string
}

variable "name" {
  description = "Deployed to this subdomain, also tags ec2 instance with this name."
  type        = string
}

variable "subnet_ids" {
  type = list(string)
}

variable "vpc_id" {
  type = string
}
