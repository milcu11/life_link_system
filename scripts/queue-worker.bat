@echo off
REM Start Laravel queue worker in background and log output to storage/logs/queue.log
cd /d %~dp0
cd ..\lifelink_system
start "Laravel Queue" /B php artisan queue:work --sleep=3 --tries=3 >> storage\logs\queue.log 2>&1
echo Queue worker started (background). Logs: storage\logs\queue.log
