<div class="row">
    @foreach($tables as $table)
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card h-100 table-card" data-table-id="{{ $table->id }}">
                <a href="{{ route('platform.tables.edit', $table) }}">
                    <div class="card-body text-center">
                        <div class="table-icon mb-3">
                            <i class="icon-table fs-1"></i>
                        </div>

                        <h5 class="card-title">{{ $table->name }}</h5>

                        <p class="card-text">
                            <small class="text-muted">
                                {{ $table->min_capacity }} - {{ $table->max_capacity }} người
                            </small>
                        </p>

                        <div class="mb-3">
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

                                $color = $statusColors[$table->status] ?? 'secondary';
                                $label = $statusLabels[$table->status] ?? $table->status;
                            @endphp

                            <span class="badge bg-{{ $color }}">{{ $label }}</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    @endforeach
</div>

<style>
    .table-card {
        transition: transform 0.2s ease-in-out;
        border: 2px solid transparent;
    }

    .table-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .table-card[data-status="available"] {
        border-color: #28a745;
    }

    .table-card[data-status="occupied"] {
        border-color: #dc3545;
    }

    .table-card[data-status="reserved"] {
        border-color: #ffc107;
    }

    .table-icon {
        color: #6c757d;
    }
</style>

<script>
    function confirmDelete(tableId) {
        if (confirm('Bạn có chắc chắn muốn xóa bàn này?')) {
            // Tạo form ẩn để submit delete request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/tables/${tableId}/edit`;

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';

            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = '{{ csrf_token() }}';

            form.appendChild(methodInput);
            form.appendChild(tokenInput);
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Cập nhật border color dựa trên trạng thái
    document.addEventListener('DOMContentLoaded', function () {
        const tableCards = document.querySelectorAll('.table-card');
        tableCards.forEach(card => {
            const status = card.querySelector('.badge').textContent.trim();
            if (status === 'Trống') {
                card.setAttribute('data-status', 'available');
            } else if (status === 'Đang sử dụng') {
                card.setAttribute('data-status', 'occupied');
            } else if (status === 'Đã đặt trước') {
                card.setAttribute('data-status', 'reserved');
            }
        });
    });

</script>