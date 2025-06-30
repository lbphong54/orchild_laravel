# Chức năng Hủy Đơn Hàng và Gửi Mail

## Tổng quan

Hệ thống đã được bổ sung chức năng gửi mail thông báo khi hủy đơn hàng với logic hoàn tiền dựa trên thời gian hủy:

- **Hủy trước 1 tiếng**: Được hoàn tiền trong vòng 3-5 ngày làm việc
- **Hủy trong vòng 1 tiếng**: Không được hoàn tiền

## Các file đã tạo/cập nhật

### 1. Mail Class
- `app/Mail/ReservationCancellationMail.php` - Class gửi mail hủy đơn hàng

### 2. Email Template
- `resources/views/emails/reservation_cancellation.blade.php` - Template email hủy đơn hàng

### 3. Job
- `app/Jobs/SendReservationCancellationJob.php` - Job xử lý gửi mail hủy đơn hàng

### 4. API Controller
- `app/Http/Controllers/Api/ReservationController.php` - Thêm method `cancel()`

### 5. Admin Panel
- `app/Orchid/Screens/Reservation/ReservationListScreen.php` - Cập nhật để gửi mail khi admin hủy đơn

### 6. Routes
- `routes/api.php` - Thêm route `POST /api/reservations/{id}/cancel`

### 7. Test Command
- `app/Console/Commands/TestReservationCancellation.php` - Command test chức năng

## Cách sử dụng

### 1. Hủy đơn hàng qua API

```bash
POST /api/reservations/{id}/cancel
Authorization: Bearer {token}
```

**Response thành công:**
```json
{
    "status": "success",
    "message": "Hủy đơn hàng thành công",
    "data": {
        "reservation_id": 123,
        "cancellation_time": "2024-01-15 14:30:00",
        "hours_before_reservation": 2,
        "is_refundable": true,
        "refund_message": "Đơn hàng sẽ được hoàn tiền trong vòng 3-5 ngày làm việc."
    }
}
```

### 2. Hủy đơn hàng qua Admin Panel

1. Đăng nhập vào admin panel
2. Vào "Quản lý đơn đặt bàn"
3. Click vào icon "eye" để xem chi tiết đơn hàng
4. Thay đổi trạng thái thành "Đã hủy"
5. Click "Cập nhật"

Hệ thống sẽ tự động gửi email thông báo hủy đơn hàng.

### 3. Test chức năng

**Xem danh sách đơn hàng có thể test:**
```bash
php artisan reservations:test-cancellation
```

**Test hủy đơn hàng cụ thể:**
```bash
# Test hủy trước 2 tiếng (được hoàn tiền)
php artisan reservations:test-cancellation --reservation-id=123 --hours-before=2

# Test hủy trước 30 phút (không được hoàn tiền)
php artisan reservations:test-cancellation --reservation-id=123 --hours-before=0.5
```

## Logic hoàn tiền

- **Hủy trước 1 tiếng trở lên**: 
  - `isRefundable = true`
  - Thông báo: "Đơn hàng của bạn sẽ được hoàn tiền trong vòng 3-5 ngày làm việc."
  - Email có màu xanh lá

- **Hủy trong vòng 1 tiếng**:
  - `isRefundable = false`
  - Thông báo: "Do hủy đơn trong vòng 1 giờ trước thời gian đặt bàn, đơn hàng không được hoàn tiền theo chính sách của chúng tôi."
  - Email có màu đỏ

## Các trường hợp không cho phép hủy

1. Đơn hàng đã được hủy trước đó
2. Đơn hàng đã hoàn thành
3. Đã quá thời gian đặt bàn

## Cấu hình Queue

Để đảm bảo email được gửi bất đồng bộ, cần cấu hình queue:

```bash
# Chạy queue worker
php artisan queue:work

# Hoặc chạy trong background
php artisan queue:work --daemon
```

## Log

Hệ thống sẽ log các hoạt động gửi mail:

- **Thành công**: `Reservation cancellation email sent successfully`
- **Lỗi**: `Failed to send reservation cancellation email`

Log được lưu trong `storage/logs/laravel.log` 