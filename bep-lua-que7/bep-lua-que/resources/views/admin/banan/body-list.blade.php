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
        {{-- <td>
            <div class="d-flex align-items-center"><span class="w-space-no">{{ $item->so_ghe }}</span></div>
        </td> --}}


        <!-- Trạng thái (Đang sử dụng / Ngừng sử dụng) -->
        <td>
            @if ($item->deleted_at)
                <div class="d-flex align-items-center">
                    <i class="fa fa-circle text-danger mr-1"></i> Ngừng sử dụng
                </div>
            @elseif ($item->phongAn && $item->phongAn->deleted_at)
                <div class="d-flex align-items-center">
                    <i class="fa fa-circle text-warning mr-1"></i> Bàn không thuộc phòng nào
                </div>
            @else
                <div class="d-flex align-items-center">
                    <i class="fa fa-circle text-success mr-1"></i> Đang sử dụng
                </div>
            @endif
        </td>


        @php
            $trangThaiLabels = [
                'trong' => 'Trống',
                'co_khach' => 'Có khách',
                'da_dat_truoc' => 'Đã đặt trước',
            ];
        @endphp

        <td>
            <div class="d-flex align-items-center">
                <span class="w-space-no">
                    {{ $trangThaiLabels[$item->trang_thai] ?? $item->trang_thai }}
                </span>
            </div>
        </td>

        <!-- Hành động: Xem, Sửa, Xóa, Khôi phục -->
        <td>
            <div class="d-flex align-items-center">

                <button class="btn btn-info btnChiTietBanAn" data-id="{{ $item->id }}">
                    <i class="fa fa-eye"></i>
                </button>

                <!-- Nút xem chi tiết -->
                @if (!$item->deleted_at)
                    <button class="btn btn-warning btn-sm p-2 m-2 btnEditBanAn" data-id="{{ $item->id }}">
                        <i class="fa fa-edit"></i>
                    </button>
                @endif

                @if ($item->deleted_at)
                    <!-- Nút khôi phục nếu bàn ăn đã bị xóa -->
                    <button type="button" class="btn btn-success btn-sm p-2 m-2 btnRestoreBanAn"
                        data-id="{{ $item->id }}" title="Khôi phục"
                        onclick="return confirm('Bạn có chắc muốn khôi phục bàn ăn này không?')">
                        <i class="fa fa-recycle"></i>
                    </button>
                @else
                    <!-- Nút xóa (Ngừng sử dụng bàn ăn) -->
                    @if ($item->trang_thai === 'trong')
                        <button class="btn btn-danger btn-sm p-2 m-2 btnDeleteBanAn" data-id="{{ $item->id }}">
                            <i class="fa fa-trash"></i>
                        </button>
                    @endif
                @endif
            </div>
        </td>
    </tr>
@endforeach
