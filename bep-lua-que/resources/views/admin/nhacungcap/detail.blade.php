@extends('layouts.admin')

@section('title')
    Chi tiết nhà cung cấp
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Chi tiết nhà cung cấp</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Chi tiết nhà cung cấp</a></li>
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
                                <label for="name">Tên nhà cung cấp</label>
                                <input type="text" id="ten_nha_cung_cap" name="ten_nha_cung_cap" class="form-control"
                                    value="{{ $nhaCungCap->ten_nha_cung_cap }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="dia_chi">Địa chỉ</label>
                                <input type="text" id="dia_chi" name="dia_chi" class="form-control"
                                    value="{{ $nhaCungCap->dia_chi }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="so_dien_thoai">Số điện thoại</label>
                                <input type="tel" id="so_dien_thoai" name="so_dien_thoai" class="form-control"
                                    value="{{ $nhaCungCap->so_dien_thoai }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" class="form-control"
                                    value="{{ $nhaCungCap->email }}" readonly>
                            </div>

                            <!-- Mô tả -->
                            <div class="form-group">
                                <label for="description">Mô tả</label>
                                <div style=" background: transparent;">
                                    {!! $nhaCungCap->moTa !!}
                                </div>
                            </div>


                            <!-- Hình ảnh -->
                            <div class="form-group">
                                <label for="image">Hình ảnh</label>
                                <div class="image-container">
                                    @if ($nhaCungCap->hinhAnh)
                                        <img src="{{ asset('storage/' . $nhaCungCap->hinhAnh) }}"
                                            alt="{{ $nhaCungCap->ten }}" class="img-fluid rounded border"
                                            style="max-width: 250px; max-height: 250px;">
                                    @else
                                        <p>Mục này chưa có ảnh</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Trạng thái -->
                            <div class="form-group ">
                                <label for="status">Trạng thái kinh doanh</label>
                                @if ($nhaCungCap->deleted_at != null)
                                    <input type="text" id="status" class="form-control" value="Đã ngừng kinh doanh"
                                        readonly>
                                @else
                                    <input type="text" id="status" class="form-control" value="Đang kinh doanh"
                                        readonly>
                                @endif
                            </div>

                            <!-- Nút submit -->
                            <div class="form-group text-right">
                                <a href="{{ route('nha-cung-cap.index') }}" class="btn btn-primary btn-sm"><i
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
