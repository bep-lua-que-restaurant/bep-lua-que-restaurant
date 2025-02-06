@foreach ($data as $index => $item)
    <tr data-toggle="collapse" data-target="#detail{{ $index }}" class="clickable-row">
        <!-- Checkbox -->
        <td>
            <div class="custom-control custom-checkbox checkbox-success check-lg mr-3">
                <input type="checkbox" class="custom-control-input" id="customCheckBox{{ $index }}" required="">
                <label class="custom-control-label" for="customCheckBox{{ $index }}"></label>
            </div>
        </td>

        <!-- ID của bàn ăn -->
        <td><strong>{{ $item->id }}</strong></td>

        <!-- Tên bàn ăn -->
        <td>
            <div class="d-flex align-items-center"><span class="w-space-no">{{ $item->ten_ban }}</span></div>
        </td>

        <!-- Số ghế -->
        <td>
            <div class="d-flex align-items-center"><span class="w-space-no">{{ $item->so_ghe }}</span></div>
        </td>


        <!-- Trạng thái (Đang sử dụng / Ngừng sử dụng) -->
        <td>
            @if ($item->deleted_at != null)
                <div class="d-flex align-items-center">
                    <i class="fa fa-circle text-danger mr-1"></i> Ngừng sử dụng
                </div>
            @else
                <div class="d-flex align-items-center">
                    <i class="fa fa-circle text-success mr-1"></i> Đang sử dụng
                </div>
            @endif
        </td>

        <!-- Hành động: Xem, Sửa, Xóa, Khôi phục -->
        <td>
            <div class="d-flex align-items-center">
                <!-- Nút xem chi tiết -->
                <a href="{{ route('ban-an.show', $item->id) }}" class="btn btn-info btn-sm p-2 m-2"
                    title="Xem chi tiết">
                    <i class="fa fa-eye"></i>
                </a>


                <!-- Nút chỉnh sửa -->
                <a href="{{ route('ban-an.edit', $item->id) }}" class="btn btn-warning btn-sm p-2 m-2">
                    <i class="fa fa-edit"></i>
                </a>


                @if ($item->deleted_at)
                    <!-- Nút khôi phục nếu bàn ăn đã bị xóa -->
                    <form action="{{ route('ban-an.restore', $item->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" onclick="return confirm('Bạn có chắc muốn khôi phục bàn ăn này không?')"
                            class="btn btn-success btn-sm p-2 m-2" title="Khôi phục">
                            <i class="fa fa-recycle"></i>
                        </button>
                    </form>
                @else
                    <!-- Nút xóa (Ngừng sử dụng bàn ăn) -->
                    <form action="{{ route('ban-an.destroy', $item->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            onclick="return confirm('Bạn có chắc muốn ngừng sử dụng bàn ăn này không?')"
                            class="btn btn-danger btn-sm  p-2 m-2">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                @endif
            </div>
        </td>
    </tr>
@endforeach
