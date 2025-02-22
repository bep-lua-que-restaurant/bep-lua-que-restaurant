@extends('layouts.admin')

@section('title')
    Chỉnh sửa phòng ăn
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Chỉnh sửa phòng ăn</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('phong-an.index') }}">Danh sách phòng ăn</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Chỉnh sửa phòng ăn</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-primary">Cập nhật thông tin phòng ăn</h3>
                        <hr>

                        <!-- Form chỉnh sửa -->
                        <form action="{{ route('phong-an.update', $phongAn->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Tên bàn -->
                            <div class="form-group">
                                <label for="ten_phong_an">Tên phòng ăn</label>
                                <input type="text" id="ten_phong_an" name="ten_phong_an" class="form-control"
                                    value="{{ old('ten_phong_an', $phongAn->ten_phong_an) }}">
                                @if ($errors->has('ten_phong_an'))
                                    <small class="text-danger">*{{ $errors->first('ten_phong_an') }}</small>
                                @endif
                            </div>




                            <!-- Nút lưu -->
                            <div class="form-group text-right">
                                <a href="{{ route('phong-an.index') }}" class="btn btn-secondary">
                                    <i class="fa fa-arrow-left"></i> Quay lại
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Cập nhật
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
