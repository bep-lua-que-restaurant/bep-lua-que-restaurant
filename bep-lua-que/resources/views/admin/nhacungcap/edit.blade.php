@extends('layouts.admin')

@section('title')
    Sửa nhà cung cấp
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4> Sửa nhà cung cấp</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)"> Sửa nhà cung cấp</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('nha-cung-cap.update', $nhaCungCap) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <!-- Tên danh mục -->
                            <div class="form-group">
                                <label for="name">Tên nhà cung cấp</label>
                                <input type="text" id="name" name="ten" class="form-control"
                                    value="{{ $nhaCungCap->ten }}">
                                @if ($errors->has('ten'))
                                    <small class="text-danger">*{{ $errors->first('ten') }}</small>
                                @endif
                            </div>

                            <!-- Mô tả -->
                            <div class="form-group">
                                <label for="description">Mô tả</label>
                                <textarea id="description" name="moTa" class="form-control" placeholder="Nhập mô tả">{{ $nhaCungCap->moTa }}</textarea>
                                @if ($errors->has('moTa'))
                                    <small class="text-danger">*{{ $errors->first('moTa') }}</small>
                                @endif
                            </div>


                            <!-- Hình ảnh -->
                            <div class="form-group">
                                <label for="image">Ảnh hiện tại</label>
                                <div class="image-container">
                                    @if ($nhaCungCap->hinhAnh)
                                        <img src="{{ asset('storage/' . $nhaCungCap->hinhAnh) }}"
                                            alt="{{ $nhaCungCap->ten }}" class="img-fluid rounded border"
                                            style="max-width: 250px; max-height: 250px;">
                                    @else
                                        <p>Mục này chưa có ảnh</p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="image">Tải lên hình ảnh</label>
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
                                <a href="{{route('nha-cung-cap.index' )}}" class="btn btn-primary btn-sm"> <i class="fa fa-arrow-left"></i> Quay lại
                                </a>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Lưu
                                </button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
