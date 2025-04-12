@extends('layouts.admin')

@section('title')
    Thêm mới loại nguyên liệu
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Thêm mới loại nguyên liệu</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Thêm mới loại nguyên liệu</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('loai-nguyen-lieu.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Tên loại nguyên liệu -->
                            <div class="form-group">
                                <label for="name">Tên loại nguyên liệu</label>
                                <input type="text" id="name" name="ten_loai" class="form-control"
                                    placeholder="Nhập tên loại nguyên liệu">
                                @if ($errors->has('ten_loai'))
                                    <small class="text-danger">*{{ $errors->first('ten_loai') }}</small>
                                @endif
                            </div>

                            <!-- Ghi chú -->
                            <div class="form-group">
                                <label for="ghi_chu">Ghi chú</label>
                                <textarea id="ghi_chu" name="ghi_chu" class="form-control" placeholder="Nhập ghi chú"></textarea>
                                @if ($errors->has('ghi_chu'))
                                    <small class="text-danger">*{{ $errors->first('ghi_chu') }}</small>
                                @endif
                            </div>

                            <!-- Nút submit -->
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Thêm mới</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
