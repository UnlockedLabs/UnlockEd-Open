output "ssh_connect_string" {
  description = "Public IP address of EC2 instance"
  value       = module.ec2.ssh_connect_string
}

output "site_url" {
  description = "Publically accessible at"
  value       = module.ec2.site_url
}
