@echo off
echo ========================================
echo Starting Laravel Scheduler
echo ========================================
echo.
echo This will run the scheduler every minute
echo Press Ctrl+C to stop
echo.

php artisan schedule:work 