<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhắc nhở đặt bàn</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #dee2e6;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 15px;
            text-align: center;
            border-radius: 0 0 8px 8px;
            font-size: 12px;
            color: #6c757d;
        }
        .highlight {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .label {
            font-weight: bold;
            color: #495057;
        }
        .value {
            color: #212529;
        }
        .urgent {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🍽️ Nhắc nhở đặt bàn</h1>
        <p>Đơn đặt bàn của bạn sắp đến giờ!</p>
    </div>

    <div class="content">
        <div class="highlight">
            <h3>⏰ Thông báo quan trọng</h3>
            <p>Đơn đặt bàn của bạn sẽ đến giờ trong <strong class="urgent">{{ $timeUntilReservation }}</strong></p>
        </div>

        <h3>📋 Chi tiết đơn đặt bàn</h3>
        
        <div class="info-row">
            <span class="label">Mã đơn hàng:</span>
            <span class="value">#{{ $reservation->id }}</span>
        </div>

        <div class="info-row">
            <span class="label">Nhà hàng:</span>
            <span class="value">{{ $restaurant->name }}</span>
        </div>

        <div class="info-row">
            <span class="label">Địa chỉ:</span>
            <span class="value">{{ $restaurant->address }}</span>
        </div>

        <div class="info-row">
            <span class="label">Số điện thoại:</span>
            <span class="value">{{ $restaurant->phone }}</span>
        </div>

        <div class="info-row">
            <span class="label">Giờ đặt bàn:</span>
            <span class="value">{{ \Carbon\Carbon::parse($reservation->reservation_time)->format('H:i - d/m/Y') }}</span>
        </div>

        <div class="info-row">
            <span class="label">Số người:</span>
            <span class="value">{{ $reservation->num_adults }} người lớn, {{ $reservation->num_children }} trẻ em</span>
        </div>

        @if($reservation->special_request)
        <div class="info-row">
            <span class="label">Ghi chú:</span>
            <span class="value">{{ $reservation->special_request }}</span>
        </div>
        @endif

        <div style="margin-top: 20px; padding: 15px; background-color: #e7f3ff; border-radius: 5px;">
            <h4>💡 Lưu ý:</h4>
            <ul>
                <li>Vui lòng đến đúng giờ để đảm bảo bàn được giữ</li>
                <li>Nếu có thay đổi, vui lòng liên hệ nhà hàng sớm nhất</li>
                <li>Mang theo thông tin đặt bàn khi đến nhà hàng</li>
            </ul>
        </div>
    </div>

    <div class="footer">
        <p>Email này được gửi tự động từ hệ thống đặt bàn</p>
        <p>Nếu có thắc mắc, vui lòng liên hệ: {{ $restaurant->phone }}</p>
    </div>
</body>
</html> 