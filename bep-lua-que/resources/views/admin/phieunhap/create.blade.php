@extends('layouts.admin')

@section('title')
    Thêm Phiếu Nhập Kho
@endsection

@section('content')
<div class="container-fluid">
    <div class="row page-titles mx-0">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4>Thêm Phiếu Nhập Kho</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('phieu-nhap-kho.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label>Nhà cung cấp:</label>
                            <input type="text" name="nha_cung_cap" class="form-control" placeholder="Nhập tên nhà cung cấp" required>
                        </div>

                        <div class="form-group">
                            <label>Nhân viên nhập:</label>
                            <input type="text" name="nhan_vien" class="form-control" placeholder="Nhập tên nhân viên" required>
                        </div>

                        <div class="form-group">
                            <label>Ngày nhập:</label>
                            <input type="date" name="ngay_nhap" class="form-control" required>
                        </div>

                        <button type="button" id="add-nguyen-lieu" class="btn btn-success mb-3">Thêm Nguyên Liệu</button>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Loại Hàng</th>
                                    <th>Tên Nguyên Liệu</th>
                                    <th>Đơn Vị Tính</th>
                                    <th>Số Lượng</th>
                                    <th>Giá Nhập</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody id="nguyen-lieu-list"></tbody>
                        </table>

                        <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Tạo Phiếu Nhập</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById("add-nguyen-lieu").addEventListener("click", function() {
    var tableBody = document.getElementById("nguyen-lieu-list");
    var newRow = document.createElement("tr");

    newRow.innerHTML = `
        <td>
            <select name="loai_hang_id[]" class="form-control" required>
                <option value="">-- Chọn loại hàng --</option>
                @foreach($loaiNguyenLieus as $loai)
                    <option value="{{ $loai->id }}">{{ $loai->ten_loai }}</option>
                @endforeach
            </select>
        </td>
        <td><input type="text" name="ten_nguyen_lieu[]" class="form-control" placeholder="Nhập tên nguyên liệu" required></td>
        <td><input type="text" name="don_vi_tinh[]" class="form-control" placeholder="Nhập đơn vị tính" required></td>
        <td><input type="number" name="so_luong[]" class="form-control" min="1" required></td>
        <td><input type="text" name="gia_nhap[]" class="form-control" required></td>
        <td><button type="button" class="btn btn-danger remove-nguyen-lieu">Xóa</button></td>
    `;

    tableBody.appendChild(newRow);

    newRow.querySelector(".remove-nguyen-lieu").addEventListener("click", function() {
        newRow.remove();
    });
});
</script>
@endsection
