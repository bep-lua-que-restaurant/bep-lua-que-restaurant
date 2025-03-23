@extends('layouts.admin')

@section('title', 'Chi tiết Hóa đơn')

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Chi tiết Hóa đơn {{ $hoaDon->ma_hoa_don }}</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('hoa-don.index') }}">Hóa đơn</a></li>
                    <li class="breadcrumb-item active">Chi tiết</li>
                </ol>
            </div>
        </div>

        <!-- Thông tin Hóa đơn -->
        <div class="card">
            <div class="card-header">
                <h4>Thông tin hóa đơn</h4>
            </div>
            <div class="card-body">
                <p><strong>Mã hóa đơn:</strong> {{ $hoaDon->ma_hoa_don }}</p>
                <p><strong>Khách hàng:</strong> {{ $hoaDon->ten_khach_hang }}</p>
                <p><strong>Số điện thoại:</strong> {{ $hoaDon->so_dien_thoai }}</p>
                <p><strong>Tổng tiền:</strong> {{ number_format($hoaDon->tong_tien, 0, ',', '.') }} VND</p>
                <p><strong>Phương thức thanh toán:</strong>
                    {{ $hoaDon->phuong_thuc_thanh_toan == 'tien_mat'
                        ? 'Tiền mặt'
                        : ($hoaDon->phuong_thuc_thanh_toan == 'the'
                            ? 'Thẻ'
                            : 'Tài khoản') }}
                </p>

                <p><strong>Mô tả:</strong> {{ $hoaDon->mo_ta }}</p>
                <p><strong>Ngày tạo:</strong> {{ $hoaDon->created_at ? $hoaDon->created_at->format('d/m/Y H:i') : 'N/A' }}
                </p>
            </div>
        </div>

        <!-- Bảng chi tiết hóa đơn (Bao gồm cả bàn ăn) -->
        <div class="card mt-4">
            <div class="card-header">
                <h4>Chi tiết hóa đơn</h4>
            </div>
            <div class="card-body">
                @if (!empty($hoaDon->chiTietHoaDons) && $hoaDon->chiTietHoaDons->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Bàn Ăn</th> <!-- Thêm cột Bàn Ăn -->
                                    <th>Món Ăn</th>
                                    <th>Số Lượng</th>
                                    <th>Đơn Giá</th>
                                    <th>Thành Tiền</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($hoaDon->chiTietHoaDons as $chiTiet)
                                    <tr>
                                        {{-- <td>{{ $chiTiet->id }}</td> --}}
                                        <td>
                                            @if (!empty($hoaDon->banAns) && $hoaDon->banAns->isNotEmpty())
                                                {{ $hoaDon->banAns->pluck('ten_ban')->implode(', ') }}
                                            @else
                                                Không có bàn
                                            @endif
                                        </td>
                                        <td>{{ $chiTiet->monAn->ten ?? 'Không có' }}</td>
                                        <td>{{ $chiTiet->so_luong }}</td>
                                        <td>{{ number_format($chiTiet->don_gia, 0, ',', '.') }} đ</td>
                                        <td>{{ number_format($chiTiet->thanh_tien, 0, ',', '.') }} VND</td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p>Không có món ăn nào trong hóa đơn này.</p>
                @endif
            </div>
        </div>

        <!-- Nút quay lại -->
        <a href="{{ route('hoa-don.index') }}" class="btn btn-secondary mt-3">Quay lại</a>
    </div>
@endsection
