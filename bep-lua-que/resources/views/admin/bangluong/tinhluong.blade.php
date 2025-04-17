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
                        <th>Lương chính/Ca (VND)</th>
                        <th>Thưởng/Phạt</th>
                        <th>Lý do</th>
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
                            <td>
                                <input name="thuong_phat[]" type="number"
                                    class="form-control input-same-size thuong-phat-input" />
                            </td>
                            <td>
                                <input name="ly_do[]" type="text" class="form-control input-same-size" />
                            </td>
                            <td class="tong-luong">
                                @php
                                    $tongLuong = $luong * $so_ca_lam;
                                @endphp
                                <input type="hidden" name="tong_luong[]" value="{{ $tongLuong }}"
                                    class="tong-luong-hidden" />
                                <span class="tong-luong-span">{{ number_format($tongLuong, 0, ',', '.') }}</span>

                                <!-- Dùng hidden để lưu dữ liệu gốc -->
                                <input type="hidden" class="luong-co-ban" value="{{ $luong }}" />
                                <input type="hidden" class="so-ca-lam" value="{{ $so_ca_lam }}" />
                            </td>



                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <tfoot>
                            <tr>
                                <th colspan="7" class="text-right">Tổng cộng:</th>
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
    {{ $nhanViens->links('pagination::bootstrap-5') }}
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
        function calculateTotal() {
            let totalSalary = 0;
            document.querySelectorAll("#salaryTable tr").forEach((row) => {
                let luongChinh = parseFloat(row.querySelector(".luong-chinh").value) || 0;
                let soCaLam = parseFloat(row.querySelector(".so-ca-lam").textContent) || 0;
                let thuongPhat = parseFloat(row.querySelector(".thuong-phat-input").value) ||
                    0; // Lấy giá trị thưởng/phạt

                let total = (luongChinh * soCaLam) + thuongPhat; // Tính tổng lương với thưởng/phạt

                // Cập nhật giá trị vào input và hiển thị
                row.querySelector('input[name="tong_luong[]"]').value = total;
                row.querySelector(".tong-luong span").textContent =
                    total.toLocaleString("vi-VN");
                totalSalary += total;
            });

            document.getElementById("tongCong").textContent =
                totalSalary.toLocaleString("vi-VN");
        }

        // Gọi hàm tính tổng khi trang được tải
        calculateTotal();

        // Thêm sự kiện khi người dùng thay đổi lương chính hoặc thưởng/phạt
        document.querySelectorAll(".luong-chinh").forEach((input) => {
            input.addEventListener("input", calculateTotal);
        });

        document.querySelectorAll('.thuong-phat-input').forEach(function(input) {
            input.addEventListener('input', function() {
                const row = input.closest('tr'); // tìm dòng hiện tại

                // Cập nhật lại tổng lương khi thưởng/phạt thay đổi
                const luong = parseFloat(row.querySelector('.luong-co-ban').value) || 0;
                const soCa = parseFloat(row.querySelector('.so-ca-lam').value) || 0;
                const thuongPhat = parseFloat(input.value) || 0;

                const tongLuong = (luong * soCa) + thuongPhat;

                // Cập nhật giá trị tổng lương vào cả input hidden và span hiển thị
                row.querySelector('.tong-luong-hidden').value = tongLuong;
                row.querySelector('.tong-luong-span').textContent = tongLuong.toLocaleString(
                    'vi-VN');

                // Tính lại tổng lương toàn bộ bảng
                calculateTotal();
            });
        });



    });

    function confirmChotLuong(event) {
        if (!confirm('Bạn có chắc chắn muốn chốt lương?')) {
            event.preventDefault(); // Ngừng gửi form nếu người dùng không xác nhận
        }
    }
</script>
