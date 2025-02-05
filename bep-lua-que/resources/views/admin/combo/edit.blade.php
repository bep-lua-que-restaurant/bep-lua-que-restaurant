@extends('layouts.admin')

@section('title')
    Sửa combo
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4> Sửa combo</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)"> Sửa combo</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('com-bo.update', $comBo) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <!-- Tên combo -->
                            <div class="form-group">
                                <label for="name">Tên combo</label>
                                <input type="text" id="name" name="ten" class="form-control"
                                    value="{{ $comBo->ten }}">
                                @if ($errors->has('ten'))
                                    <small class="text-danger">*{{ $errors->first('ten') }}</small>
                                @endif
                            </div>

                            <!-- Mô tả -->
                            <div class="form-group">
                                <label for="description">Mô tả</label>
                                <textarea id="description" name="mo_ta" class="form-control" placeholder="Nhập mô tả">{{ $comBo->mo_ta }}</textarea>
                                @if ($errors->has('mo_ta'))
                                    <small class="text-danger">*{{ $errors->first('mo_ta') }}</small>
                                @endif
                            </div>


                            <!-- Hình ảnh -->
                            <div class="form-group">
                                <label for="image">Ảnh hiện tại</label>
                                <div class="image-container">
                                    @if ($comBo->hinh_anh)
                                        <img src="{{ asset('storage/' . $comBo->hinh_anh) }}" alt="{{ $comBo->ten }}"
                                            class="img-fluid rounded border" style="max-width: 250px; max-height: 250px;">
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
                                    <input name="hinh_anh" type="file" id="image-upload" accept="image/*"
                                        style="display: none;">
                                    @if ($errors->has('hinh_anh'))
                                        <small class="text-danger">{{ $errors->first('hinh_anh') }}</small>
                                    @endif
                                </div>
                            </div>

                            <!-- Nút submit -->
                            <div class="form-group text-right">
                                <a href="{{ route('com-bo.index') }}" class="btn btn-primary btn-sm"> <i
                                        class="fa fa-arrow-left"></i> Quay lại
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
