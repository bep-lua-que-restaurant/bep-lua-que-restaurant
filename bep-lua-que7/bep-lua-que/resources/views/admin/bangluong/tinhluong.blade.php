@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2>Tính Lương Nhân Viên</h2>
        <form method="GET" class="d-flex align-items-center gap-2 mb-3">
            <label for="thang" class="fw-bold">Chọn tháng:</label>
            <input type="month" name="thang" id="thang" class="form-control w-auto"
                value="{{ request('thang', now()->format('Y-m')) }}" onchange="this.form.submit()">
        </form>



        <form action="{{ route('luong.store') }}" method="POST">
            @csrf
            <input type="hidden" name="thang_nam" value="{{ request('thang', now()->format('Y-m')) }}">
            <table class="table table-bordered text-center">
                <thead class="thead-light">
                    <tr>
                        <th>STT</th>
                        <th>Tên nhân viên</th>
                        <th>Số ca làm</th>
                        {{-- <th>Số ngày làm</th> --}}
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
                            // $so_ngay_lam = floor($so_ca_lam / 3); // Mỗi 3 ca tính 1 ngày công
                            // $so_ca_hien_thi = $so_ca_lam % 3 ?: ($so_ca_lam > 0 ? 3 : 0); // Nếu chia hết cho 3, hiển thị 3 ca

                            // // Nếu có đúng 4 ca thì sẽ hiển thị 1 ca + 1 ngày
                            // if ($so_ca_lam == 4) {
                            //     $so_ca_hien_thi = 1; // Chỉ hiển thị 1 ca
                            //     // $so_ngay_lam += 1; // Cộng thêm 1 ngày công
                            // }
                            // $so_ca_lam;
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
                                {{ $so_ca_lam }}
                                <input type="hidden" name="so_ca_lam[]" value="{{ $so_ca_lam }}">
                            </td>
                            {{-- <td class="so-ngay-lam">
                                {{ $so_ngay_lam }}
                                <input type="hidden" name="so_ngay_cong[]" value="{{ $so_ngay_lam }}">
                            </td> --}}

                            <td class="hinh-thuc">
                                {{ $hinhThuc }}
                            </td>
                            <td>
                                <input type="number" class="form-control luong-chinh" value="{{ intval($luongChinh) }}"
                                    min="0" readonly style="text-align: center;">
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
                        <th colspan="5" class="text-right">Tổng cộng:</th>
                        <th id="tongCong">
                            {{ number_format($nhanViens->sum(fn($nv) => optional($nv->luong)->hinh_thuc == 'thang' ? optional($nv->luong)->muc_luong : optional($nv->luong)->muc_luong * $nv->chamCongs->count()), 0, ',', '.') }}
                        </th>

                    </tr>
                </tfoot>
            </table>
            <a class="btn btn-primary mt-3 mr-3" href="{{ route('luong.index') }}">Quay lại</a>
            @php
                $thangHienTai = now()->format('Y-m');
                $daChotLuong = \App\Models\Luong::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->exists();
            @endphp

            <button type="submit" class="btn btn-success mt-3">
                Chốt Lương
            </button>

            {{-- @if ($daChotLuong)
                <p class="text-danger mt-2">Lương tháng này đã được chốt!</p>
            @endif --}}

        </form>
    </div>
@endsection
<script>
    document.addEventListener("DOMContentLoaded", function() {
        function calculateTotal() {
            let totalSalary = 0;
            document.querySelectorAll('#salaryTable tr').forEach(row => {
                let luongChinh = parseFloat(row.querySelector('.luong-chinh').value) || 0;
                let soCaLam = parseFloat(row.querySelector('.so-ca-lam').textContent) || 0;
                let soNgayLam = parseFloat(row.querySelector('.so-ngay-lam').textContent) || 0;
                let hinhThuc = row.querySelector('.hinh-thuc').textContent.trim();

                let total = 0;

                // Tính lương theo hình thức
                // if (hinhThuc === 'Theo tháng' && soNgayLam > 0) {
                //     total = (luongChinh / 26) * soNgayLam; // Chia trung bình cho 26 ngày công chuẩn
                // } else if (hinhThuc === 'Theo ca' && soCaLam > 0) {
                //     total = luongChinh * soCaLam;
                // }

                // Cập nhật giá trị vào input và hiển thị
                row.querySelector('input[name="tong_luong[]"]').value = total;
                row.querySelector('.tong-luong span').textContent = total.toLocaleString('vi-VN');
                totalSalary += total;
            });

            document.getElementById('tongCong').textContent = totalSalary.toLocaleString('vi-VN');
        }

        calculateTotal();

        document.querySelectorAll('.luong-chinh').forEach(input => {
            input.addEventListener('input', calculateTotal);
        });

        document.querySelectorAll('.remove-row').forEach(btn => {
            btn.addEventListener('click', function() {
                this.closest('tr').remove();
                calculateTotal();
            });
        });
    });
</script>
