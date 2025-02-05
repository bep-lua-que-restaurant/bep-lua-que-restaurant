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
            </div>

            <div class="mb-3">
                <label for="trang_thai" class="form-label">Trạng thái</label>
                <select name="trang_thai" id="trang_thai" class="form-control">
                    <option value="dang_ban" {{ $monAn->trang_thai == 'dang_ban' ? 'selected' : '' }}>Đang bán</option>
                    <option value="het_hang" {{ $monAn->trang_thai == 'het_hang' ? 'selected' : '' }}>Hết hàng</option>
                    <option value="ngung_ban" {{ $monAn->trang_thai == 'ngung_ban' ? 'selected' : '' }}>Ngừng bán</option>
                </select>
            </div>

            <!-- Hiển thị ảnh hiện tại với dấu X hình tròn -->
            <div class="mb-3">
                <label class="form-label">Ảnh hiện tại</label>
                <div id="currentImages" style="display: flex; gap: 10px; flex-wrap: wrap;">
                    @foreach ($monAn->hinhAnhs as $hinhAnh)
                        <div class="image-preview" style="position: relative; display: inline-block;">
                            <img src="{{ asset('storage/' . $hinhAnh->hinh_anh) }}" class="img-thumbnail"
                                style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px;">

                            <!-- Nút xóa ảnh với hình tròn -->
                            <button type="button" class="btn btn-danger btn-sm remove-existing-image"
                                data-id="{{ $hinhAnh->id }}"
                                style="position: absolute; top: 5px; right: 5px; width: 24px; height: 24px; border-radius: 50%; padding: 0; display: flex; 
                                justify-content: center; align-items: center; font-size: 16px;background-color: rgba(255, 0, 0, 0.7);border: none;color: white;cursor: pointer;
                                ">
                                &times;
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mb-3">
                <label for="hinh_anh" class="form-label">Chọn ảnh mới</label>
                <input type="file" name="hinh_anh[]" id="hinh_anh" class="form-control" multiple accept="image/*">
                <div id="previewImages"></div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('mon-an.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại danh sách
                </a>
                <button type="submit" class="btn btn-primary">Cập nhập</button>
            </div>
        </form>
    </div>

    <script>
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
                    success: function() {
                        imageDiv.remove();
                        alert("Ảnh đã được xóa thành công!");
                    },
                    error: function() {
                        alert("Lỗi khi xóa ảnh!");
                    }
                });
            }
        });
    </script>
@endsection
