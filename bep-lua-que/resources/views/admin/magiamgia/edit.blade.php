@extends('layouts.admin')

@section('title')
    Sửa mã giảm giá
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4> Sửa mã giảm giá</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Sửa mã giảm giá</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('ma-giam-gia.update', $maGiamGia->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Mã Giảm Giá -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="code">Mã Giảm Giá:</label>
                                        <input type="text" class="form-control" name="code"
                                            value="{{ old('code', $maGiamGia->code) }}">
                                    </div>
                                    @error('code')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Loại Giảm Giá -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="type">Loại Giảm Giá:</label>
                                        <select name="type" class="form-control">
                                            <option value="percentage"
                                                {{ old('type', $maGiamGia->type) == 'percentage' ? 'selected' : '' }}>Phần
                                                trăm</option>
                                            <option value="fixed"
                                                {{ old('type', $maGiamGia->type) == 'fixed' ? 'selected' : '' }}>Số tiền
                                            </option>
                                        </select>
                                    </div>
                                    @error('type')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- Giá Trị Giảm -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="value">Giá Trị Giảm:</label>
                                        <input type="number" step="0.01" class="form-control" name="value"
                                            value="{{ old('value', $maGiamGia->value) }}">
                                    </div>
                                    @error('value')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Đơn Hàng Tối Thiểu -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="min_order_value">Đơn Hàng Tối Thiểu:</label>
                                        <input type="number" step="0.01" class="form-control" name="min_order_value"
                                            value="{{ old('min_order_value', $maGiamGia->min_order_value) }}">
                                    </div>
                                    @error('min_order_value')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- Ngày Bắt Đầu -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_date">Ngày Bắt Đầu:</label>
                                        <input type="datetime-local" class="form-control" name="start_date"
                                            value="{{ old('start_date', \Carbon\Carbon::parse($maGiamGia->start_date)->format('Y-m-d\TH:i')) }}">
                                    </div>
                                    @error('start_date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Ngày Kết Thúc -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="end_date">Ngày Kết Thúc:</label>
                                        <input type="datetime-local" class="form-control" name="end_date"
                                            value="{{ old('end_date', \Carbon\Carbon::parse($maGiamGia->end_date)->format('Y-m-d\TH:i')) }}">
                                    </div>
                                    @error('end_date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <!-- Giới Hạn Lượt Sử Dụng -->
                            <div class="form-group">
                                <label for="usage_limit">Số lượng sử dụng</label>
                                <input type="number" class="form-control" name="usage_limit"
                                    value="{{ old('usage_limit', $maGiamGia->usage_limit) }}">
                                @error('usage_limit')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Nút Submit -->
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('ma-giam-gia.index') }}" class="btn btn-secondary">
                                    <i class="fa fa-arrow-left"></i> Quay lại
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-save"></i> Cập Nhật Mã Giảm Giá
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
