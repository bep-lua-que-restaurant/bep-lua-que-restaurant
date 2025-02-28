@extends('layouts.admin')

@section('title')
    Chi tiết danh mục
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Chi tiết bảng lương</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Chi tiết bảng lương</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div>
                            @csrf
                            <!-- Bảng thông tin nhân viên -->
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên nhân viên</th>
                                        <th>Số ca làm</th>
                                        <th>Số ngày công</th>
                                        <th>Lương chính (VND)</th>
                                        <th>Tổng lương (VND)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @if ($bangTinhLuong->isNotEmpty())
                                        @foreach ($bangTinhLuong as $index => $chamCong)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td> <!-- Laravel tự động đếm -->
                                                <td>{{ $chamCong->ten_nhan_vien ?? 'Không có tên' }}</td>
                                                <td>{{ $chamCong->ten_ca ?? 'Chưa có ca' }}</td>
                                                <td>{{ $chamCong->ngay_cham_cong ?? 'Chưa cập nhật' }}</td>
                                                <td>{{ number_format(optional($chamCong->luong)->muc_luong ?? 0, 0, ',', '.') }}
                                                </td>
                                                <td>{{ number_format(optional($chamCong->tong_luong ?? 0), 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center">Không có dữ liệu</td>
                                        </tr>
                                    @endif --}}



                                </tbody>
                            </table>

                            <!-- Nút submit -->
                            <div class="form-group text-right">
                                <a href="{{ route('luong.index') }}" class="btn btn-primary btn-sm"><i
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
