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

                <!-- Công thức -->
                <div class="col-12">
                    <label class="form-label">Công thức món ăn</label>
                    <div id="cong-thuc-list">
                        @php
                            $congThucList =
                                old('cong_thuc') ??
                                $monAn->congThuc
                                    ->map(function ($ct) {
                                        return [
                                            'nguyen_lieu_id' => $ct->nguyen_lieu_id,
                                            'so_luong' => $ct->so_luong,
                                            'don_vi' => $ct->don_vi,
                                        ];
                                    })
                                    ->toArray();
                        @endphp

                        @foreach ($congThucList as $i => $ct)
                            <div class="row mb-2 cong-thuc-item">
                                <div class="col-md-5">
                                    <select name="cong_thuc[{{ $i }}][nguyen_lieu_id]"
                                        class="form-select select-nguyen-lieu">
                                        <option value="">-- Chọn nguyên liệu --</option>
                                        @foreach ($nguyenLieus as $nl)
                                            <option value="{{ $nl->id }}" data-don-vi="{{ $nl->don_vi_ton }}"
                                                {{ $ct['nguyen_lieu_id'] == $nl->id ? 'selected' : '' }}>
                                                {{ $nl->ten_nguyen_lieu }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" step="0.01" name="cong_thuc[{{ $i }}][so_luong]"
                                        class="form-control" placeholder="Số lượng" value="{{ $ct['so_luong'] }}">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="cong_thuc[{{ $i }}][don_vi]"
                                        class="form-control" placeholder="Đơn vị" value="{{ $ct['don_vi'] }}" readonly>
                                </div>
                                <div class="col-md-1 text-end">
                                    <button type="button" class="btn btn-danger btn-sm remove-ct">&times;</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" id="add-cong-thuc" class="btn btn-outline-primary btn-sm mt-2">+ Thêm nguyên
                        liệu</button>
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
            document.querySelectorAll('.select-nguyen-lieu').forEach(select => {
                select.addEventListener('change', function() {
                    handleNguyenLieuChange(this);
                });
            });

            function handleNguyenLieuChange(selectElement) {
                const selectedOption = selectElement.options[selectElement.selectedIndex];
                const donVi = selectedOption.getAttribute('data-don-vi') || '';
                const donViInput = selectElement.closest('.cong-thuc-item').querySelector(
                'input[name*="[don_vi]"]');
                if (donViInput) {
                    donViInput.value = donVi;
                }

                // Kiểm tra trùng nguyên liệu
                const selectedValue = selectElement.value;
                if (selectedValue) {
                    let isDuplicate = false;
                    document.querySelectorAll('.select-nguyen-lieu').forEach(select => {
                        if (select !== selectElement && select.value === selectedValue) {
                            isDuplicate = true;
                        }
                    });

                    if (isDuplicate) {
                        alert('Nguyên liệu này đã được chọn. Vui lòng chọn nguyên liệu khác.');
                        selectElement.value = '';
                        donViInput.value = '';
                    }
                }
            }


            document.getElementById('add-cong-thuc').addEventListener('click', function() {
                const index = Date.now(); // đảm bảo index duy nhất
                const html = `
    <div class="row mb-2 cong-thuc-item">
        <div class="col-md-5">
            <select name="cong_thuc[${index}][nguyen_lieu_id]" class="form-select select-nguyen-lieu">
                <option value="">-- Chọn nguyên liệu --</option>
                @foreach ($nguyenLieus as $nl)
                    <option value="{{ $nl->id }}" data-don-vi="{{ $nl->don_vi_ton }}">{{ $nl->ten_nguyen_lieu }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <input type="number" step="0.01" name="cong_thuc[${index}][so_luong]" class="form-control" placeholder="Số lượng">
        </div>
        <div class="col-md-3">
            <input type="text" name="cong_thuc[${index}][don_vi]" class="form-control" placeholder="Đơn vị" readonly>
        </div>
        <div class="col-md-1 text-end">
            <button type="button" class="btn btn-danger btn-sm remove-ct">&times;</button>
        </div>
    </div>`;
                const container = document.getElementById('cong-thuc-list');
                container.insertAdjacentHTML('beforeend', html);

                // Lấy phần tử select vừa thêm và gắn lại sự kiện
                const newItem = container.lastElementChild;
                const newSelect = newItem.querySelector('.select-nguyen-lieu');
                newSelect.addEventListener('change', function() {
                    handleNguyenLieuChange(this);
                });
            });


            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-ct')) {
                    e.target.closest('.cong-thuc-item').remove();
                }
            });

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
