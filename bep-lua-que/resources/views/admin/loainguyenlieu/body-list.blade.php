@foreach ($data as $index => $item)
    <tr>
        <td><strong>{{ $item->id }}</strong></td>
        <td>{{ $item->ma_loai }}</td>
        <td>{{ $item->ten_loai }}</td>


        <td>
            @if ($item->deleted_at != null)
                <div class="d-flex align-items-center">
                    <i class="fa fa-circle text-danger mr-1"></i> Không sử dụng
                </div>
            @else
                <div class="d-flex align-items-center">
                    <i class="fa fa-circle text-success mr-1"></i> Đang sử dụng
                </div>
            @endif
        </td>

        <td>
            <div class="d-flex align-items-center">
                <a href="{{ route('loai-nguyen-lieu.show', $item->id) }}" class="btn btn-info btn-sm p-2 m-2">
                    <i class="fa fa-eye"></i>
                </a>
                <a href="{{ route('loai-nguyen-lieu.edit', $item->id) }}" class="btn btn-warning btn-sm p-2 m-2">
                    <i class="fa fa-edit"></i>
                </a>

                @if ($item->deleted_at)
                    <form action="{{ route('loai-nguyen-lieu.restore', $item->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" onclick="return confirm('Bạn có chắc muốn khôi phục mục này không?')"
                            class="btn btn-success btn-sm p-2 m-2">
                            <i class="fa fa-recycle"></i>
                        </button>
                    </form>
                @else
                    <form action="{{ route('loai-nguyen-lieu.destroy', $item->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Bạn muốn ngừng sử dụng loại nguyên liệu này chứ?')"
                            class="btn btn-danger btn-sm p-2 m-2">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                @endif
            </div>
        </td>
    </tr>
@endforeach
