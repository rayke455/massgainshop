@echo off
REM Starts Zirka Social locally at http://localhost:8000 (keep this window open while you use the site).
REM Needs XAMPP's MySQL running (XAMPP Control Panel -> MySQL -> Start).
cd /d "%~dp0project\project"
REM Background jobs (order status, refunds, refills, currency rates, balance alerts) - the local "cron".
start "Zirka Social - background jobs" /min "C:\xampp\php\php.exe" artisan schedule:work
echo Zirka Social is running at http://localhost:8000  (close this window to stop)
start "" http://localhost:8000
"C:\xampp\php\php.exe" -d zend_extension=opcache -d opcache.enable_cli=1 -S 127.0.0.1:8000 -t . server-router.php
