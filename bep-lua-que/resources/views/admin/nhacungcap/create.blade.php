@extends('layouts.admin')

@section('title')
    Thêm mới nhà cung cấp
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Thêm mới nhà cung cấp</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Thêm mới nhà cung cấp</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('nha-cung-cap.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <!-- Tên danh mục -->
                            <div class="form-group">
                                <label for="name">Tên nhà cung cấp</label>
                                <input type="text" id="ten_nha_cung_cap" name="ten_nha_cung_cap" class="form-control"
                                    placeholder="Nhập tên nhà cung cấp">
                                @if ($errors->has('ten_nha_cung_cap'))
                                    <small class="text-danger">*{{ $errors->first('ten_nha_cung_cap') }}</small>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="name">Địa chỉ</label>
                                <input type="text" id="dia_chi" name="dia_chi" class="form-control"
                                    placeholder="Nhập địa chỉ">
                                @if ($errors->has('dia_chi'))
                                    <small class="text-danger">*{{ $errors->first('dia_chi') }}</small>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="name">Số điện thoại</label>
                                <input type="tel" id="so_dien_thoai" name="so_dien_thoai" class="form-control"
                                    placeholder="Nhập số điện thoại">
                                @if ($errors->has('so_dien_thoai'))
                                    <small class="text-danger">*{{ $errors->first('so_dien_thoai') }}</small>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="name">Email</label>
                                <input type="email" id="email" name="email" class="form-control"
                                    placeholder="Nhập email">
                                @if ($errors->has('email'))
                                    <small class="text-danger">*{{ $errors->first('email') }}</small>
                                @endif
                            </div>

                            <!-- Mô tả -->
                            <div class="form-group">
                                <label for="description">Mô tả</label>
                                <textarea id="description" name="moTa" class="form-control" placeholder="Nhập mô tả"></textarea>
                                @if ($errors->has('moTa'))
                                    <small class="text-danger">*{{ $errors->first('moTa') }}</small>
                                @endif
                            </div>

                            <!-- Hình ảnh -->
                            <div class="form-group">
                                <label for="image">Hình ảnh</label>
                                <div class="image-upload-container">
                                    <label for="image-upload" class="image-upload-label">
                                        <i class="icon-camera"></i>
                                        <img id="preview-image" alt="Preview" style="display: none;">
                                    </label>
                                    <input name="hinhAnh" type="file" id="image-upload" accept="image/*"
                                        style="display: none;">
                                    @if ($errors->has('hinhAnh'))
                                        <small class="text-danger">{{ $errors->first('hinhAnh') }}</small>
                                    @endif
                                </div>
                            </div>

                            <!-- Nút submit -->
                            <div class="form-group text-right">
                                <a href="{{ route('nha-cung-cap.index') }}" class="btn btn-primary btn-sm"> <i
                                        class="fa fa-arrow-left"></i> Quay lại
                                </a>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Thêm
                                    mới</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
