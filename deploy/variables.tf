variable "root_domain" {
  description = "Base domain in route53 used by the hosted zone"
  type        = string
  default     = "unlockedlabs.net"
}

variable "repo_owner" {
  description = "The owner of the repo, make sure a git clone https://github.com/$repo_owner/$repo_name works"
  type        = string
  default     = "UnlockedLabs"
}

variable "repo_name" {
  description = "The repo name in github"
  type        = string
  default     = "unlocked"
}

variable "repo_default_branch" {
  description = "Branch to checkout"
  type        = string
  default     = "sprint-11"
}
