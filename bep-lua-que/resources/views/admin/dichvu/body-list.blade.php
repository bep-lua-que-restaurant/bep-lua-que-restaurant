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
            <div class="d-flex align-items-center"><span class="w-space-no">{{ $item->ten_dich_vu }}</span></div>
        </td>

        <td>
            @if ($item->deleted_at != null)
                <div class="d-flex align-items-center"><i class="fa fa-circle text-danger mr-1"></i> Đã ngừng kinh doanh
                </div>
            @else
                <div class="d-flex align-items-center"><i class="fa fa-circle text-success mr-1"></i> Đang kinh doanh
                </div>
                {{ $item->deleted_at }}
            @endif

        </td>
        <td>
            <div class="d-flex align-items-center">
                <a href="{{ route('dich-vu.show', $item->id) }}" class="btn btn-info btn-sm p-2 m-2">
                    <i class="fa fa-eye"></i>
                </a>
                <a href="{{ route('dich-vu.edit', $item->id) }}" class="btn btn-warning btn-sm p-2 m-2">
                    <i class="fa fa-edit "></i>
                </a>
                @if ($item->deleted_at)
                    <form action="{{ route('dich-vu.restore', $item->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" onclick="return confirm('Bạn có chắc muốn khôi phục mục này không?')"
                            class="btn btn-success btn-sm p-2 m-2" title="Khôi phục">
                            <i class="fa fa-recycle"></i> {{-- Icon khôi phục --}}
                        </button>
                    </form>
                @else
                    <form action="{{ route('dich-vu.destroy', $item->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Bạn muốn ngừng kinh doanh mục này chứ?')"
                            class="btn btn-danger btn-sm p-2 m-2" title="Xóa">
                            <i class="fa fa-trash"></i> {{-- Icon xóa --}}
                        </button>
                    </form>
                @endif
            </div>
        </td>


    </tr>
@endforeach
