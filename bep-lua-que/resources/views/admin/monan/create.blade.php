@extends('layouts.admin')

@section('title')
    Thêm mới món ăn
@endsection

@section('content')
    <div class="container">
        <h2>Thêm Món Ăn</h2>
        <form action="{{ route('mon-an.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="ten" class="form-label">Tên món ăn</label>
                <input type="text" name="ten" id="ten" class="form-control" value="{{ old('ten') }}">
                @error('ten')
                    <div class="text-danger">*{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="danh_muc_mon_an_id" class="form-label">Danh mục</label>
                <select name="danh_muc_mon_an_id" id="danh_muc_mon_an_id" class="form-control">
                    @foreach ($danhMucs as $danhMuc)
                        <option value="{{ $danhMuc->id }}">{{ $danhMuc->ten }}</option>
                    @endforeach
                </select>
                @error('danh_muc_mon_an_id')
                    <div class="text-danger">*{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Mô tả</label>
                <textarea id="description" name="mo_ta" class="form-control" placeholder="Nhập mô tả">{{ old('mo_ta') }}</textarea>
                @error('mo_ta')
                    <small class="text-danger">*{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="gia" class="form-label">Giá</label>
                <input type="number" name="gia" id="gia" class="form-control" value="{{ old('gia') }}">
                @error('gia')
                    <div class="text-danger">*{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="hinh_anh" class="form-label">Hình ảnh</label>
                <input type="file" name="hinh_anh[]" id="hinh_anh" class="form-control" multiple accept="image/*">
                @error('hinh_anh.*')
                    <div class="text-danger">*{{ $message }}</div>
                @enderror
            </div>

            <!-- Hiển thị ảnh đã chọn -->
            <div id="previewImages" class="mb-3 d-flex gap-2 flex-wrap"></div>
            {{-- Nguyên liệu món ăn --}}
            <div id="nguyenLieuContainer">
                <h4>Nguyên Liệu Món Ăn</h4>
                @if (old('nguyen_lieu_id'))
                    @foreach (old('nguyen_lieu_id') as $index => $nguyen_lieu_id)
                        <div class="nguyen-lieu-row d-flex align-items-center mb-2">
                            <div class="me-2">
                                <select name="nguyen_lieu_id[]" class="form-control">
                                    <option value="">Chọn nguyên liệu</option>
                                    @foreach ($nguyenLieus as $nguyenLieu)
                                        <option value="{{ $nguyenLieu->id }}" {{ $nguyen_lieu_id == $nguyenLieu->id ? 'selected' : '' }}>
                                            {{ $nguyenLieu->ten_nguyen_lieu }}
                                        </option>
                                    @endforeach
                                </select>
                                @error("nguyen_lieu_id.{$index}")
                                    <div class="text-danger mt-1">*{{ $message }}</div>
                                @enderror
                            </div>
            
                            <div class="me-2">
                                <input type="number" name="so_luong[]" class="form-control" placeholder="Số lượng"
                                       value="{{ old('so_luong')[$index] ?? '' }}">
                                @error("so_luong.{$index}")
                                    <div class="text-danger mt-1">*{{ $message }}</div>
                                @enderror
                            </div>
            
                            <div class="me-2">
                                <input type="text" name="don_vi_tinh[]" class="form-control" placeholder="Đơn vị tính"
                                       value="{{ old('don_vi_tinh')[$index] ?? '' }}">
                                @error("don_vi_tinh.{$index}")
                                    <div class="text-danger mt-1">*{{ $message }}</div>
                                @enderror
                            </div>
            
                            <button type="button" class="btn btn-danger remove-row">Xóa</button>
                        </div>
                    @endforeach
                @else
                    <div class="nguyen-lieu-row d-flex align-items-center mb-2">
                        <div class="me-2">
                            <select name="nguyen_lieu_id[]" class="form-control">
                                <option value="">Chọn nguyên liệu</option>
                                @foreach ($nguyenLieus as $nguyenLieu)
                                    <option value="{{ $nguyenLieu->id }}">{{ $nguyenLieu->ten_nguyen_lieu }}</option>
                                @endforeach
                            </select>
                        </div>
            
                        <div class="me-2">
                            <input type="number" name="so_luong[]" class="form-control" placeholder="Số lượng">
                        </div>
            
                        <div class="me-2">
                            <input type="text" name="don_vi_tinh[]" class="form-control" placeholder="Đơn vị tính">
                        </div>
            
                        <button type="button" class="btn btn-danger remove-row">Xóa</button>
                    </div>
                @endif
            </div>
            
            
            <!-- Nút thêm nguyên liệu -->
            <button type="button" id="addNguyenLieu" class="btn btn-success mb-3">Thêm nguyên liệu</button>
            
            <div class="d-flex justify-content-between">
                <a href="{{ route('mon-an.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại danh sách
                </a>
                <button type="submit" class="btn btn-primary">Thêm</button>
            </div>



        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#addNguyenLieu').click(function() {
                let newRow = `
                    <div class="nguyen-lieu-row d-flex align-items-center mb-2">
                        <div class="me-2">
                            <select name="nguyen_lieu_id[]" class="form-control">
                                <option value="">Chọn nguyên liệu</option>
                                @foreach ($nguyenLieus as $nguyenLieu)
                                    <option value="{{ $nguyenLieu->id }}">{{ $nguyenLieu->ten_nguyen_lieu }}</option>
                                @endforeach
                            </select>
                        </div>
    
                        <div class="me-2">
                            <input type="number" name="so_luong[]" class="form-control" placeholder="Số lượng">
                        </div>
    
                        <div class="me-2">
                            <input type="text" name="don_vi_tinh[]" class="form-control" placeholder="Đơn vị tính">
                        </div>
    
                        <button type="button" class="btn btn-danger remove-row">Xóa</button>
                    </div>`;
                $('#nguyenLieuContainer').append(newRow);
            });
    
            // Xóa nguyên liệu
            $(document).on('click', '.remove-row', function() {
                $(this).closest('.nguyen-lieu-row').remove();
            });
    
            // Hiển thị ảnh trước khi upload
            $("#hinh_anh").on("change", function() {
                $("#previewImages").empty();
                Array.from(this.files).forEach((file, index) => {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        let imgDiv = $(`
                            <div class="image-preview position-relative">
                                <img src="${e.target.result}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px;">
                                <button type="button" class="btn btn-danger btn-sm remove-image" data-index="${index}"
                                    style="position: absolute; top: 5px; right: 5px; width: 18px; height: 18px; line-height: 10px; padding: 0; font-size: 12px; border-radius: 50%;">
                                    ×
                                </button>
                            </div>
                        `);
                        $("#previewImages").append(imgDiv);
                    };
                    reader.readAsDataURL(file);
                });
            });
    
            // Xóa ảnh đã chọn
            $(document).on("click", ".remove-image", function() {
                let index = $(this).data("index");
                let dataTransfer = new DataTransfer();
                let files = $("#hinh_anh")[0].files;
                Array.from(files).forEach((file, i) => {
                    if (i !== index) dataTransfer.items.add(file);
                });
                $("#hinh_anh")[0].files = dataTransfer.files;
                $(this).closest(".image-preview").remove();
            });
        });
    </script>
    
@endsection
