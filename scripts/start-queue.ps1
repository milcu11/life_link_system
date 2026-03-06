# Start Laravel queue worker in background and pipe output to storage/logs/queue.log
$projectRoot = Split-Path -Parent $MyInvocation.MyCommand.Path
# move to project root
Set-Location (Join-Path $projectRoot '..\lifelink_system')
$log = Join-Path (Get-Location) 'storage\logs\queue.log'
$php = 'php'
$args = 'artisan','queue:work','--sleep=3','--tries=3'
Start-Process -FilePath $php -ArgumentList $args -WindowStyle Hidden -RedirectStandardOutput $log -RedirectStandardError $log
Write-Output "Queue worker started; logging to $log"
