@foreach ($data as $index => $item)
    <tr data-toggle="collapse" data-target="#detail{{ $index }}" class="clickable-row">
        <td>
            <div class="custom-control custom-checkbox checkbox-success check-lg mr-3">
                <input type="checkbox" class="custom-control-input" id="customCheckBox2" required="">
                <label class="custom-control-label" for="customCheckBox2"></label>
            </div>
        </td>
        <td><strong>{{ $item->id }}</strong></td>
        <td>
            <div class="d-flex align-items-center"><span class="w-space-no">{{ $item->ten }}</span></div>
        </td>

        <!-- Trạng thái kinh doanh -->
        <td>
            @if ($item->deleted_at)
                <div class="d-flex align-items-center"><i class="fa fa-circle text-danger mr-1"></i> Đã ngừng kinh doanh</div>
            @else
                <div class="d-flex align-items-center"><i class="fa fa-circle text-success mr-1"></i> Đang kinh doanh</div>
            @endif
        </td>

        <!-- Trạng thái món ăn -->
        <td>
            @if ($item->trang_thai === 'dang_ban')
                <span class="badge badge-success">Đang bán</span>
            @elseif ($item->trang_thai === 'het_hang')
                <span class="badge badge-warning">Hết hàng</span>
            @elseif ($item->trang_thai === 'ngung_ban')
                <span class="badge badge-danger">Ngừng bán</span>
            @endif
        </td>

        <td>
            <div class="d-flex align-items-center">
                <a href="{{ route('mon-an.show', $item->id) }}" class="btn btn-info btn-sm p-2 m-2">
                    <i class="fa fa-eye"></i>
                </a>
                <a href="{{ route('mon-an.edit', $item->id) }}" class="btn btn-warning btn-sm p-2 m-2">
                    <i class="fa fa-edit"></i>
                </a>
                @if ($item->deleted_at)
                    <form action="{{ route('mon-an.restore', $item->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" onclick="return confirm('Bạn có chắc muốn khôi phục mục này không?')"
                            class="btn btn-success btn-sm p-2 m-2" title="Khôi phục">
                            <i class="fa fa-recycle"></i>
                        </button>
                    </form>
                @else
                    <form action="{{ route('mon-an.destroy', $item->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Bạn muốn ngừng kinh doanh mục này chứ?')"
                            class="btn btn-danger btn-sm p-2 m-2" title="Xóa">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                @endif
            </div>
        </td>
    </tr>
@endforeach
