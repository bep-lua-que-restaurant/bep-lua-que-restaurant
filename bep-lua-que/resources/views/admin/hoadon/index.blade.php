@extends('layouts.admin')

@section('title', 'Hóa đơn')

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Hi, welcome back!</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Hóa đơn</a></li>
                </ol>
            </div>
        </div>

        <!-- Form tìm kiếm -->
        <div class="row">
            <div class="col-lg-12">
                <form method="GET" action="{{ route('hoa-don.index') }}">
                    <div class="input-group mb-3">
                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm hóa đơn..."
                            value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Danh sách hóa đơn -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Danh sách hóa đơn</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-responsive-md">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Mã Hóa Đơn</th>
                                        <th>Khách Hàng</th>
                                        <th>Số điện thoại</th>
                                        <th>Tổng Tiền</th>
                                        <th>Phương Thức Thanh Toán</th>
                                        <th>Ngày Tạo</th>
                                        <th>Hành động </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($hoa_don as $hoa_dons)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $hoa_dons->ma_hoa_don }}</td>
                                            <td>{{ $hoa_dons->ho_ten }}</td>
                                            <td>{{ $hoa_dons->so_dien_thoai }}</td>
                                            <td>{{ number_format($hoa_dons->tong_tien, 0, ',', '.') }} VNĐ</td>
                                            @php
                                                $paymentMethods = [
                                                    'tien_mat' => 'Tiền mặt',
                                                    'the' => 'Thẻ',
                                                    'tai_khoan' => 'Tài khoản',
                                                ];
                                            @endphp
                                            <td>{{ $paymentMethods[$hoa_dons->phuong_thuc_thanh_toan] ?? 'Không xác định' }}
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($hoa_dons->ngay_tao)->format('d/m/Y H:i') }}</td>

                                            <td>
                                                <a href="{{ route('hoa-don.show', $hoa_dons->id) }}"
                                                    class="btn btn-primary btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <button class="btn btn-success btn-sm"
                                                    onclick="printInvoice({{ $hoa_dons->id }})">
                                                    <i class="fas fa-print"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $hoa_don->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($hoa_don as $hoa_dons)
        <!-- Nội dung hóa đơn cần in -->
        <div id="print-area-{{ $hoa_dons->id }}"
            style="display: none; font-family: Arial, sans-serif; width: 300px; padding: 20px; border: 1px solid #000;">
            <div style="text-align: center;">
                <h2 style="margin: 0;">🍽️ Nhà Hàng Bếp lửa quê</h2>
                {{-- <p style="margin: 5px 0;">123 Đường ABC, Quận X, TP.HCM</p>
                <p style="margin: 5px 0;">📞 0987 654 321</p> --}}
                <hr>
                <h3 style="margin: 10px 0;">HÓA ĐƠN THANH TOÁN</h3>
                <p>Mã hóa đơn: <strong>{{ $hoa_dons->ma_hoa_don }}</strong></p>
                <p>Ngày: <strong>{{ \Carbon\Carbon::parse($hoa_dons->ngay_tao)->format('d/m/Y H:i') }}</strong></p>
            </div>

            <hr>

            <p><strong>Khách hàng:</strong> {{ $hoa_dons->ho_ten }}</p>
            <p><strong>Số điện thoại:</strong> {{ $hoa_dons->so_dien_thoai }}</p>

            <hr>

            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr>
                        <th style="border-bottom: 1px solid #000;">Món ăn</th>
                        <th style="border-bottom: 1px solid #000; text-align: center;">SL</th>
                        <th style="border-bottom: 1px solid #000; text-align: right;">Giá</th>
                        <th style="border-bottom: 1px solid #000; text-align: right;">Thành tiền</th>

                    </tr>
                </thead>
                <tbody>
                    @if ($hoa_dons->chiTietHoaDons && count($hoa_dons->chiTietHoaDons) > 0)
                        @foreach ($hoa_dons->chiTietHoaDons as $chiTiet)
                            <tr>
                                <td>{{ $chiTiet->ten ?? 'Không có' }}</td>
                                <td style="text-align: center;">{{ $chiTiet->so_luong ?? 0 }}</td>
                                <td style="text-align: right;">
                                    {{ number_format($chiTiet->gia ?? 0, 0, ',', '.') }} VNĐ
                                </td>
                                <td style="text-align: right;">
                                    {{ number_format($chiTiet->thanh_tien ?? 0, 0, ',', '.') }} VNĐ
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="text-center">Không có dữ liệu</td>
                        </tr>
                    @endif


                </tbody>
            </table>

            <hr>

            <p style="text-align: right; font-size: 16px;">
                <strong>Tổng cộng: {{ number_format($hoa_dons->tong_tien, 0, ',', '.') }} VNĐ</strong>
            </p>

            <p style="text-align: center; font-style: italic;">Cảm ơn quý khách! Hẹn gặp lại! 🎉</p>
        </div>
    @endforeach

@endsection

<!-- Script in hóa đơn -->
<script>
    function printInvoice(id) {
        var content = document.getElementById('print-area-' + id).innerHTML;
        var myWindow = window.open('', '', 'width=800,height=1000'); // Tăng kích thước cửa sổ in
        myWindow.document.write('<html><head><title>In Hóa Đơn</title>');
        myWindow.document.write('<style>');
        myWindow.document.write('body { font-size: 18px; padding: 20px; }'); // Tăng kích thước chữ và thêm padding
        myWindow.document.write('</style></head><body>');
        myWindow.document.write(content);
        myWindow.document.write('</body></html>');
        myWindow.document.close();
        myWindow.print();
    }
</script>
