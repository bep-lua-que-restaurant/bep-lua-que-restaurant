@extends('layouts.admin')

@section('title', 'Chi Tiết Nguyên Liệu')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Chi Tiết Nguyên Liệu: {{ $chiTiet->nguyenLieu->ten_nguyen_lieu }}</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Mã Nguyên Liệu</th>
                                    <td>{{ $chiTiet->nguyenLieu->ma_nguyen_lieu }}</td>
                                </tr>
                                <tr>
                                    <th>Tên Nguyên Liệu</th>
                                    <td>{{ $chiTiet->nguyenLieu->ten_nguyen_lieu }}</td>
                                </tr>
                                <tr>
                                    <th>Loại Nguyên Liệu</th>
                                    <td>{{ $chiTiet->nguyenLieu->loaiNguyenLieu->ten_loai ?? 'Không xác định' }}</td>
                                </tr>
                                <tr>
                                    <th>Số Lượng Nhập</th>
                                    <td>{{ $chiTiet->so_luong }}</td>
                                </tr>
                                <tr>
                                    <th>Đơn vị nhập</th>
                                    <td>{{ $chiTiet->don_vi_nhap }}</td>
                                </tr>
                                <tr>
                                    <th>Giá Nhập</th>
                                    <td>{{ number_format($chiTiet->don_gia, 0, ',', '.') }} VNĐ</td>
                                </tr>
                                <tr>
                                    <th>Thành Tiền</th>
                                    <td>{{ number_format($chiTiet->tong_tien, 0, ',', '.') }} VNĐ</td>
                                </tr>
                                <tr>
                                    <th>Số Lượng Tồn</th>
                                    <td>{{ $soLuongTon }} {{ $chiTiet->nguyenLieu->don_vi_tinh }}</td>
                                </tr>
                                <tr>
                                    <th>Hình Ảnh Nguyên Liệu</th>
                                    <td>
                                        <img src="{{ asset('storage/' . $chiTiet->nguyenLieu->hinh_anh) }}" 
                                            alt="Hình Ảnh Nguyên Liệu" width="150">
                                    </td>
                                </tr>
                                <tr>
                                    <th>Trạng Thái</th>
                                    <td>
                                        <span class="badge 
                                            @if($chiTiet->trang_thai == 'Đạt') bg-success 
                                            @elseif($chiTiet->trang_thai == 'Không đạt') bg-danger 
                                            @else bg-warning 
                                            @endif">
                                            {{ $chiTiet->trang_thai }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Cập nhật trạng thái</th>
                                    <td>
                                        <form action="{{ route('phieu-nhap-kho.capnhaptrangthai', ['phieuNhapId' => $phieuNhapId, 'nguyenLieuId' => $chiTiet->nguyen_lieu_id]) }}" method="POST">
                                            @csrf
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <!-- Các trường thông tin nguyên liệu -->
                                                    <tr>
                                                        <th>Trạng Thái</th>
                                                        <td>
                                                            <select name="trang_thai" class="form-control">
                                                                @if($chiTiet->trang_thai == 'Cần kiểm tra')
                                                                    <option value="Cần kiểm tra" selected>Cần kiểm tra</option>
                                                                    <option value="Đạt">Đạt</option>
                                                                    <option value="Không đạt">Không đạt</option>
                                                                @else
                                                                    <option value="Đạt" @if($chiTiet->trang_thai == 'Đạt') selected @endif>Đạt</option>
                                                                    <option value="Không đạt" @if($chiTiet->trang_thai == 'Không đạt') selected @endif>Không đạt</option>
                                                                @endif
                                                            </select>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i> Cập nhật trạng thái
                                            </button>
                                        </form>
                                        
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="mt-4">
                            <a href="{{ route('phieu-nhap-kho.show', ['phieu_nhap_kho' => $phieuNhapId]) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay Lại
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
