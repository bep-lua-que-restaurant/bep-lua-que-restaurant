@php
    $soNgay = 5; // có thể thay đổi thành 30 hoặc số ngày theo tháng
@endphp

<table class="table table-bordered">
    <thead class="table-light">
        <tr>
            <th>Nhân viên</th>
            @for ($i = 1; $i <= $soNgay; $i++)
                <th>{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</th>
            @endfor
            <th>Tổng công</th>
            <th>Lương / công</th>
            <th>Tổng lương</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($bangLuong as $nhanVien)
            <tr>
                <td class="employee-name">{{ $nhanVien['ten'] }}</td>
                @for ($i = 1; $i <= $soNgay; $i++)
                    <td>{{ $nhanVien['chamCong'][$i] ?? 0 }}</td>
                @endfor
                <td class="total">{{ array_sum($nhanVien['chamCong']) }}</td>
                <td class="total">{{ number_format($nhanVien['luongCong']) }}</td>
                <td class="total total-salary">
                    {{ number_format(array_sum($nhanVien['chamCong']) * $nhanVien['luongCong']) }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
