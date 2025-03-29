@extends('layouts.admin')

@section('title')
    Sửa dịch vụ
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4> Sửa dịch vụ</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)"> Sửa dịch vụ</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('dich-vu.update', $dichVu) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <!-- Tên dịch vụ -->
                            <div class="form-group">
                                <label for="name">Tên dịch vụ</label>
                                <input type="text" id="name" name="ten_dich_vu" class="form-control"
                                    value="{{ $dichVu->ten_dich_vu }}">
                                @if ($errors->has('ten_dich_vu'))
                                    <small class="text-danger">*{{ $errors->first('ten_dich_vu') }}</small>
                                @endif
                            </div>

                            <!-- Mô tả -->
                            <div class="form-group">
                                <label for="description">Mô tả</label>
                                <textarea id="description" name="mo_ta" class="form-control" placeholder="Nhập mô tả">{{ $dichVu->mo_ta }}</textarea>
                                @if ($errors->has('mo_ta'))
                                    <small class="text-danger">*{{ $errors->first('mo_ta') }}</small>
                                @endif
                            </div>


                            <!-- Nút submit -->
                            <div class="form-group text-right">
                                <a href="{{ route('dich-vu.index') }}" class="btn btn-primary btn-sm"> <i
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
