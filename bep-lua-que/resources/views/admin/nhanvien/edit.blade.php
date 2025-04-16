@extends('layouts.admin')

@section('title')
    Cập nhật Nhân Viên
@endsection

@section('content')
    <div class="container">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Cập nhật Nhân Viên</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Cập nhật nhân viên</a></li>
                </ol>
            </div>
        </div>

        <form action="{{ route('nhan-vien.update', $nhanVien->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Họ tên</label>
                    <input type="text" name="ho_ten" class="form-control" value="{{ $nhanVien->ho_ten }}">
                    @error('ho_ten')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $nhanVien->email }}">
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label>Số điện thoại</label>
                    <input type="text" name="so_dien_thoai" class="form-control" value="{{ $nhanVien->so_dien_thoai }}">
                    @error('so_dien_thoai')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label>Chức vụ</label>
                    <select name="chuc_vu_id" class="form-control">
                        @foreach ($chucVus as $chucVu)
                            <option value="{{ $chucVu->id }}"
                                {{ $chucVu->id == $nhanVien->chuc_vu_id ? 'selected' : '' }}>
                                {{ $chucVu->ten_chuc_vu }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Giới tính</label>
                    <select name="gioi_tinh" class="form-control">
                        <option value="nam" {{ $nhanVien->gioi_tinh == 'nam' ? 'selected' : '' }}>Nam</option>
                        <option value="nu" {{ $nhanVien->gioi_tinh == 'nu' ? 'selected' : '' }}>Nữ</option>
                    </select>
                    @error('gioi_tinh')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label>Ngày sinh</label>
                    <input type="date" name="ngay_sinh" class="form-control"
                        value="{{ old('ngay_sinh', optional($nhanVien->ngay_sinh)->format('Y-m-d')) }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Ngày vào làm</label>
                    <input type="date" name="ngay_vao_lam" class="form-control"
                        value="{{ $nhanVien->ngay_vao_lam ? \Carbon\Carbon::parse($nhanVien->ngay_vao_lam)->format('Y-m-d') : '' }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Địa chỉ</label>
                    <input type="text" name="dia_chi" class="form-control"
                        value="{{ old('dia_chi', $nhanVien->dia_chi) }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Hình ảnh</label>
                    <input type="file" name="hinh_anh" class="form-control">
                    @if ($nhanVien->hinh_anh)
                        <img src="{{ asset('storage/' . $nhanVien->hinh_anh) }}" alt="Hình ảnh nhân viên"
                            style="width: 100px; margin-top: 10px;">
                    @endif
                </div>

                <div class="col-md-6 mb-3">
                    <label>Mật khẩu (nếu thay đổi)</label>
                    <input type="password" name="password" class="form-control">
                    @error('password')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label>Hình thức lương</label>
                    <select name="hinh_thuc_luong" class="form-control" id="hinhThucLuong">
                        <option value="ca"
                            {{ optional($nhanVien->luong->first())->hinh_thuc == 'ca' ? 'selected' : '' }}>
                            Lương theo ca
                        </option>

                    </select>
                    @error('hinh_thuc_luong')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                @php
                    $luongGanNhat = $nhanVien->luong->sortByDesc('ngay_ap_dung')->first();
                @endphp

                <div class="col-md-6 mb-3">
                    <label>Mức lương</label>
                    <div class="input-group">
                        <input type="number" name="muc_luong" class="form-control" id="mucLuong"
                            value="{{ old('muc_luong', $luongGanNhat?->muc_luong) }}" required>
                        <div class="input-group-append">
                            <span class="input-group-text" id="donViLuong">VNĐ / Ca</span>
                        </div>
                    </div>

                    @error('muc_luong')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label>Ngày áp dụng</label>
                    <input type="date" id="ngay_ap_dung" name="ngay_ap_dung" class="form-control"
                        value="{{ old('ngay_ap_dung', $luongGanNhat?->ngay_ap_dung ? \Carbon\Carbon::parse($luongGanNhat->ngay_ap_dung)->toDateString() : '') }}"
                        $existingValue>

                    @error('ngay_ap_dung')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>



                <div class="col-12 mb-3">
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                    <a href="{{ route('nhan-vien.index') }}" class="btn btn-secondary">Trở lại danh sách</a>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var hinhThucLuong = document.getElementById('hinhThucLuong');
            var donViLuong = document.getElementById('donViLuong');

            function updateDonViLuong() {
                if (hinhThucLuong.value === 'ca') {
                    donViLuong.textContent = 'VNĐ / Ca';
                } else if (hinhThucLuong.value === 'gio') {
                    donViLuong.textContent = 'VNĐ / Giờ';
                } else {
                    donViLuong.textContent = 'VNĐ / Tháng';
                }
            }

            hinhThucLuong.addEventListener('change', updateDonViLuong);
            updateDonViLuong();
        });
        const input = document.getElementById('ngay_ap_dung');

        input.addEventListener('change', function() {
            const date = new Date(this.value);
            if (date.getDate() !== 1) {
                alert('Vui lòng chọn ngày đầu tiên của tháng!');
                this.value = ''; // Xóa giá trị sai
            }
        });
    </script>
@endsection
