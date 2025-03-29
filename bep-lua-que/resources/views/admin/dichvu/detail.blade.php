@extends('layouts.admin')

@section('title')
    Chi tiết danh mục
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Chi tiết dịch vụ</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Chi tiết dịch vụ</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div>
                            @csrf

                            <!-- Tên danh mục -->
                            <div class="form-group">
                                <label for="name">Tên dịch vụ</label>
                                <input type="text" id="name" name="ten_dich_vu" class="form-control"
                                    placeholder="Nhập tên danh mục" value="{{ $dichVu->ten_dich_vu }}" readonly>
                            </div>

                            <!-- Mô tả -->
                            <div class="form-group">
                                <label for="description">Mô tả</label>
                                <div style=" background: transparent;">
                                    {!! $dichVu->mo_ta !!}
                                </div>
                            </div>


                            <!-- Trạng thái -->
                            <div class="form-group ">
                                <label for="status">Trạng thái kinh doanh</label>
                                @if ($dichVu->deleted_at != null)
                                    <input type="text" id="status" class="form-control" value="Đã ngừng kinh doanh"
                                        readonly>
                                @else
                                    <input type="text" id="status" class="form-control" value="Đang kinh doanh"
                                        readonly>
                                @endif
                            </div>

                            <!-- Nút submit -->
                            <div class="form-group text-right">
                                <a href="{{ route('dich-vu.index') }}" class="btn btn-primary btn-sm"><i
                                        class="fa fa-arrow-left"></i>
                                    Quay lại</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
