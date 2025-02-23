@extends('layouts.admin')

@section('title', 'Sửa loại nguyên liệu')

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Sửa loại nguyên liệu</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Sửa loại nguyên liệu</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('loai-nguyen-lieu.update', $loaiNguyenLieu) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="ma_loai">Mã loại</label>
                                <input type="text" id="ma_loai" name="ma_loai" class="form-control" value="{{ $loaiNguyenLieu->ma_loai }}">
                                @error('ma_loai') <small class="text-danger">*{{ $message }}</small> @enderror
                            </div>

                            <div class="form-group">
                                <label for="ten_loai">Tên loại</label>
                                <input type="text" id="ten_loai" name="ten_loai" class="form-control" value="{{ $loaiNguyenLieu->ten_loai }}">
                                @error('ten_loai') <small class="text-danger">*{{ $message }}</small> @enderror
                            </div>

                            <div class="form-group">
                                <label for="mo_ta">Mô tả</label>
                                <textarea id="mo_ta" name="mo_ta" class="form-control">{{ $loaiNguyenLieu->mo_ta }}</textarea>
                                @error('mo_ta') <small class="text-danger">*{{ $message }}</small> @enderror
                            </div>

                            <div class="form-group text-right">
                                <a href="{{ route('loai-nguyen-lieu.index') }}" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left"></i> Quay lại</a>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Lưu</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
