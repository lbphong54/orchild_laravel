@php
    $statusColors = [
        'available' => 'success',
        'occupied' => 'danger', 
        'reserved' => 'warning'
    ];
    
    $statusLabels = [
        'available' => 'Trống',
        'occupied' => 'Đang sử dụng',
        'reserved' => 'Đã đặt trước'
    ];
    
    $color = $statusColors[$status] ?? 'secondary';
    $label = $statusLabels[$status] ?? $status;
@endphp

<span class="badge bg-{{ $color }}">{{ $label }}</span> 