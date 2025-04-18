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
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
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
                <p><strong>Mã giảm giá:</strong> {{ $hoaDon->code ?: 'Không có' }}</p>

                <p><strong>Phương thức thanh toán:</strong>
                    {{ $hoaDon->phuong_thuc_thanh_toan == 'tien_mat'
                        ? 'Tiền mặt'
                        : ($hoaDon->phuong_thuc_thanh_toan == 'the'
                            ? 'Thẻ'
                            : 'Tài khoản') }}
                </p>

                <p><strong>Mô tả:</strong> {{ $hoaDon->mo_ta ?: 'Không có' }}</p>
                <p><strong>Tổng tiền trước khi giảm:</strong>
                    {{ number_format($hoaDon->tong_tien_truoc_khi_giam, 0, ',', '.') }}VND</p>

                <p><strong>Tiền thanh toán: </strong> {{ number_format($hoaDon->tong_tien, 0, ',', '.') }} VND</p>
                <p><strong>Ngày tạo:</strong>
                    {{ $hoaDon->created_at ? $hoaDon->created_at->format('d/m/Y H:i') : 'Không có' }}
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
                                        <td>{{ number_format($chiTiet->don_gia, 0, ',', '.') }} VND</td>
                                        <td>{{ number_format($chiTiet->so_luong * $chiTiet->don_gia, 0, ',', '.') }} VND
                                        </td>

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

<!-- Ảnh hóa đơn -->
<div class="card mt-4">
    <div class="card-header">
        <h4>Ảnh hóa đơn</h4>
    </div>
    <div class="card-body">
        @if (!empty($hoaDon->billImages) && $hoaDon->billImages->isNotEmpty())
            <div class="row">
                @foreach ($hoaDon->billImages as $image)
                    <div class="col-md-4 mb-3">
                        <a href="{{ asset('storage/' . $image->image_path) }}" 
                           data-lightbox="bill-images" 
                           data-title="Ảnh hóa đơn {{ $hoaDon->ma_hoa_don }}">
                            <img src="{{ asset('storage/' . $image->image_path) }}" 
                                 alt="Ảnh hóa đơn {{ $hoaDon->ma_hoa_don }}" 
                                 class="img-fluid rounded" 
                                 style="max-height: 200px; object-fit: cover;">
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <p>Hóa đơn này chưa tải ảnh bill lên.</p>
        @endif
    </div>
</div>

        <!-- Nút quay lại -->
        <a href="{{ route('hoa-don.index') }}" class="btn btn-secondary mt-3">Quay lại</a>
    </div>

        <!-- Lightbox2 CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" integrity="sha512-ZKX+BvQihRJPA8CROKBhDNvoc2aDMOdAlcm7TUQY+35XYtrd3yh95QOOhsPDQY9QnKE0Wqag9y38OIgEvb88cA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
        <!-- Lightbox2 JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js" integrity="sha512-k2GFCTbp9rQU412BStrcD/rlwv1PYec9SNrkbQlo6RZCf75l6KcC3Uwjo9o6v5Z0QmYwXIOuQue4ElaaYwXwkfg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection
