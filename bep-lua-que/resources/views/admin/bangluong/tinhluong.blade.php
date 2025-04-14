@extends('layouts.admin') @section('content')
    <div class="container">
        <h2>Tính Lương Nhân Viên</h2>
        <form method="GET" class="d-flex align-items-center gap-2 mb-3">
            <label for="thang" class="fw-bold">Chọn tháng:</label>
            <input type="month" name="thang" id="thang" class="form-control w-auto"
                value="{{ request('thang', now()->format('Y-m')) }}" onchange="this.form.submit()" />
        </form>

        <form action="{{ route('luong.store') }}" method="POST">
            @csrf
            <input type="hidden" name="thang_nam" value="{{ request('thang', now()->format('Y-m')) }}" />
            <table class="table table-bordered text-center">
                <thead class="thead-light">
                    <tr>
                        <th>STT</th>
                        <th>Tên nhân viên</th>
                        <th>Số ca làm</th>
                        {{--
                    <th>Số ngày làm</th>
                    --}}
                        <th>Hình thức tính lương</th>
                        <th>Lương chính (VND)</th>
                        <th>Tổng lương (VND)</th>
                        {{--
                    <th>Hành động</th>
                    --}}
                    </tr>
                </thead>
                <tbody id="salaryTable">
                    @foreach ($nhanViens as $index => $nv)
                        @php

                            $luong = $nv->luong_cu ?? 0; //
                            $so_ca_lam = $nv->chamCongs->count();
                        @endphp

                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                {{ $nv->ho_ten }}<br />
                                <small>{{ $nv->ma_nhan_vien }}</small>
                                <input type="hidden" name="nhan_vien_id[]" value="{{ $nv->id }}" />
                            </td>
                            <td class="so-ca-lam">
                                {{ $so_ca_lam }}
                                <input type="hidden" name="so_ca_lam[]" value="{{ $so_ca_lam }}" />
                            </td>
                            <td class="hinh-thuc">Theo ca</td>
                            <td>
                                <input type="number" class="form-control luong-chinh" value="{{ intval($luong) }}"
                                    min="0" readonly style="text-align: center" />
                            </td>
                            <td class="tong-luong">
                                @php
                                    $tongLuong = $luong * $so_ca_lam;
                                @endphp
                                <input type="hidden" name="tong_luong[]" value="{{ $tongLuong }}" />
                                <span>{{ number_format($tongLuong, 0, ',', '.') }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <tfoot>
                            <tr>
                                <th colspan="5" class="text-right">Tổng cộng:</th>
                                <th id="tongCong">
                                    {{ number_format(
                                        $nhanViens->sum(function ($nv) {
                                            return $nv->hinh_thuc == 'thang' ? $nv->luong_cu : $nv->luong_cu * $nv->chamCongs->count();
                                        }),
                                        0,
                                        ',',
                                        '.',
                                    ) }}
                                </th>
                            </tr>
                        </tfoot>
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
            <button type="submit" class="btn btn-success mt-3" onclick="return confirmChotLuong(event)">
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
            document.querySelectorAll("#salaryTable tr").forEach((row) => {
                let luongChinh =
                    parseFloat(row.querySelector(".luong-chinh").value) || 0;
                let soCaLam =
                    parseFloat(row.querySelector(".so-ca-lam").textContent) ||
                    0;
                let soNgayLam =
                    parseFloat(row.querySelector(".so-ngay-lam").textContent) ||
                    0;
                let hinhThuc = row
                    .querySelector(".hinh-thuc")
                    .textContent.trim();

                let total = 0;

                // Tính lương theo hình thức
                // if (hinhThuc === 'Theo tháng' && soNgayLam > 0) {
                //     total = (luongChinh / 26) * soNgayLam; // Chia trung bình cho 26 ngày công chuẩn
                // } else if (hinhThuc === 'Theo ca' && soCaLam > 0) {
                //     total = luongChinh * soCaLam;
                // }

                // Cập nhật giá trị vào input và hiển thị
                row.querySelector('input[name="tong_luong[]"]').value = total;
                row.querySelector(".tong-luong span").textContent =
                    total.toLocaleString("vi-VN");
                totalSalary += total;
            });

            document.getElementById("tongCong").textContent =
                totalSalary.toLocaleString("vi-VN");
        }

        calculateTotal();

        document.querySelectorAll(".luong-chinh").forEach((input) => {
            input.addEventListener("input", calculateTotal);
        });

        document.querySelectorAll(".remove-row").forEach((btn) => {
            btn.addEventListener("click", function() {
                this.closest("tr").remove();
                calculateTotal();
            });
        });
    });

    function confirmChotLuong(event) {
        var confirmAction = confirm("Bạn có muốn chốt lương tháng này không?");
        if (confirmAction) {
            // Nếu người dùng xác nhận, cho phép gửi form
            return true;
        } else {
            // Nếu người dùng từ chối, ngừng hành động mặc định (ngừng gửi form)
            event.preventDefault();
            alert("Chốt lương bị hủy.");
            return false;
        }
    }
</script>
