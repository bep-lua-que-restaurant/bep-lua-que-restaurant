@extends('layouts.admin')

@section('title', 'Hóa đơn')

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Hi, welcome back!</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Hóa đơn</a></li>
                </ol>
            </div>
        </div>

        <!-- Form tìm kiếm -->
        <div class="row">
            <div class="col-lg-12">
                <form method="GET" action="{{ route('hoa-don.index') }}">
                    <div class="input-group mb-3">
                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm hóa đơn..."
                            value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Danh sách hóa đơn -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Danh sách hóa đơn</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-responsive-md">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Mã Hóa Đơn</th>
                                        {{-- <th>Khách Hàng</th> --}}
                                        <th>Tổng Tiền</th>
                                        <th>Phương Thức Thanh Toán</th>
                                        {{-- <th>Mô Tả</th> --}}
                                        <th>Ngày Tạo</th>
                                        <th>Hành động </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($hoa_don as $hoa_dons)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $hoa_dons->ma_hoa_don }}</td>
                                            {{-- <td>{{ $hoa_dons->khach_hang_id }}</td> --}}
                                            <td>{{ number_format($hoa_dons->tong_tien, 0, ',', '.') }} đ</td>
                                            @php
                                                $paymentMethods = [
                                                    'tien_mat' => 'Tiền mặt',
                                                    'the' => 'Thẻ',
                                                    'tai_khoan' => 'Tài khoản',
                                                ];
                                            @endphp
                                            <td>{{ $paymentMethods[$hoa_dons->phuong_thuc_thanh_toan] ?? 'Không xác định' }}
                                            </td>

                                            {{-- <td>{{ $hoa_dons->mo_ta }}</td> --}}
                                            <td>{{ $hoa_dons->created_at->format('d/m/Y H:i') }}</td>
                                            <td> <a href="{{ route('hoa-don.show', $hoa_dons->id) }}"
                                                    class="btn btn-primary btn-sm">
                                                    Xem chi tiết
                                                </a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Hiển thị phân trang -->
                        {{ $hoa_don->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection
