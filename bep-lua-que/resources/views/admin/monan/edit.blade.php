@extends('layouts.admin')

@section('title')
    Cập nhật món ăn
@endsection

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">🍽️ Cập nhật Món Ăn</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('mon-an.update', $monAn->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="ten" class="form-label">Tên món ăn</label>
                    <input type="text" name="ten" id="ten" class="form-control"
                        value="{{ old('ten', $monAn->ten) }}" placeholder="Nhập tên món ăn">
                </div>

                <div class="col-md-6">
                    <label for="gia" class="form-label">Giá món ăn (VNĐ)</label>
                    <input type="number" name="gia" id="gia" class="form-control"
                        value="{{ old('gia', $monAn->gia) }}" min="0" placeholder="Nhập giá món ăn">
                </div>

                <div class="col-md-6">
                    <label for="thoi_gian_nau" class="form-label">Thời gian nấu (phút)</label>
                    <input type="number" name="thoi_gian_nau" id="thoi_gian_nau" class="form-control"
                        value="{{ old('thoi_gian_nau', $monAn->thoi_gian_nau) }}" min="0"
                        placeholder="Nhập thời gian nấu">
                </div>

                <div class="col-md-6">
                    <label for="danh_muc_mon_an_id" class="form-label">Danh mục</label>
                    <select name="danh_muc_mon_an_id" id="danh_muc_mon_an_id" class="form-select">
                        @foreach ($danhMucs as $danhMuc)
                            <option value="{{ $danhMuc->id }}"
                                {{ old('danh_muc_mon_an_id', $monAn->danh_muc_mon_an_id) == $danhMuc->id ? 'selected' : '' }}>
                                {{ $danhMuc->ten }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="trang_thai" class="form-label">Trạng thái</label>
                    <select name="trang_thai" id="trang_thai" class="form-select">
                        <option value="dang_ban"
                            {{ old('trang_thai', $monAn->trang_thai) == 'dang_ban' ? 'selected' : '' }}>Đang bán</option>
                        <option value="het_hang"
                            {{ old('trang_thai', $monAn->trang_thai) == 'het_hang' ? 'selected' : '' }}>Hết hàng</option>
                        <option value="ngung_ban"
                            {{ old('trang_thai', $monAn->trang_thai) == 'ngung_ban' ? 'selected' : '' }}>Ngừng bán</option>
                    </select>
                </div>

                <div class="col-12">
                    <label for="mo_ta" class="form-label">Mô tả</label>
                    <textarea name="mo_ta" id="mo_ta" rows="4" class="form-control">{{ old('mo_ta', $monAn->mo_ta) }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Ảnh hiện tại</label>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($monAn->hinhAnhs as $hinhAnh)
                            <img src="{{ asset('storage/' . $hinhAnh->hinh_anh) }}" class="img-thumbnail"
                                style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;">
                        @endforeach
                    </div>
                </div>

                <div class="col-12">
                    <label for="hinh_anh" class="form-label">Chọn ảnh mới</label>
                    <input type="file" name="hinh_anh[]" id="hinh_anh" class="form-control" multiple accept="image/*">
                    <div id="previewImages" class="d-flex flex-wrap gap-2 mt-2"></div>
                </div>

               

                <div class="col-12 d-flex justify-content-between mt-4">
                    <a href="{{ route('mon-an.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
        

            document.getElementById('hinh_anh')?.addEventListener('change', function(event) {
                const previewContainer = document.getElementById('previewImages');
                previewContainer.innerHTML = '';
                Array.from(event.target.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.classList.add('img-thumbnail');
                        img.style.width = '100px';
                        img.style.height = '100px';
                        img.style.objectFit = 'cover';
                        img.style.borderRadius = '8px';
                        previewContainer.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            });
        });
    </script>
@endsection
