@echo off
echo ========================================
echo Testing Reservation Reminder System
echo ========================================

echo.
echo 1. Checking available reservations...
php artisan reservations:test-reminders

echo.
echo 2. Running manual check for reminders...
php artisan reservations:check-reminders

echo.
echo 3. Starting queue worker (press Ctrl+C to stop)...
php artisan queue:work

pause 