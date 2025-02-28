@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2>Tính Lương Nhân Viên</h2>
        <form action="{{ route('luong.store') }}" method="POST">
            @csrf
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
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody id="salaryTable">
                    @foreach ($nhanViens as $index => $nv)
                        @php
                            $so_ca_lam = $nv->chamCongs->count();
                            $so_ngay_lam_thuc_te = $nv->chamCongs->groupBy('ngay_cham_cong')->count();
                            $so_ngay_lam = floor($so_ca_lam / 3);
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
                            <td class="so-ngay-lam">
                                {{ $so_ngay_lam }}
                                <input type="hidden" name="so_ngay_cong[]" value="{{ $so_ngay_lam }}">
                            </td>
                            <td class="hinh-thuc">
                                {{ $hinhThuc }}
                            </td>
                            <td>
                                <input type="number" class="form-control luong-chinh" value="{{ $luongChinh }}"
                                    min="0" readonly>
                            </td>
                            <td class="tong-luong">
                                <input type="hidden" name="tong_luong[]" value="{{ $tongLuong }}">
                                <span>{{ number_format($tongLuong, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm remove-row">🗑</button>
                            </td>
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
            <a class="btn btn-primary mt-3 mr-3" href="{{ route('luong.index') }}">Quay lại</a>
            <button type="submit" class="btn btn-success mt-3">Chốt Lương</button>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function calculateTotal() {
                let totalSalary = 0;
                document.querySelectorAll('#salaryTable tr').forEach(row => {
                    let luongChinh = parseFloat(row.querySelector('.luong-chinh').value) || 0;
                    let soCaLam = parseFloat(row.querySelector('.so-ca-lam').textContent) || 0;
                    let hinhThuc = row.querySelector('.hinh-thuc').textContent.trim();
                    let total = hinhThuc === 'Theo tháng' ? luongChinh : (luongChinh * soCaLam);

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
@endsection
