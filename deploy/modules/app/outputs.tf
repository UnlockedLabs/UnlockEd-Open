output "ssh_connect_string" {
  description = "SSH connection info"
  value       = module.ec2.ssh_connect_string
}

output "db_connect_string" {
  description = "Mariadb connection info"
  value       = "mysql -h ${aws_db_instance.this.address} -u ${aws_db_instance.this.username} -p${aws_db_instance.this.password} ${aws_db_instance.this.name}"
}

output "site_url" {
  description = "Publically accessible at"
  value       = module.ec2.site_url
}
