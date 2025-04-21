@extends('layouts.admin')

@section('title')
    Xem Chi Tiết Nhân Viên
@endsection

@section('content')
    <div class="container">
        <h2>Chi Tiết Nhân Viên</h2>
        <div class="card">
            <div class="card-header">
                <strong>{{ $nhanVien->ho_ten }}</strong>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Cột bên trái: Hình ảnh -->
                    <div class="col-md-3 text-center">
                        <h4>Hình ảnh</h4>
                        @if ($nhanVien->hinh_anh)
                            <img src="{{ asset('storage/' . $nhanVien->hinh_anh) }}" alt="Hình ảnh nhân viên"
                                class="img-fluid rounded" style="max-width: 100%; height: auto;">
                        @else
                            <p>Chưa có hình ảnh</p>
                        @endif
                    </div>

                    <!-- Cột giữa: Thông tin nhân viên phần 1 -->
                    <div class="col-md-5">
                        <ul class="list-group">
                            <li class="list-group-item"><strong>Mã nhân viên:</strong> {{ $nhanVien->ma_nhan_vien }}</li>
                            <li class="list-group-item"><strong>Email:</strong> {{ $nhanVien->email }}</li>
                            <li class="list-group-item"><strong>Số điện thoại:</strong> {{ $nhanVien->so_dien_thoai }}</li>
                            <li class="list-group-item"><strong>Chức vụ:</strong> {{ $nhanVien->chucVu->ten_chuc_vu }}</li>
                            <li class="list-group-item"><strong>Giới tính:</strong> {{ ucfirst($nhanVien->gioi_tinh) }}</li>
                        </ul>
                    </div>

                    <!-- Cột bên phải: Thông tin nhân viên phần 2 -->
                    <div class="col-md-4">
                        <ul class="list-group">
                            <li class="list-group-item"><strong>Địa chỉ:</strong>
                                {{ $nhanVien->dia_chi ?? 'Không có thông tin' }}</li>
                            <li class="list-group-item"><strong>Ngày sinh:</strong>
                                {{ $nhanVien->ngay_sinh ? \Carbon\Carbon::parse($nhanVien->ngay_sinh)->format('d/m/Y') : 'Không có thông tin' }}
                            </li>
                            <li class="list-group-item"><strong>Ngày vào làm:</strong>
                                {{ $nhanVien->ngay_vao_lam ? \Carbon\Carbon::parse($nhanVien->ngay_vao_lam)->format('d/m/Y') : 'Không có thông tin' }}
                            </li>

                            <li class="list-group-item"><strong>Ngày tạo:</strong>
                                {{ $nhanVien->created_at->format('d/m/Y') }}</li>
                            <li class="list-group-item"><strong>Ngày cập nhật:</strong>
                                {{ $nhanVien->updated_at->format('d/m/Y') }}</li>
                        </ul>
                    </div>
                </div>

                <!-- Thông tin lương -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <h4>Thông tin lương</h4>
                        @php
                            $luongMoi = $nhanVien->luong()->orderByDesc('ngay_ap_dung')->first(); // hoặc orderByDesc('updated_at')
                        @endphp

                        <ul class="list-group">
                            <li class="list-group-item">
                                <strong>Hình thức:</strong>
                                @if ($luongMoi && $luongMoi->hinh_thuc == 'ca')
                                    Lương theo ca
                                @elseif ($luongMoi)
                                    {{ $luongMoi->hinh_thuc }}
                                @else
                                    <span class="text-danger">Chưa thiết lập</span>
                                @endif
                            </li>

                            <li class="list-group-item">
                                <strong>Mức lương:</strong>
                                @if ($luongMoi)
                                    {{ number_format($luongMoi->muc_luong, 0, ',', '.') }} VNĐ
                                @else
                                    <span class="text-danger">Chưa thiết lập</span>
                                @endif
                            </li>

                            <li class="list-group-item">
                                <strong>Tháng áp dụng lương:</strong>
                                @if ($luongMoi)
                                    {{ \Carbon\Carbon::parse($luongMoi->ngay_ap_dung)->format('m/Y') }}
                                @else
                                    <span class="text-danger">Chưa thiết lập</span>
                                @endif
                            </li>
                        </ul>

                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <a href="{{ route('nhan-vien.edit', $nhanVien->id) }}" class="btn btn-success "
                        style="margin-right: 10px; height: 40px;">Cập nhật</a>

                    <!-- Nút Nghỉ việc / Khôi phục -->
                    @if ($nhanVien->trang_thai === 'dang_lam_viec')
                        <form action="{{ route('nhan-vien.nghi-viec', $nhanVien->id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Bạn có chắc chắn muốn nghỉ việc nhân viên này?')">Ngừng làm
                                việc</button>
                        </form>
                    @else
                        <form action="{{ route('nhan-vien.khoi-phuc', $nhanVien->id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success"
                                onclick="return confirm('Bạn có chắc chắn muốn khôi phục nhân viên này?')">Quay lại làm
                                việc</button>
                        </form>
                    @endif
                    {{-- <form action="{{ route('nhan-vien.destroy', $nhanVien->id) }}" method="POST"
                    style="display:inline;height:20px; margin: 0px 10px">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Bạn có chắc chắn muốn xóa nhân viên này?')">Xóa nhân viên</button>
                </form> --}}
                    <a style="margin-left: 10px;" href="{{ route('nhan-vien.index') }}" class="btn btn-secondary">Trở lại
                        danh
                        sách</a>

                </div>
            </div>
        </div>
    @endsection
