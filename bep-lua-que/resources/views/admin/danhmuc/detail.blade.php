@extends('layouts.admin')

@section('title')
    Chi tiết danh mục
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Chi tiết danh mục</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Chi tiết danh mục</a></li>
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
                                <label for="name">Tên danh mục</label>
                                <input type="text" id="name" name="ten" class="form-control"
                                    placeholder="Nhập tên danh mục" value="{{ $danhMucMonAn->ten }}" readonly>
                            </div>

                            <!-- Mô tả -->
                            <div class="form-group">
                                <label for="description">Mô tả</label>
                                <div style=" background: transparent;">
                                    {!! $danhMucMonAn->mo_ta !!}
                                </div>
                            </div>


                            <!-- Hình ảnh -->
                            <div class="form-group">
                                <label for="image">Hình ảnh</label>
                                <div class="image-container">
                                    @if ($danhMucMonAn->hinh_anh)
                                        <img src="{{ asset('storage/' . $danhMucMonAn->hinh_anh) }}"
                                            alt="{{ $danhMucMonAn->ten }}" class="img-fluid rounded border"
                                            style="max-width: 250px; max-height: 250px;">
                                    @else
                                        <p>Mục này chưa có ảnh</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Trạng thái -->
                            <div class="form-group ">
                                <label for="status">Trạng thái kinh doanh</label>
                                @if ($danhMucMonAn->deleted_at != null)
                                    <input type="text" id="status" class="form-control" value="Đã ngừng kinh doanh"
                                        readonly>
                                @else
                                    <input type="text" id="status" class="form-control" value="Đang kinh doanh"
                                        readonly>
                                @endif
                            </div>

                            <!-- Nút submit -->
                            <div class="form-group text-right">
                                <a href="{{ route('danh-muc-mon-an.index') }}" class="btn btn-primary btn-sm"><i
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
