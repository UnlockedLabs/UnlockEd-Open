output "staging_ssh_connect_string" {
  value = module.staging_app.ssh_connect_string
}

output "staging_site_url" {
  value = module.staging_app.site_url
}

output "staging_db_connect_string" {
  value     = module.staging_app.db_connect_string
  sensitive = true
}

output "staging_canvas_ssh_connect_string" {
  value = module.staging_canvas.ssh_connect_string
}

output "staging_canvas_site_url" {
  value = module.staging_canvas.site_url
}

output "washu_ssh_connect_string" {
  value = module.washu_app.ssh_connect_string
}

output "washu_site_url" {
  value = module.washu_app.site_url
}

output "washu_db_connect_string" {
  value     = module.washu_app.db_connect_string
  sensitive = true
}

output "washu_canvas_ssh_connect_string" {
  value = module.washu_canvas.ssh_connect_string
}

output "washu_canvas_site_url" {
  value = module.washu_canvas.site_url
}
