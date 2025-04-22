@extends('layouts.admin')

@section('title')
    Thêm chức vụ
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Thêm chức vụ mới</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('chuc-vu.index') }}">Chức vụ</a></li>
                    <li class="breadcrumb-item active">Thêm mới</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Thêm chức vụ</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('chuc-vu.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="ten_chuc_vu" class="form-label">Tên chức vụ</label>
                                <input type="text" name="ten_chuc_vu" id="ten_chuc_vu" class="form-control" value="{{ old('ten_chuc_vu') }}" required>
                                @error('ten_chuc_vu')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Lưu</button>
                            <a href="{{ route('chuc-vu.index') }}" class="btn btn-secondary">Hủy</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection