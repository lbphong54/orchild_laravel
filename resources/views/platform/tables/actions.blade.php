<div class="btn-group" role="group">
    <a href="{{ route('platform.tables.edit', $table) }}" 
       class="btn btn-sm btn-outline-primary" 
       title="Chỉnh sửa">
        <i class="icon-pencil"></i>
    </a>
    
    <button type="button" 
            class="btn btn-sm btn-outline-danger" 
            title="Xóa bàn"
            onclick="confirmDelete({{ $table->id }})">
        <i class="icon-trash"></i>
    </button>
</div>

<script>
function confirmDelete(tableId) {
    if (confirm('Bạn có chắc chắn muốn xóa bàn này?')) {
        // Tạo form ẩn để submit delete request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("platform.tables.edit", $table) }}';
        
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
</script> 