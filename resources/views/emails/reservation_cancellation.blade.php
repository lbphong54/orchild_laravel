<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Thông báo hủy đơn đặt bàn</title>
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
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .content {
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .reservation-details {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .refund-notice {
            background-color: {{ $isRefundable ? '#d4edda' : '#f8d7da' }};
            color: {{ $isRefundable ? '#155724' : '#721c24' }};
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            border: 1px solid {{ $isRefundable ? '#c3e6cb' : '#f5c6cb' }};
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Thông báo hủy đơn đặt bàn</h2>
    </div>
    
    <div class="content">
        <p>Xin chào <strong>{{ $reservation->customer->full_name }}</strong>,</p>
        
        <p>Chúng tôi xác nhận rằng đơn đặt bàn của bạn đã được hủy thành công.</p>
        
        <div class="reservation-details">
            <h3>Chi tiết đơn đặt bàn:</h3>
            <ul>
                <li><strong>Mã đơn:</strong> #{{ $reservation->id }}</li>
                <li><strong>Nhà hàng:</strong> {{ $reservation->restaurant->name }}</li>
                <li><strong>Thời gian đặt bàn:</strong> {{ $reservation->reservation_time->format('d/m/Y H:i') }}</li>
                <li><strong>Số người lớn:</strong> {{ $reservation->num_adults }}</li>
                <li><strong>Số trẻ em:</strong> {{ $reservation->num_children }}</li>
                <li><strong>Thời gian hủy:</strong> {{ $cancellationTime->format('d/m/Y H:i') }}</li>
            </ul>
        </div>
        
        <div class="refund-notice">
            <h3>{{ $isRefundable ? 'Thông báo hoàn tiền' : 'Thông báo không hoàn tiền' }}</h3>
            <p>{{ $refundMessage }}</p>
        </div>
        
        <p>Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi qua:</p>
        <ul>
            <li>Email: support@restaurant.com</li>
            <li>Điện thoại: 1900-xxxx</li>
        </ul>
        
        <p>Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!</p>
    </div>
    
    <div class="footer">
        <p>Trân trọng,<br>
        <strong>{{ $reservation->restaurant->name }}</strong></p>
    </div>
</body>
</html> 