@foreach ($data as $index => $item)
    <tr data-toggle="collapse" data-target="#detail{{ $index }}" class="clickable-row">
        <td>
            <div class="custom-control custom-checkbox checkbox-success check-lg mr-3">
                <input type="checkbox" class="custom-control-input" id="customCheckBox{{ $item->id }}" required="">
                <label class="custom-control-label" for="customCheckBox{{ $item->id }}"></label>
            </div>
        </td>
        <td><strong>{{ $item->id }}</strong></td>
        <td>
            <div class="d-flex align-items-center"><span class="w-space-no">{{ $item->ten_nguyen_lieu }}</span></div>
        </td>
        <td>
            <div class="d-flex align-items-center"><span class="w-space-no">{{ $item->loaiNguyenLieu->ten_loai }}</span>
            </div>
        </td>


        <!-- Giá nhập -->
        <td>{{ number_format($item->gia_nhap, 0, ',', '.') }} VNĐ</td>

        <!-- Số lượng tồn -->
        <td>
            @if ($item->so_luong_ton > 10)
                <span class="badge badge-success">{{ number_format($item->so_luong_ton, 0) }}</span>
            @elseif ($item->so_luong_ton > 0)
                <span class="badge badge-warning">{{ number_format($item->so_luong_ton, 0) }}</span>
            @else
                <span class="badge badge-danger">Hết hàng</span>
            @endif
        </td>
        <!-- Đơn vị tính -->
        <td>{{ $item->don_vi_tinh }}</td>

        {{-- <!-- Trạng thái kinh doanh -->
        <td>
            @if ($item->deleted_at)
                <div class="d-flex align-items-center"><i class="fa fa-circle text-danger mr-1"></i> Ngừng nhập hàng</div>
            @else
                <div class="d-flex align-items-center"><i class="fa fa-circle text-success mr-1"></i> Đang nhập hàng</div>
            @endif
        </td> --}}

        <td>
            <div class="d-flex align-items-center">
                <a href="{{ route('nguyen-lieu.show', $item->id) }}" class="btn btn-info btn-sm p-2 m-2">
                    <i class="fa fa-eye"></i>
                </a>
                {{-- <a href="{{ route('nguyen-lieu.edit', $item->id) }}" class="btn btn-warning btn-sm p-2 m-2">
                    <i class="fa fa-edit"></i>
                </a>
                @if ($item->deleted_at)
                    <form action="{{ route('nguyen-lieu.restore', $item->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" onclick="return confirm('Bạn có chắc muốn khôi phục nguyên liệu này không?')"
                            class="btn btn-success btn-sm p-2 m-2" title="Khôi phục">
                            <i class="fa fa-recycle"></i>
                        </button>
                    </form>
                @else
                    <form action="{{ route('nguyen-lieu.destroy', $item->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Bạn muốn ngừng nhập nguyên liệu này chứ?')"
                            class="btn btn-danger btn-sm p-2 m-2" title="Xóa">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                @endif --}}
            </div>
        </td>
    </tr>
@endforeach
