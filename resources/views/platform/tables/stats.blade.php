<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $totalTables }}</h4>
                        <p class="card-text">Tổng số bàn</p>
                    </div>
                    <div class="align-self-center">
                        <i class="icon-table fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $availableTables }}</h4>
                        <p class="card-text">Bàn trống</p>
                    </div>
                    <div class="align-self-center">
                        <i class="icon-check fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $occupiedTables }}</h4>
                        <p class="card-text">Đang sử dụng</p>
                    </div>
                    <div class="align-self-center">
                        <i class="icon-people fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $reservedTables }}</h4>
                        <p class="card-text">Đã đặt trước</p>
                    </div>
                    <div class="align-self-center">
                        <i class="icon-calendar fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 