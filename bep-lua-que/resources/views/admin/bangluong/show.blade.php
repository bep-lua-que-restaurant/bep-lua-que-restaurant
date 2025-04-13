@extends('layouts.admin')

@section('title')
    Chi tiết bảng lương
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
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
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
                                        <th class="text-center">STT</th>
                                        <th class="text-center">Tên nhân viên</th>
                                        <th class="text-center">Số ca làm</th>
                                        {{-- <th>Số ngày công</th> --}}
                                        <th class="text-center">Lương chính (VND)</th>
                                        <th class="text-center">Tổng lương (VND)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($bangTinhLuong->isNotEmpty())
                                        @foreach ($bangTinhLuong->groupBy('ten_nhan_vien') as $tenNhanVien => $nhomLuong)
                                            @php
                                                $luong = $nhomLuong->first();
                                                $soCaLam = $luong->so_ca_lam ?? 0;
                                                $soNgayCong = intdiv($soCaLam, 3); // Số ngày công trọn vẹn
                                                // $caDu = $soCaLam % 3; // Số ca dư chưa đủ thành 1 ngày
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-center">{{ $luong->ten_nhan_vien ?? 'Không có tên' }}</td>
                                                <td class="text-center">{{ $soCaLam > 0 ? $soCaLam . ' ca' : '-' }}</td>
                                                <!-- Hiển thị số ca dư -->
                                                {{-- <td>{{ $soNgayCong > 0 ? sprintf('%02d', $soNgayCong) . ' ngày' : '-' }} --}}
                                                </td> <!-- Hiển thị số ngày công -->
                                                <td class="text-center">
                                                    {{ isset($luong->muc_luong) ? number_format($luong->muc_luong) : '0' }}
                                                </td>
                                                <td class="text-center">
                                                    {{ number_format($luong->tong_luong ?? 0, 0, ',', '.') }} VND</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center">Không có dữ liệu</td>
                                        </tr>
                                    @endif
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
