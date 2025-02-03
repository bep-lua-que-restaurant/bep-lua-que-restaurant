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
            <div class="d-flex align-items-center"><span class="w-space-no">{{ $item->ten_ca }}</span></div>
        </td>

        <td>
            <div class="d-flex align-items-center">
                <span class="w-space-no">{{ $item->gio_bat_dau }} - {{ $item->gio_ket_thuc }}</span>
            </div>
        </td>

        <td>
            <div class="d-flex align-items-center">
                <?php
                // Tính toán tổng thời gian làm việc
                $startTime = \Carbon\Carbon::parse($item->gio_bat_dau);
                $endTime = \Carbon\Carbon::parse($item->gio_ket_thuc);
                $duration = $startTime->diff($endTime);
                
                // Tổng thời gian làm việc (giờ và phút)
                $totalHours = $duration->h;
                $totalMinutes = $duration->i;
                ?>
                <span class="w-space-no">{{ $totalHours }} giờ {{ $totalMinutes }} phút</span>
            </div>
        </td>

        <td>
            @if ($item->deleted_at != null)
                <div class="d-flex align-items-center"><i class="fa fa-circle text-danger mr-1"></i> Đã ngừng hoạt động
                </div>
            @else
                <div class="d-flex align-items-center"><i class="fa fa-circle text-success mr-1"></i> Đang hoạt động
                </div>
                {{ $item->deleted_at }}
            @endif

        </td>
        <td>
            <div class="d-flex align-items-center">
                <a href="{{ route('danh-muc-mon-an.show', $item->id) }}" class="btn btn-info btn-sm p-2 m-2">
                    <i class="fa fa-eye"></i>
                </a>
                <a href="{{ route('danh-muc-mon-an.edit', $item->id) }}" class="btn btn-warning btn-sm p-2 m-2">
                    <i class="fa fa-edit "></i>
                </a>
                @if ($item->deleted_at)
                    <form action="{{ route('danh-muc-mon-an.restore', $item->id) }}" method="POST"
                        style="display:inline;">
                        @csrf
                        <button type="submit" onclick="return confirm('Bạn có chắc muốn khôi phục mục này không?')"
                            class="btn btn-success btn-sm p-2 m-2" title="Khôi phục">
                            <i class="fa fa-recycle"></i> {{-- Icon khôi phục --}}
                        </button>
                    </form>
                @else
                    <form action="{{ route('danh-muc-mon-an.destroy', $item->id) }}" method="POST"
                        style="display:inline;">
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
