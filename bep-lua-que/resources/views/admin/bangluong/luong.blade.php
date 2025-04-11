{{-- @extends('layouts.admin')

@section('content')
    <div class="container">
        <h2 class="mb-3">Bảng Lương Nhân Viên</h2>
        <div class="d-flex justify-content-between mb-3">
            <a href="{{ route('luong.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tính Lương</a>
            <form action="{{ route('luong.import') }}" method="POST" enctype="multipart/form-data"
                class="d-inline-flex align-items-center">
                @csrf
                <input type="file" name="file" class="form-control form-control-sm me-2">
                <button type="submit" class="btn btn-warning"><i class="fas fa-file-import"></i> Nhập Excel</button>
            </form>
            <a href="{{ route('luong.export') }}" class="btn btn-success"><i class="fas fa-file-export"></i> Xuất Excel</a>
        </div>

        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nhân viên</th>
                    <th>Hình thức</th>
                    <th>Mức lương</th>
                    <th>Giờ/Ngày làm</th>
                    <th>Tổng lương</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($luongs as $luong)
                    <tr>
                        <td><strong>{{ $luong->id }}</strong></td>
                        <td>{{ $luong->nhanVien->ten }}</td>
                        <td>{{ ucfirst($luong->hinh_thuc) }}</td>
                        <td>{{ number_format($luong->muc_luong) }} VND</td>
                        <td>
                            @if ($luong->hinh_thuc == 'gio')
                                {{ $luong->so_gio_lam }} giờ
                            @elseif($luong->hinh_thuc == 'ngay')
                                {{ $luong->so_ngay_lam }} ngày
                            @else
                                -
                            @endif
                        </td>
                        <td><strong>{{ number_format($luong->tong_luong) }} VND</strong></td>
                        <td class="text-center">
                            <a href="#" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                            <a href="#" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                            <a href="#" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection --}}
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Bảng Lương Nhân Viên</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 14px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        .employee-name {
            text-align: left;
            font-weight: bold;
            background-color: #f9f9f9;
        }

        .total {
            font-weight: bold;
            background-color: #f1f1f1;
        }

        .total-salary {
            color: green;
        }
    </style>
</head>

<body>

    <h2>BẢNG LƯƠNG THÁNG 4/2025</h2>

    <table>
        <thead>
            <tr>
                <th>Nhân viên</th>
                <th>01</th>
                <th>02</th>
                <th>03</th>
                <th>04</th>
                <th>05</th>
                <th>Tổng công</th>
                <th>Lương / công</th>
                <th>Tổng lương</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="employee-name">Nguyễn Văn A</td>
                <td>1</td>
                <td>1</td>
                <td>1</td>
                <td>0</td>
                <td>1</td>
                <td class="total">4</td>
                <td class="total">300,000</td>
                <td class="total total-salary">1,200,000</td>
            </tr>
            <tr>
                <td class="employee-name">Trần Thị B</td>
                <td>1</td>
                <td>0</td>
                <td>1</td>
                <td>1</td>
                <td>1</td>
                <td class="total">4</td>
                <td class="total">280,000</td>
                <td class="total total-salary">1,120,000</td>
            </tr>
        </tbody>
    </table>

</body>

</html>
