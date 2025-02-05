@extends('layouts.admin')

@section('title')
    Cập nhật món ăn
@endsection

@section('content')
    <div class="container">
        <h2>Cập nhật Món Ăn</h2>
        <form action="{{ route('mon-an.update', $monAn->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="ten" class="form-label">Tên món ăn</label>
                <input type="text" name="ten" id="ten" class="form-control" value="{{ old('ten', $monAn->ten) }}">
                @error('ten')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="danh_muc_mon_an_id" class="form-label">Danh mục</label>
                <select name="danh_muc_mon_an_id" id="danh_muc_mon_an_id" class="form-control">
                    @foreach ($danhMucs as $danhMuc)
                        <option value="{{ $danhMuc->id }}"
                            {{ $monAn->danh_muc_mon_an_id == $danhMuc->id ? 'selected' : '' }}>
                            {{ $danhMuc->ten }}
                        </option>
                    @endforeach
                </select>
                @error('danh_muc_mon_an_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Mô tả -->
            <div class="form-group">
                <label for="description">Mô tả</label>
                <textarea id="description" name="mo_ta" class="form-control" placeholder="Nhập mô tả">{{ old('mo_ta', $monAn->mo_ta) }}</textarea>
                @error('mo_ta')
                    <small class="text-danger">*{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="gia" class="form-label">Giá</label>
                <input type="number" name="gia" id="gia" class="form-control"
                    value="{{ old('gia', $monAn->gia) }}">
                @error('gia')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="trang_thai" class="form-label">Trạng thái</label>
                <select name="trang_thai" id="trang_thai" class="form-control">
                    <option value="dang_ban" {{ $monAn->trang_thai == 'dang_ban' ? 'selected' : '' }}>Đang bán</option>
                    <option value="het_hang" {{ $monAn->trang_thai == 'het_hang' ? 'selected' : '' }}>Hết hàng</option>
                    <option value="ngung_ban" {{ $monAn->trang_thai == 'ngung_ban' ? 'selected' : '' }}>Ngừng bán</option>
                </select>
            </div>

            <!-- Hiển thị ảnh hiện tại với nút xóa -->
            <div class="mb-3">
                <label class="form-label">Ảnh hiện tại</label>
                <div id="currentImages" style="display: flex; gap: 10px; flex-wrap: wrap;">
                    @foreach ($monAn->hinhAnhs as $hinhAnh)
                        <div class="image-preview" style="position: relative; display: inline-block;">
                            <img src="{{ asset('storage/' . $hinhAnh->hinh_anh) }}" class="img-thumbnail"
                                style="width: 100px; height: 100px; object-fit: cover;">
                            <button type="button" class="btn btn-danger btn-sm remove-existing-image"
                                data-id="{{ $hinhAnh->id }}"
                                style="position: absolute; top: 5px; right: 5px; width: 18px; height: 18px; line-height: 10px; padding: 0; font-size: 12px; border-radius: 50%;">
                                ×
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mb-3">
                <label for="hinh_anh" class="form-label">Chọn ảnh mới</label>
                <input type="file" name="hinh_anh[]" id="hinh_anh" class="form-control" multiple accept="image/*">
                <div id="previewImages" style="display: flex; gap: 10px; flex-wrap: wrap;"></div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('mon-an.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại danh sách
                </a>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let selectedFiles = [];

            $("#hinh_anh").on("change", function() {
                $("#previewImages").empty();
                selectedFiles = Array.from(this.files);

                selectedFiles.forEach((file, index) => {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        let imgDiv = $(`
                            <div class="image-preview" style="position: relative; display: inline-block;">
                                <img src="${e.target.result}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
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

            // Xóa ảnh mới đã chọn
            $(document).on("click", ".remove-image", function() {
                let index = $(this).data("index");
                selectedFiles.splice(index, 1);
                let dataTransfer = new DataTransfer();
                selectedFiles.forEach(file => dataTransfer.items.add(file));
                document.getElementById("hinh_anh").files = dataTransfer.files;
                $(this).closest(".image-preview").remove();
            });

            // Xóa ảnh hiện có từ server (Gửi request để xóa)
            $(document).on("click", ".remove-existing-image", function() {
                let imageId = $(this).data("id");
                let imageDiv = $(this).closest(".image-preview");

                if (confirm("Bạn có chắc chắn muốn xóa ảnh này?")) {
                    $.ajax({
                        url: "{{ route('mon-an.xoa-hinh-anh', '') }}/" + imageId,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            imageDiv.remove();
                            alert("Ảnh đã được xóa thành công!");
                        },
                        error: function(xhr) {
                            alert("Lỗi khi xóa ảnh. Vui lòng thử lại!");
                        }
                    });
                }
            });

        });
    </script>
@endsection
