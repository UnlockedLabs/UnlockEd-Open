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
  description = "Amazon Machine Image to run the app on"
  type        = string
}

variable "repo_owner" {
  description = "The owner of the repo, make sure a git clone https://github.com/$repo_owner/$repo_name works"
  type        = string
}

variable "repo_name" {
  description = "The repo name in github"
  type        = string
}


variable "repo_branch" {
  description = "Branch to checkout"
  type        = string
}

variable "subnet_ids" {
  type = list(string)
}

variable "vpc_id" {
  type = string
}


variable "private_key" {
  description = "Private key used as the deploy key in github for now"
  type        = string
}

variable "key_name" {
  description = "Name of the keypair resource in aws"
  type        = string
}
