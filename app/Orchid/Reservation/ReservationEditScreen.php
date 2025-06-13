// (Chỉ trích đoạn bổ sung)
Layout::rows([
    // ... các trường cũ ...
    Select::make('reservation.table_id')
        ->fromModel(\App\Models\Table::class, 'name')
        ->title('Bàn')
        ->help('Chọn bàn cho nhà hàng')
        ->required(),

    Select::make('reservation.status')
        ->options([
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'checked-in' => 'Đã đến',
            'canceled' => 'Đã hủy',
            'completed' => 'Hoàn thành',
        ])
        ->title('Trạng thái')
        ->required(),

    TextArea::make('reservation.note')
        ->title('Ghi chú')
        ->rows(2)
        ->placeholder('Yêu cầu đặc biệt, dịp sinh nhật,...')
]),