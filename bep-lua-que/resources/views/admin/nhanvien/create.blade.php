@extends('layouts.admin')

@section('title')
    Quản lí nhân viên
@endsection

@section('content')
    <div class="container">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Thêm Mới Nhân Viên</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Quản lí nhân viên</a></li>
                </ol>
            </div>
        </div>

        <form action="{{ route('nhan-vien.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Họ tên</label>
                    <input type="text" name="ho_ten" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Số điện thoại</label>
                    <input type="text" name="so_dien_thoai" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Chức vụ</label>
                    <select name="chuc_vu_id" class="form-control" required>
                        @foreach ($chucVus as $chucVu)
                            <option value="{{ $chucVu->id }}">{{ $chucVu->ten_chuc_vu }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Giới tính</label>
                    <select name="gioi_tinh" class="form-control" required>
                        <option value="nam">Nam</option>
                        <option value="nu">Nữ</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Ngày sinh</label>
                    <input type="date" name="ngay_sinh" class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Ngày vào làm</label>
                    <input type="date" name="ngay_vao_lam" class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Địa chỉ</label>
                    <input type="text" name="dia_chi" class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Hình ảnh</label>
                    <input type="file" name="hinh_anh" class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Mật khẩu</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <!-- Hình thức lương -->
                <div class="col-md-6 mb-3">
                    <label>Hình thức lương</label>
                    <select name="hinh_thuc_luong" class="form-control" id="hinhThucLuong" required>
                        <option value="thang">Lương tháng</option>
                        <option value="ca">Lương theo ca</option>
                        <option value="gio">Lương theo giờ</option>
                    </select>
                </div>

                <!-- Mức lương -->
                <div class="col-md-6 mb-3">
                    <label>Mức lương</label>
                    <div class="input-group">
                        <input type="number" name="muc_luong" class="form-control" id="mucLuong" required>
                        <div class="input-group-append">
                            <span class="input-group-text" id="donViLuong">VNĐ / Tháng</span>
                        </div>
                    </div>
                </div>

            </div>

            <button type="submit" class="btn btn-success">Thêm</button>
        </form>
    </div>

    <script>
        document.getElementById('hinhThucLuong').addEventListener('change', function() {
            var donViLuong = document.getElementById('donViLuong');
            if (this.value === 'ca') {
                donViLuong.textContent = 'VNĐ / Ca';
            } else if (this.value === 'gio') {
                donViLuong.textContent = 'VNĐ / Giờ';
            } else {
                donViLuong.textContent = 'VNĐ / Tháng';
            }
        });
    </script>
@endsection
