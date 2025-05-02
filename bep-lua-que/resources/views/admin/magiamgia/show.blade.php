@extends('layouts.admin')

@section('title', 'Chi tiết mã giảm giá')

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Chi tiết mã giảm giá</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item active">Chi tiết Mã giảm giá</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div>
                            @csrf

                            <div class="row mb-3">
                                {{-- <div class="col-md-6">
                                    <p><strong>ID:</strong> {{ $maGiamGia->id }}</p>
                                </div> --}}
                                <div class="col-md-6">
                                    <p><strong>Mã:</strong> {{ $maGiamGia->code }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Số Lượt Sử Dụng:</strong> {{ $maGiamGia->used }}
                                        {{ $maGiamGia->usage_limit == 0 ? 'Không giới hạn' : $maGiamGia->usage_limit }}
                                    </p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p><strong>Giá Trị:</strong>
                                        <span class="discount-value">
                                            @if ($maGiamGia->type == 'percentage')
                                                {{ number_format($maGiamGia->value, 0, ',', '.') . '%' }}
                                            @else
                                                {{ number_format($maGiamGia->value, 0, ',', '.') . ' VND' }}
                                            @endif
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Đơn Hàng Tối Thiểu:</strong>
                                        <span class="min-order-value">
                                            @if ($maGiamGia->min_order_value)
                                                {{ number_format($maGiamGia->min_order_value, 0, ',', '.') }} VND
                                            @else
                                                Không yêu cầu
                                            @endif
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p><strong>Hiệu Lực:</strong>
                                        <span class="date-range">
                                            {{ \Carbon\Carbon::parse($maGiamGia->start_date)->format('d/m/Y') }} đến
                                            {{ \Carbon\Carbon::parse($maGiamGia->end_date)->format('d/m/Y') }}
                                        </span>
                                    </p>
                                </div>

                            </div>
                            <!-- Trạng thái -->
                            <div class="form-group">
                                <label for="status">Trạng thái hoạt động</label>
                                <input type="text" id="status" class="form-control"
                                    value="{{ $maGiamGia->deleted_at ? 'Đã ngừng hoạt động' : 'Đang hoạt động' }}" readonly>
                            </div>
                            <div class="mb-3">

                                <a href="{{ route('ma-giam-gia.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
                            </div>




                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
