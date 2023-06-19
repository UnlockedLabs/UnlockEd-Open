output "ssh_connect_string" {
  description = "Public IP address of EC2 instance"
  value       = "ssh -i .${var.key_name}.pem bitnami@${aws_instance.unlocked.public_ip}"
}

output "site_url" {
  description = "Publically accessible at"
  value       = "https://${aws_route53_record.unlocked.name}"
}
