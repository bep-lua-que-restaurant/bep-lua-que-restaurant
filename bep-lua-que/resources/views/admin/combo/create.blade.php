@extends('layouts.admin')

@section('title')
    Thêm mới combo
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Thêm mới combo</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Thêm mới combo</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('com-bo.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Tên combo -->
                            <div class="form-group">
                                <label for="name">Tên combo</label>
                                <input type="text" id="name" name="ten" class="form-control"
                                    placeholder="Nhập tên combo">
                                @if ($errors->has('ten'))
                                    <small class="text-danger">*{{ $errors->first('ten') }}</small>
                                @endif
                            </div>

                            <!-- Mô tả -->
                            <div class="form-group">
                                <label for="description">Mô tả</label>
                                <textarea id="description" name="mo_ta" class="form-control" placeholder="Nhập mô tả"></textarea>
                                @if ($errors->has('mo_ta'))
                                    <small class="text-danger">*{{ $errors->first('mo_ta') }}</small>
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
                                    <input name="hinh_anh" type="file" id="image-upload" accept="image/*"
                                        style="display: none;">
                                    @if ($errors->has('hinh_anh'))
                                        <small class="text-danger">{{ $errors->first('hinh_anh') }}</small>
                                    @endif
                                </div>
                            </div>

                            <!-- Nút submit -->
                            <div class="form-group text-right">
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
