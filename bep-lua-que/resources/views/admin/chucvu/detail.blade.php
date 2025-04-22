@extends('layouts.admin')

@section('title')
    Chi tiết chức vụ
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Chi tiết chức vụ</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('chuc-vu.index') }}">Chức vụ</a></li>
                    <li class="breadcrumb-item active">Chi tiết</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Thông tin chức vụ</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">ID:</label>
                            <p>{{ $chucVu->id }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tên chức vụ:</label>
                            <p>{{ $chucVu->ten_chuc_vu }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Trạng thái:</label>
                            <p>
                                @if ($chucVu->deleted_at)
                                    <i class="fa fa-circle text-danger mr-1"></i> Đã ngừng hoạt động
                                @else
                                    <i class="fa fa-circle text-success mr-1"></i> Đang hoạt động
                                @endif
                            </p>
                        </div>
                        <a href="{{ route('chuc-vu.index') }}" class="btn btn-secondary">Quay lại</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection