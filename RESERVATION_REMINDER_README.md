# Chức năng Gửi Mail Nhắc nhở Đơn hàng

## Mô tả
Hệ thống tự động kiểm tra đơn hàng gần đến giờ nhận bàn và gửi mail nhắc nhở cho khách hàng mỗi 5 giây.

## Các thành phần

### 1. Mail Template
- **File**: `app/Mail/ReservationReminderMail.php`
- **Template**: `resources/views/emails/reservation_reminder.blade.php`
- **Chức năng**: Gửi email nhắc nhở với thông tin chi tiết đơn hàng

### 2. Job Queue
- **File**: `app/Jobs/SendReservationReminderJob.php`
- **Chức năng**: Xử lý việc gửi mail trong background

### 3. Command Kiểm tra
- **File**: `app/Console/Commands/CheckReservationReminders.php`
- **Command**: `php artisan reservations:check-reminders`
- **Chức năng**: Kiểm tra đơn hàng cần gửi nhắc nhở

### 4. Command Test
- **File**: `app/Console/Commands/TestReservationReminders.php`
- **Command**: `php artisan reservations:test-reminders`
- **Chức năng**: Test chức năng gửi mail

### 5. Schedule
- **File**: `app/Console/Kernel.php`
- **Chức năng**: Chạy command kiểm tra mỗi 5 giây

## Cách hoạt động

### Thời gian gửi nhắc nhở
- **30 phút** trước giờ đặt bàn
- **15 phút** trước giờ đặt bàn  
- **5 phút** trước giờ đặt bàn

### Logic kiểm tra
1. Hệ thống chạy mỗi 5 giây
2. Kiểm tra các đơn hàng có status = 'confirmed'
3. Tìm đơn hàng đúng thời điểm cần gửi nhắc nhở
4. Sử dụng cache để tránh gửi trùng lặp
5. Dispatch job để gửi mail trong background

## Cài đặt và Chạy

### 1. Chạy Migration (nếu chưa có)
```bash
php artisan migrate
```

### 2. Chạy Queue Worker
```bash
php artisan queue:work
```

### 3. Chạy Schedule (trong production)
```bash
# Thêm vào crontab
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### 4. Test chức năng
```bash
# Xem danh sách đơn hàng có thể test
php artisan reservations:test-reminders

# Test gửi mail cho đơn hàng cụ thể
php artisan reservations:test-reminders --reservation-id=1
```

### 5. Chạy kiểm tra thủ công
```bash
php artisan reservations:check-reminders
```

## Cấu hình

### Mail Configuration
Đảm bảo đã cấu hình mail trong file `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email
MAIL_FROM_NAME="${APP_NAME}"
```

### Queue Configuration
Đảm bảo đã cấu hình queue trong file `.env`:
```env
QUEUE_CONNECTION=database
```

## Monitoring

### Logs
- Log được ghi vào `storage/logs/laravel.log`
- Thông tin: "Reservation reminder queued: #ID - TIME before reservation time"

### Cache
- Sử dụng cache để tránh gửi trùng lặp
- Key: `reminder_{reservation_id}_{minutes}`
- TTL: `minutes + 1` phút

## Troubleshooting

### 1. Mail không gửi được
- Kiểm tra cấu hình SMTP
- Kiểm tra queue worker có đang chạy không
- Kiểm tra log lỗi

### 2. Gửi trùng lặp
- Kiểm tra cache có hoạt động không
- Kiểm tra schedule có chạy đúng không

### 3. Không tìm thấy đơn hàng
- Kiểm tra status của đơn hàng phải là 'confirmed'
- Kiểm tra thời gian đặt bàn có đúng format không

## Tùy chỉnh

### Thay đổi thời gian nhắc nhở
Sửa trong file `app/Console/Commands/CheckReservationReminders.php`:
```php
$reminderIntervals = [
    60 => '1 giờ',    // 1 hour before
    30 => '30 phút',  // 30 minutes before
    15 => '15 phút',  // 15 minutes before
    5 => '5 phút',    // 5 minutes before
];
```

### Thay đổi tần suất kiểm tra
Sửa trong file `app/Console/Kernel.php`:
```php
$schedule->command('reservations:check-reminders')
        ->everyMinute()  // Thay vì everyFiveSeconds()
        ->withoutOverlapping()
        ->runInBackground();
``` 