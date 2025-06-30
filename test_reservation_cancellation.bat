@echo off
echo Testing Reservation Cancellation Feature
echo ======================================

echo.
echo 1. Showing available reservations for testing...
php artisan reservations:test-cancellation

echo.
echo 2. To test a specific reservation, use:
echo    php artisan reservations:test-cancellation --reservation-id=ID --hours-before=HOURS
echo.
echo    Examples:
echo    - Test cancellation 2 hours before (refundable):
echo      php artisan reservations:test-cancellation --reservation-id=1 --hours-before=2
echo.
echo    - Test cancellation 30 minutes before (not refundable):
echo      php artisan reservations:test-cancellation --reservation-id=1 --hours-before=0.5
echo.

pause 