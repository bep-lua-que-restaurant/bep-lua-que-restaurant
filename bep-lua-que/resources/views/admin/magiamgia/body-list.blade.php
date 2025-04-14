{{-- <table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Mã</th>
            <th>Loại</th>
            <th>Giá trị</th>
            <th>Hiệu lực</th>
            <th>Số lượt đã dùng</th>
            <th>Trạng thái </th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $index => $item)
            <tr class="ma-giam-gia-row">
                <td>{{ $item->id }}</td>
                <td class="ma-giam-gia-row">{{ $item->code }}</td>
                <td>{{ $item->type == 'percentage' ? 'Phần trăm' : 'Tiền' }}</td>

                <td>
                    @if ($item->type == 'percentage')
                        {{ number_format($item->value, 0, ',', '.') . '%' }}
                    @else
                        {{ number_format($item->value, 0, ',', '.') . 'VND' }}
                    @endif
                </td>


                <td>
                    @if ($item->start_date && $item->end_date)
                        Từ: {{ \Carbon\Carbon::parse($item->start_date)->format('d/m/Y') }}<br>
                        Đến: {{ \Carbon\Carbon::parse($item->end_date)->format('d/m/Y') }}
                    @else
                        Không xác định
                    @endif
                </td>
                <td>{{ $item->usage_count ?? 0 }}</td>
                <td class="trang-thai-ma-giam-gia">
                    {{ $item->deleted_at ? 'Đã ngừng hoạt động' : 'Đang hoạt động' }}
                </td>


                <td>
                    <div class="d-flex align-items-center">
                        <a href="{{ route('ma-giam-gia.show', $item->id) }}" class="btn btn-info btn-sm m-1">
                            <i class="fa fa-eye"></i>
                        </a>
                        <a href="{{ route('ma-giam-gia.edit', $item->id) }}" class="btn btn-warning btn-sm m-1">
                            <i class="fa fa-edit"></i>
                        </a>
                        @if ($item->deleted_at)
                            <form action="{{ route('ma-giam-gia.restore', $item->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                <button type="submit"
                                    onclick="return confirm('Bạn có chắc muốn khôi phục mục này không?')"
                                    class="btn btn-success btn-sm m-1" title="Khôi phục">
                                    <i class="fa fa-recycle"></i>
                                </button>
                            </form>
                        @else
                            <form action="{{ route('ma-giam-gia.destroy', $item->id) }}" method="POST"
                                style="display:inline;">
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
        @endforeach
    </tbody>
</table> --}}
