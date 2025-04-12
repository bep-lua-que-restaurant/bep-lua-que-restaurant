<table class="table table-bordered table-striped text-nowrap align-middle">
    <thead class="table-primary text-center">
        <tr>
            {{-- <th style="width: 40px;">Chọn</th> --}}
            <th style="min-width: 100px;">Mã bảng lương</th>
            <th style="min-width: 150px;">Tên nhân viên</th>
            <th style="min-width: 80px;">Số công</th>
            <th style="min-width: 120px;">Mức lương</th>
            <th style="min-width: 130px;">Tổng lương</th>
            <th style="min-width: 180px;">Tháng tính lương</th>
            <th style="min-width: 120px;">Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $index => $item)
            <tr>
                {{-- Checkbox chọn dòng --}}
                {{-- <td class="text-center align-middle">
                    <input type="checkbox" class="form-check-input" id="customCheckBox{{ $index }}">
                </td> --}}

                {{-- Mã bảng lương --}}
                <td class="align-middle">
                    <strong>BL{{ $item->id }}</strong>
                </td>

                {{-- Tên nhân viên --}}
                <td class="align-middle">{{ $item->ten_nhan_vien }}</td>

                {{-- Số công --}}
                <td class="align-middle text-center">{{ $item->so_cong ?? '0' }}</td>

                {{-- Mức lương --}}
                <td class="align-middle text-end">
                    {{ number_format($item->muc_luong ?? 0, 0, ',', '.') }} VNĐ
                </td>

                {{-- Tổng lương --}}
                <td class="align-middle text-end">
                    {{ number_format($item->tong_luong ?? 0, 0, ',', '.') }} VNĐ
                </td>

                {{-- Khoảng thời gian tháng --}}
                <td class="align-middle text-center">
                    {{ \Carbon\Carbon::parse($item->thang_nam . '-01')->startOfMonth()->format('d/m/Y') }}
                    -
                    {{ \Carbon\Carbon::parse($item->thang_nam . '-01')->endOfMonth()->format('d/m/Y') }}
                </td>

                {{-- Hành động --}}
                <td class="align-middle text-center">
                    <a href="{{ route('luong.show', $item->id) }}" class="btn btn-sm btn-info me-1" title="Xem chi tiết">
                        <i class="fa fa-eye"></i>
                    </a>
                    <form action="{{ route('luong.destroy', $item->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa không?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
