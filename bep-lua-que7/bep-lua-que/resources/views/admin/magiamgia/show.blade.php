@extends('layouts.admin')

@section('title')
    Chi tiết mã giảm giá
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Chi tiết </h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Chi tiết Mã giảm </a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div>
                            @csrf

                            <div class="card-body">
                                <p><strong>ID:</strong> {{ $maGiamGia->id }}</p>
                                <p><strong>Mã:</strong> {{ $maGiamGia->code }}</p>
                                <p><strong>Loại:</strong> {{ $maGiamGia->type }}</p>
                                <p><strong>Giá Trị:</strong> {{ $maGiamGia->value }}</p>
                                <p><strong>Đơn Hàng Tối Thiểu:</strong> {{ $maGiamGia->min_order_value ?? 'Không yêu cầu' }}
                                </p>
                                <p><strong>Hiệu Lực:</strong> {{ $maGiamGia->start_date }} đến {{ $maGiamGia->end_date }}
                                </p>
                                <p><strong>Số Lượt Sử Dụng:</strong> {{ $maGiamGia->used }} /
                                    {{ $maGiamGia->usage_limit == 0 ? 'Không giới hạn' : $maGiamGia->usage_limit }}</p>
                                <a href="{{ route('ma-giam-gia.edit', $maGiamGia->id) }}" class="btn btn-custom">Sửa</a>
                                <a href="{{ route('ma-giam-gia.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
                            </div>

                            <!-- Trạng thái -->
                            {{-- <div class="form-group ">
                                <label for="status">Trạng thái kinh doanh</label>
                                @if ($maGiamGia->deleted_at != null)
                                    <input type="text" id="status" class="form-control" value="Đã ngừng kinh doanh"
                                        readonly>
                                @else
                                    <input type="text" id="status" class="form-control" value="Đang kinh doanh"
                                        readonly>
                                @endif
                            </div> --}}

                            <!-- Nút submit -->
                            <div class="form-group text-right">
                                <a href="{{ route('ma-giam-gia.index') }}" class="btn btn-primary btn-sm"><i
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