@extends('layouts.admin')

@section('title')
    Thêm mới phòng ăn
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Thêm mới phòng ăn</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Thêm mới phòng ăn</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('phong-an.store') }}" method="POST">
                            @csrf

                            <!-- Tên phòng ăn -->
                            <div class="form-group">
                                <label for="ten_phong_an">Tên phòng ăn</label>
                                <input type="text" id="ten_phong_an" name="ten_phong_an" class="form-control"
                                    placeholder="Nhập tên phòng ăn" value="{{ old('ten_phong_an') }}">
                                @if ($errors->has('ten_phong_an'))
                                    <small class="text-danger">*{{ $errors->first('ten_phong_an') }}</small>
                                @endif
                            </div>

                            <!-- Nút submit -->
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus"></i> Thêm mới
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
