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

        <td>
            <div class="d-flex align-items-center"><i class="fa fa-circle text-success mr-1"></i> Successful</div>
        </td>
        <td>
            <div class="d-flex align-items-center">
                <a href="{{ route('danh-muc-mon-an.show', $item->id) }}" class="btn btn-info btn-sm p-2 m-2">
                    <i class="fa fa-eye"></i>
                </a>
                <a href="{{ route('danh-muc-mon-an.edit', $item->id) }}" class="btn btn-warning btn-sm p-2 m-2">
                    <i class="fa fa-edit "></i>
                </a>
                <form action="{{ route('danh-muc-mon-an.destroy', $item->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm p-2 m-2" title="Xóa">
                        <i class="fa fa-trash"></i>
                    </button>
                </form>
            </div>
        </td>


    </tr>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>

    </script>
@endforeach
