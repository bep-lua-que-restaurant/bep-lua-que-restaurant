@extends('layouts.admin')

@section('title')
    Chi tiết ca làm
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Chi tiết ca làm</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Chi tiết ca làm</a></li>
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
                                <label for="name">Tên ca</label>
                                <input type="text" id="name" name="ten" class="form-control"
                                    value="{{ $caLam->ten_ca }}" readonly>
                            </div>

                            <!-- Mô tả -->
                            <div class="form-group">
                                <label for="description">Mô tả</label>
                                <div style=" background: transparent;">
                                    @if ($caLam->mo_ta)
                                        {!! $caLam->mo_ta !!}
                                    @else
                                        <p class="text-danger">Mục này chưa có mô tả</p>
                                    @endif

                                </div>
                            </div>

                            <!-- Thời gian bắt đầu -->
                            <div class="form-group">
                                <label for="start_time">Thời gian bắt đầu</label>
                                <input type="text" id="start_time" class="form-control" value="{{ $caLam->gio_bat_dau }}"
                                    readonly>
                            </div>

                            <!-- Thời gian kết thúc -->

                            <div class="form-group ">
                                <label for="end_time">Thời gian kết thúc</label>
                                <input type="text" id="end_time" class="form-control" value="{{ $caLam->gio_ket_thuc }}"
                                    readonly>
                            </div>

                            <!-- Tổng thời gian làm việc -->
                            <div class="form-group">
                                <label for="total_time">Tổng thời gian làm việc</label>
                                <?php
                                // Tính toán tổng thời gian làm việc
                                $startTime = \Carbon\Carbon::parse($caLam->gio_bat_dau);
                                $endTime = \Carbon\Carbon::parse($caLam->gio_ket_thuc);
                                $duration = $startTime->diff($endTime);
                                
                                // Tổng thời gian làm việc (giờ và phút)
                                $totalHours = $duration->h;
                                $totalMinutes = $duration->i;
                                ?>
                                <input type="text" id="end_time" class="form-control"
                                    value="{{ $totalHours }} giờ {{ $totalMinutes }} phút" readonly>
                            </div>

                            <!-- Trạng thái -->
                            <div class="form-group ">
                                <label for="status">Trạng thái kinh doanh</label>
                                @if ($caLam->deleted_at != null)
                                    <input type="text" id="status" class="form-control" value="Đã ngừng kinh doanh"
                                        readonly>
                                @else
                                    <input type="text" id="status" class="form-control" value="Đang kinh doanh"
                                        readonly>
                                @endif
                            </div>

                            <!-- Nút submit -->
                            <div class="form-group text-right">
                                <a href="{{ route('ca-lam.index') }}" class="btn btn-primary btn-sm"><i
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
