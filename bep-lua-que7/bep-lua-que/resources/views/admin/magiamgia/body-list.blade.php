<table class="table align-middle text-center custom-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Mã</th>
            <th>Loại</th>
            <th>Giá trị</th>
            <th>Hiệu lực</th>
            <th>Đã dùng</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr @if($item->deleted_at) class="table-danger" @endif>
                <td>{{ $item->id }}</td>
                <td><span class="fw-bold text-primary">{{ $item->code }}</span></td>
                <td>
                    <span class="status-badge {{ $item->type == 'percent' ? 'bg-info' : 'bg-success' }}">
                        {{ $item->type == 'percent' ? 'Phần trăm' : 'Tiền mặt' }}
                    </span>
                </td>
                <td>
                    <span class="status-badge bg-dark">
                        {{ $item->type == 'percent' ? $item->value . '%' : number_format($item->value, 0, ',', '.') . '₫' }}
                    </span>
                </td>
                <td>
                    @if($item->start_date && $item->end_date)
                        <small>
                            {{ \Carbon\Carbon::parse($item->start_date)->format('d/m/Y') }} <br>
                            – {{ \Carbon\Carbon::parse($item->end_date)->format('d/m/Y') }}
                        </small>
                    @else
                        <span class="text-muted fst-italic">Không xác định</span>
                    @endif
                </td>
                <td>
                    <span class="status-badge bg-secondary">{{ $item->usage_count ?? 0 }}</span>
                </td>
                <td>
                    <div class="btn-group action-buttons">
                        <a href="{{ route('ma-giam-gia.show', $item->id) }}" class="btn btn-view" title="Xem">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('ma-giam-gia.edit', $item->id) }}" class="btn btn-edit" title="Sửa">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if ($item->deleted_at)
                            <form action="{{ route('ma-giam-gia.restore', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-restore" title="Khôi phục" onclick="return confirm('Khôi phục mã này?')">
                                    <i class="fas fa-recycle"></i>
                                </button>
                            </form>
                        @else
                            <form action="{{ route('ma-giam-gia.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-delete" title="Xóa" onclick="return confirm('Ngừng mã này?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<style>
    .custom-table th {
        background-color: #f8f9fa;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 14px;
    }

    .custom-table td {
        vertical-align: middle;
        font-size: 15px;
    }

    .status-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        color: white;
    }

    .bg-info {
        background-color: #17a2b8 !important;
    }

    .bg-success {
        background-color: #28a745 !important;
    }

    .bg-dark {
        background-color: #343a40 !important;
    }

    .bg-secondary {
        background-color: #6c757d !important;
    }

    .action-buttons .btn {
        border-radius: 8px;
        padding: 5px 10px;
        margin: 0 2px;
        color: white;
    }

    .btn-view {
        background-color: #00bcd4;
    }

    .btn-edit {
        background-color: #ffc107;
        color: black;
    }

    .btn-delete {
        background-color: #f44336;
    }

    .btn-restore {
        background-color: #28a745;
    }

    .action-buttons .btn i {
        font-size: 14px;
    }

    .action-buttons .btn:hover {
        opacity: 0.85;
    }
</style>
