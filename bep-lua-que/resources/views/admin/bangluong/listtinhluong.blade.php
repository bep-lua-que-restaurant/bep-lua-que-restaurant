<table class="table table-bordered text-center">
    <thead class="thead-light">
        <tr>
            <th>STT</th>
            <th>Tên nhân viên</th>
            <th>Số ca làm</th>
            <th>Số ngày làm</th>
            <th>Hình thức tính lương</th>
            <th>Lương chính (VND)</th>
            <th>Tổng lương (VND)</th>
            {{-- <th>Hành động</th> --}}
        </tr>
    </thead>
    <tbody id="salaryTable">
        @foreach ($nhanViens as $index => $nv)
            @php
                $so_ca_lam = $nv->chamCongs->count();
                $so_ngay_lam_thuc_te = $nv->chamCongs->groupBy('ngay_cham_cong')->count();
                $so_ngay_lam = floor($so_ca_lam / 3); // Mỗi 3 ca tính 1 ngày công
                $so_ca_hien_thi = $so_ca_lam % 3 ?: ($so_ca_lam > 0 ? 3 : 0); // Nếu chia hết cho 3, hiển thị 3 ca

                // Nếu có đúng 4 ca thì sẽ hiển thị 1 ca + 1 ngày
                if ($so_ca_lam == 4) {
                    $so_ca_hien_thi = 1; // Chỉ hiển thị 1 ca
                    // $so_ngay_lam += 1; // Cộng thêm 1 ngày công
                }

                $luongChinh = optional($nv->luong)->muc_luong ?? 0;
                $hinhThuc = optional($nv->luong)->hinh_thuc == 'thang' ? 'Theo tháng' : 'Theo ca';
                $tongLuong = $hinhThuc === 'Theo tháng' ? $luongChinh : $luongChinh * $so_ca_lam;
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    {{ $nv->ho_ten }}<br>
                    <small>{{ $nv->ma_nhan_vien }}</small>
                    <input type="hidden" name="nhan_vien_id[]" value="{{ $nv->id }}">
                </td>
                <td class="so-ca-lam">
                    {{ $so_ca_hien_thi }}
                    <input type="hidden" name="so_ca_lam[]" value="{{ $so_ca_hien_thi }}">
                </td>
                <td class="so-ngay-lam">
                    {{ $so_ngay_lam }}
                    <input type="hidden" name="so_ngay_cong[]" value="{{ $so_ngay_lam }}">
                </td>

                <td class="hinh-thuc">
                    {{ $hinhThuc }}
                </td>
                <td>
                    <input type="number" class="form-control luong-chinh" value="{{ $luongChinh }}" min="0"
                        readonly>
                </td>
                <td class="tong-luong">
                    <input type="hidden" name="tong_luong[]" value="{{ $tongLuong }}">
                    <span>{{ number_format($tongLuong, 0, ',', '.') }}</span>
                </td>
                {{-- <td>
                    <button type="button" class="btn btn-danger btn-sm remove-row">🗑</button>
                </td> --}}
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="6" class="text-right">Tổng cộng:</th>
            <th id="tongCong">
                {{ number_format($nhanViens->sum(fn($nv) => optional($nv->luong)->hinh_thuc == 'thang' ? optional($nv->luong)->muc_luong : optional($nv->luong)->muc_luong * $nv->chamCongs->count()), 0, ',', '.') }}
            </th>
            <th></th>
        </tr>
    </tfoot>
</table>
