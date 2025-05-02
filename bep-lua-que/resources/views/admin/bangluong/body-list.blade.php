@foreach ($data as $index => $item)
    <tr>
        {{-- <td>
            <div class="custom-control custom-checkbox checkbox-success check-lg mr-3">
                <input type="checkbox" class="custom-control-input" id="customCheckBox{{ $index }}">
                <label class="custom-control-label" for="customCheckBox{{ $index }}"></label>
            </div>
        </td> --}}
        <td><strong>BL{{ $item->id }}</strong></td>

        <!-- Tên nhân viên -->
        <td>{{ $item->ten_nhan_vien }}</td>

        <!-- Số công -->
        <td class="text-center">{{ $item->so_cong ?? '0' }}</td>

        <!-- Mức lương -->
        <td class="text-center">{{ number_format($item->muc_luong ?? 0, 0, ',', '.') }} VNĐ</td>

        <!-- Tổng lương -->
        <td class="text-center">{{ number_format($item->tong_luong ?? 0, 0, ',', '.') }} VNĐ</td>

        <!-- Khoảng thời gian tháng -->
        <td>
            {{ \Carbon\Carbon::parse($item->thang_nam . '-01')->startOfMonth()->format('d/m/Y') }}
            -
            {{ \Carbon\Carbon::parse($item->thang_nam . '-01')->endOfMonth()->format('d/m/Y') }}
        </td>

        <!-- Hành động -->
        <td class="text-center">
            <a href="{{ route('luong.show', $item->id) }}" class="btn btn-info btn-sm p-2 m-2">
                <i class="fa fa-eye"></i>
            </a>
        </td>
    </tr>
@endforeach
