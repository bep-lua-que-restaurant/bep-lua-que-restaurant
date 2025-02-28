@extends('layouts.admin')

@section('title')
    Cập nhật món ăn
@endsection

@section('content')
    <div class="container">
        <h2>Cập nhật Món Ăn</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('mon-an.update', $monAn->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="ten" class="form-label">Tên món ăn</label>
                <input type="text" name="ten" id="ten" class="form-control"
                    value="{{ old('ten', $monAn->ten) }}">
            </div>

            <div class="mb-3">
                <label for="gia" class="form-label">Giá món ăn (VNĐ)</label>
                <input type="number" name="gia" id="gia" class="form-control"
                    value="{{ old('gia', $monAn->gia) }}" min="0">
            </div>

            <div class="mb-3">
                <label for="danh_muc_mon_an_id" class="form-label">Danh mục</label>
                <select name="danh_muc_mon_an_id" id="danh_muc_mon_an_id" class="form-control">
                    @foreach ($danhMucs as $danhMuc)
                        <option value="{{ $danhMuc->id }}"
                            {{ old('danh_muc_mon_an_id', $monAn->danh_muc_mon_an_id) == $danhMuc->id ? 'selected' : '' }}>
                            {{ $danhMuc->ten }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="trang_thai" class="form-label">Trạng thái</label>
                <select name="trang_thai" id="trang_thai" class="form-control">
                    <option value="dang_ban" {{ old('trang_thai', $monAn->trang_thai) == 'dang_ban' ? 'selected' : '' }}>
                        Đang bán</option>
                    <option value="het_hang" {{ old('trang_thai', $monAn->trang_thai) == 'het_hang' ? 'selected' : '' }}>Hết
                        hàng</option>
                    <option value="ngung_ban" {{ old('trang_thai', $monAn->trang_thai) == 'ngung_ban' ? 'selected' : '' }}>
                        Ngừng bán</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Ảnh hiện tại</label>
                <div id="currentImages" style="display: flex; gap: 10px; flex-wrap: wrap;">
                    @foreach ($monAn->hinhAnhs as $hinhAnh)
                        <div class="image-preview" style="position: relative; display: inline-block;">
                            <img src="{{ asset('storage/' . $hinhAnh->hinh_anh) }}" class="img-thumbnail"
                                style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px;">
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mb-3">
                <label for="hinh_anh" class="form-label">Chọn ảnh mới</label>
                <input type="file" name="hinh_anh[]" id="hinh_anh" class="form-control" multiple accept="image/*">
                <div id="previewImages" style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 10px;"></div>
            </div>

            <!-- Nguyên liệu món ăn -->
            <div id="nguyenLieuContainer">
                <h4>Nguyên Liệu Món Ăn</h4>
                @foreach ($monAnNguyenLieus as $index => $chiTiet)
                    <div class="nguyen-lieu-row d-flex align-items-center mb-2">
                        <select name="nguyen_lieu_id[]" class="form-control me-2">
                            <option value="">Chọn nguyên liệu</option>
                            @foreach ($nguyenLieus as $nguyenLieu)
                                <option value="{{ $nguyenLieu->id }}"
                                    {{ $chiTiet->pivot->nguyen_lieu_id == $nguyenLieu->id ? 'selected' : '' }}>
                                    {{ $nguyenLieu->ten_nguyen_lieu }}
                                </option>
                            @endforeach
                        </select>
                        <input type="number" name="so_luong[]" class="form-control me-2" placeholder="Số lượng"
                            value="{{ $chiTiet->pivot->so_luong }}">
                        <input type="text" name="don_vi_tinh[]" class="form-control me-2" placeholder="Đơn vị tính"
                            value="{{ $chiTiet->pivot->don_vi_tinh }}">
                        <button type="button" class="btn btn-danger remove-row">Xóa</button>
                    </div>
                @endforeach
            </div>

            <button type="button" id="addNguyenLieu" class="btn btn-success mb-3">Thêm nguyên liệu</button>

            <div class="d-flex justify-content-between">
                <a href="{{ route('mon-an.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại danh sách
                </a>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </div>
        </form>
    </div>

    <script>

        document.getElementById('hinh_anh').addEventListener('change', function(event) {
            const previewContainer = document.getElementById('previewImages');
            previewContainer.innerHTML = '';
            const files = event.target.files;
            Array.from(files).forEach(file => 
            {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('img-thumbnail');
                    img.style.width = '100px';
                    img.style.height = '100px';
                    img.style.objectFit = 'cover';
                    img.style.borderRadius = '5px';
                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        });

        document.getElementById('addNguyenLieu').addEventListener('click', function() {
            let newRow = `
                <div class="nguyen-lieu-row d-flex align-items-center mb-2">
                    <select name="nguyen_lieu_id[]" class="form-control me-2">
                        <option value="">Chọn nguyên liệu</option>
                        @foreach ($nguyenLieus as $nguyenLieu)
                            <option value="{{ $nguyenLieu->id }}">{{ $nguyenLieu->ten_nguyen_lieu }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="so_luong[]" class="form-control me-2" placeholder="Số lượng">
                    <input type="text" name="don_vi_tinh[]" class="form-control me-2" placeholder="Đơn vị tính">
                    <button type="button" class="btn btn-danger remove-row">Xóa</button>
                </div>`;
            document.getElementById('nguyenLieuContainer').insertAdjacentHTML('beforeend', newRow);
        });
    </script>
@endsection
