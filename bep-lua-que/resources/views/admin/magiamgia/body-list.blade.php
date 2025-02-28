<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Mã</th>
            <th>Loại</th>
            <th>Giá trị</th>
            <th>Hiệu lực</th>
            <th>Số lượt đã dùng</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $index => $item)
            <tr data-toggle="collapse" data-target="#detail{{ $index }}" class="clickable-row">
                <td>{{ $item->id }}</td>
                <td>{{ $item->code }}</td>
                <td>{{ $item->type }}</td>
                <td>{{ $item->value }}</td>
                <td>
                    @if($item->start_date && $item->end_date)
                        Từ: {{ \Carbon\Carbon::parse($item->start_date)->format('d/m/Y') }}<br>
                        Đến: {{ \Carbon\Carbon::parse($item->end_date)->format('d/m/Y') }}
                    @else
                        Không xác định
                    @endif
                </td>
                <td>{{ $item->usage_count ?? 0 }}</td>
                <td>
                    <div class="d-flex align-items-center">
                        <a href="{{ route('ma-giam-gia.show', $item->id) }}" class="btn btn-info btn-sm m-1">
                            <i class="fa fa-eye"></i>
                        </a>
                        <a href="{{ route('ma-giam-gia.edit', $item->id) }}" class="btn btn-warning btn-sm m-1">
                            <i class="fa fa-edit"></i>
                        </a>
                        @if ($item->deleted_at)
                            <form action="{{ route('ma-giam-gia.restore', $item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" onclick="return confirm('Bạn có chắc muốn khôi phục mục này không?')"
                                    class="btn btn-success btn-sm m-1" title="Khôi phục">
                                    <i class="fa fa-recycle"></i>
                                </button>
                            </form>
                        @else
                            <form action="{{ route('ma-giam-gia.destroy', $item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Bạn muốn ngừng mã giảm này chứ?')"
                                    class="btn btn-danger btn-sm m-1" title="Xóa">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            <!-- Nếu muốn có hàng chi tiết ẩn hiện, bạn có thể thêm row dưới dạng collapse -->
            <tr id="detail{{ $index }}" class="collapse">
                <td colspan="7">
                    <!-- Nội dung chi tiết của row, có thể hiển thị thêm thông tin nếu cần -->
                    <strong>Mã giảm giá:</strong> {{ $item->code }} <br>
                    <strong>Loại:</strong> {{ $item->type }} <br>
                    <strong>Giá trị:</strong> {{ $item->value }} <br>
                    <strong>Hiệu lực:</strong> 
                        @if($item->start_date && $item->end_date)
                            Từ {{ \Carbon\Carbon::parse($item->start_date)->format('d/m/Y') }} đến {{ \Carbon\Carbon::parse($item->end_date)->format('d/m/Y') }}
                        @else
                            Không xác định
                        @endif <br>
                    <strong>Số lượt đã dùng:</strong> {{ $item->usage_count ?? 0 }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
