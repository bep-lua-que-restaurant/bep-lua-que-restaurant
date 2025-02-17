@extends('layouts.admin')


@section('title', 'Thêm Phiếu Nhập Kho')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Thêm Phiếu Nhập Kho</h4>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('phieu-nhap-kho.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Mã phiếu nhập (Tự động) -->
                            <div class="mb-3">
                                <label for="ma_phieu_nhap" class="form-label">Mã Phiếu Nhập</label>
                                <input type="text" name="ma_phieu_nhap" id="ma_phieu_nhap" class="form-control"
                                    value="{{ old('ma_phieu_nhap', 'PNK' . now()->timestamp) }}" readonly>
                            </div>

                            <!-- Nhân viên nhập -->
                            <div class="mb-3">
                                <label for="nhan_vien_id" class="form-label">Nhân Viên Nhập</label>
                                <select name="nhan_vien_id" id="nhan_vien_id" class="form-control" required>
                                    <option value="">Chọn nhân viên nhập</option>
                                    @foreach ($nhanViens as $nhanVien)
                                        <option value="{{ $nhanVien->id }}"
                                            {{ old('nhan_vien_id') == $nhanVien->id ? 'selected' : '' }}>
                                            {{ $nhanVien->ho_ten }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Nhà cung cấp -->
                            <div class="mb-3">
                                <label for="nha_cung_cap_id" class="form-label">Nhà Cung Cấp</label>
                                <select name="nha_cung_cap_id" id="nha_cung_cap_id" class="form-control" required>
                                    <option value="">Chọn nhà cung cấp</option>
                                    @foreach ($nhaCungCaps as $nhaCungCap)
                                        <option value="{{ $nhaCungCap->id }}"
                                            {{ old('nha_cung_cap_id') == $nhaCungCap->id ? 'selected' : '' }}>
                                            {{ $nhaCungCap->ten_nha_cung_cap }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Ngày nhập -->
                            <div class="mb-3">
                                <label for="ngay_nhap" class="form-label">Ngày Nhập</label>
                                <input type="datetime-local" name="ngay_nhap" id="ngay_nhap" class="form-control"
                                    value="{{ old('ngay_nhap') }}" required>
                            </div>

                            <!-- Ghi chú -->
                            <div class="mb-3">
                                <label for="ghi_chu" class="form-label">Ghi Chú</label>
                                <textarea name="ghi_chu" id="ghi_chu" class="form-control">{{ old('ghi_chu') }}</textarea>
                            </div>

                            <!-- Danh sách nguyên liệu -->
                            <div id="nguyen-lieu-container">
                                <div class="nguyen-lieu-item mb-3 border p-3 rounded">
                                    <h5 class="text-primary">Nguyên Liệu 1</h5>
                                    <!-- Mã nguyên liệu (Tự động) -->
                                    <div class="mb-3">
                                        <label for="ma_nguyen_lieu" class="form-label">Mã Nguyên Liệu</label>
                                        <input type="text" name="nguyen_lieu[0][ma_nguyen_lieu]" class="form-control"
                                            value="{{ 'NL' . strtoupper(Str::random(8)) }}" readonly>
                                    </div>
                                    <!-- Tên nguyên liệu -->
                                    <div class="mb-3">
                                        <label for="ten_nguyen_lieu" class="form-label">Tên Nguyên Liệu</label>
                                        <input type="text" name="nguyen_lieu[0][ten_nguyen_lieu]" class="form-control"
                                            required>
                                    </div>

                                    <!-- Loại nguyên liệu -->
                                    <div class="mb-3">
                                        <label for="loai_nguyen_lieu_id" class="form-label">Loại Nguyên Liệu</label>
                                        <select name="nguyen_lieu[0][loai_nguyen_lieu_id]" class="form-control" required>
                                            <option value="">Chọn loại nguyên liệu</option>
                                            @foreach ($loaiNguyenLieus as $loai)
                                                <option value="{{ $loai->id }}">{{ $loai->ten_loai }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Đơn vị tính -->
                                    <div class="mb-3">
                                        <label for="don_vi_tinh" class="form-label">Đơn Vị Tính</label>
                                        <input type="text" name="nguyen_lieu[0][don_vi_tinh]" class="form-control"
                                            required>
                                    </div>

                                    <!-- Số lượng -->
                                    <div class="mb-3">
                                        <label for="so_luong" class="form-label">Số Lượng</label>
                                        <input type="number" name="nguyen_lieu[0][so_luong]" class="form-control"
                                            min="1" required>
                                    </div>

                                    <!-- Giá nhập -->
                                    <div class="mb-3">
                                        <label for="don_gia" class="form-label">Giá Nhập</label>
                                        <input type="number" step="0.01" name="nguyen_lieu[0][don_gia]"
                                            class="form-control" required>
                                    </div>
                                    <!-- Hạn sử dụng -->
                                    <div class="mb-3">
                                        <label for="han_su_dung" class="form-label">Hạn Sử Dụng</label>
                                        <input type="date" name="nguyen_lieu[${nguyenLieuIndex}][han_su_dung]"
                                            class="form-control" required>
                                    </div>
                                    <!-- Hình ảnh -->
                                    <div class="mb-3">
                                        <label for="hinh_anh_${uniqueID}" class="form-label">Hình Ảnh Nguyên Liệu</label>
                                        <input type="file" name="nguyen_lieu[${nguyenLieuIndex}][hinh_anh]" class="form-control" accept="image/*">
                                    </div>
                                    <!-- Nút xóa nguyên liệu -->
                                    <button type="button" class="btn btn-danger remove-nguyen-lieu">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>


                            <!-- Thêm nguyên liệu -->
                            <button type="button" id="add-nguyen-lieu" class="btn btn-secondary mt-3 mb-3">Thêm Nguyên
                                Liệu</button>

                            <!-- Submit -->
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('phieu-nhap-kho.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Quay lại danh sách
                                </a>
                                <button type="submit" class="btn btn-primary">Thêm mới</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <script>
        let nguyenLieuIndex = 1;

        // Hàm cập nhật lại thứ tự index
        function updateNguyenLieuIndex() {
            const items = document.querySelectorAll('.nguyen-lieu-item');
            items.forEach((item, index) => {
                // Cập nhật tiêu đề
                const title = item.querySelector('h5');
                title.textContent = `Nguyên Liệu ${index + 1}`;

                // Cập nhật lại tên của các input trong từng item
                const inputs = item.querySelectorAll('input, select');
                inputs.forEach(input => {
                    const name = input.name;
                    input.name = name.replace(/\[.*?\]/, `[${index}]`); // Cập nhật index trong name
                });
            });

            // Cập nhật nguyenLieuIndex với số lượng hiện tại
            nguyenLieuIndex = items.length;
        }

        // Xử lý thêm nguyên liệu
        document.getElementById('add-nguyen-lieu').addEventListener('click', function() {
            const container = document.getElementById('nguyen-lieu-container');
            const uniqueID = 'NL' + Math.random().toString(36).substr(2, 8)
                .toUpperCase(); // Tạo mã 10 ký tự duy nhất
            const newItem = `
            <div class="nguyen-lieu-item mb-3 border p-3 rounded">
                <h5 class="text-primary">Nguyên Liệu ${nguyenLieuIndex + 1}</h5>
    
                <!-- Mã nguyên liệu (Tự động) -->
                <div class="mb-3">
                    <label for="ma_nguyen_lieu_${uniqueID}" class="form-label">Mã Nguyên Liệu</label>
                    <input type="text" name="nguyen_lieu[${nguyenLieuIndex}][ma_nguyen_lieu]" class="form-control"
                        value="${uniqueID}" readonly>
                </div>
    
                <!-- Tên nguyên liệu -->
                <div class="mb-3">
                    <label for="ten_nguyen_lieu_${uniqueID}" class="form-label">Tên Nguyên Liệu</label>
                    <input type="text" name="nguyen_lieu[${nguyenLieuIndex}][ten_nguyen_lieu]" class="form-control" required>
                </div>
    
                <!-- Loại nguyên liệu -->
                <div class="mb-3">
                    <label for="loai_nguyen_lieu_id_${uniqueID}" class="form-label">Loại Nguyên Liệu</label>
                    <select name="nguyen_lieu[${nguyenLieuIndex}][loai_nguyen_lieu_id]" class="form-control" required>
                        @foreach ($loaiNguyenLieus as $loai)
                            <option value="{{ $loai->id }}">{{ $loai->ten_loai }}</option>
                        @endforeach
                    </select>
                </div>
    
                <!-- Đơn vị tính -->
                <div class="mb-3">
                    <label for="don_vi_tinh_${uniqueID}" class="form-label">Đơn Vị Tính</label>
                    <input type="text" name="nguyen_lieu[${nguyenLieuIndex}][don_vi_tinh]" class="form-control" required>
                </div>
    
                <!-- Số lượng -->
                <div class="mb-3">
                    <label for="so_luong_${uniqueID}" class="form-label">Số Lượng</label>
                    <input type="number" name="nguyen_lieu[${nguyenLieuIndex}][so_luong]" class="form-control" min="1" required>
                </div>
    
                <!-- Giá nhập -->
                <div class="mb-3">
                    <label for="don_gia_${uniqueID}" class="form-label">Giá Nhập</label>
                    <input type="number" step="0.01" name="nguyen_lieu[${nguyenLieuIndex}][don_gia]" class="form-control" required>
                </div>
                <!-- Hạn sử dụng -->
                <div class="mb-3">
                    <label for="han_su_dung_${uniqueID}" class="form-label">Hạn Sử Dụng</label>
                    <input type="date" name="nguyen_lieu[${nguyenLieuIndex}][han_su_dung]" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="hinh_anh_${uniqueID}" class="form-label">Hình Ảnh Nguyên Liệu</label>
                    <input type="file" name="nguyen_lieu[${nguyenLieuIndex}][hinh_anh]" class="form-control" accept="image/*">
                </div>
                <!-- Nút xóa nguyên liệu -->
                <button type="button" class="btn btn-danger remove-nguyen-lieu">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>`;

            container.insertAdjacentHTML('beforeend', newItem); // Thêm nội dung mới vào container
            updateNguyenLieuIndex(); // Cập nhật thứ tự sau khi thêm mới
        });

        // Xử lý sự kiện xóa nguyên liệu
        document.getElementById('nguyen-lieu-container').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-nguyen-lieu')) {
                const item = e.target.closest('.nguyen-lieu-item'); // Tìm phần tử cha gần nhất
                if (item) {
                    item.remove(); // Xóa phần tử
                    updateNguyenLieuIndex(); // Cập nhật thứ tự sau khi xóa
                }
            }
        });
    </script>



@endsection
