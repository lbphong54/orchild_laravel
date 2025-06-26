<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Xác nhận đặt bàn thành công</title>
</head>
<body>
    <h2>Đặt bàn thành công!</h2>
    <p>Cảm ơn bạn đã đặt bàn tại nhà hàng của chúng tôi.</p>
    <ul>
        <li><strong>Mã đơn:</strong> {{ $orderCode }}</li>
        <li><strong>Thời gian đặt:</strong> {{ $reservationTime }}</li>
        <li><strong>Số bàn:</strong> {{ $tableNumber }}</li>
        <li><strong>Số lượng người lớn:</strong> {{ $adults }}</li>
        <li><strong>Số lượng trẻ em:</strong> {{ $children }}</li>
    </ul>
    <p>Chúng tôi rất mong được phục vụ bạn!</p>
</body>
</html>