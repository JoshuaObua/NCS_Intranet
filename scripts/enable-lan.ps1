# Run in an administrator PowerShell to enable access from this local network.
$ErrorActionPreference = 'Stop'
$ruleName = 'NCS-Intranet-LAN-HTTP'
$apachePath = 'C:\laragon\bin\apache\httpd-2.4.68-260617-Win64-VS18\bin\httpd.exe'
if (-not (Get-NetFirewallRule -Name $ruleName -ErrorAction SilentlyContinue)) {
    New-NetFirewallRule -Name $ruleName -DisplayName 'NCS Intranet - local network HTTP' -Direction Inbound -Action Allow -Protocol TCP -LocalPort 80 -RemoteAddress LocalSubnet -Profile Any -Program $apachePath | Out-Null
}
