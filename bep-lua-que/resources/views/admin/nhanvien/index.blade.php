@extends('layouts.admin')

@section('title')
    Quản lí nhân viên
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Chào mừng trở lại!</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Quản lí nhân viên</a></li>
                </ol>
            </div>
        </div>
        <!-- row -->
        <div class="row">
            @include('admin.filter')
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Danh sách nhân viên</h4>

                        <div class="btn-group">
                            <a href="{{ route('nhan-vien.create') }}" class="btn btn-sm btn-primary">
                                <i class="fa fa-plus"></i> Thêm nhân viên
                            </a>
                            <a href="#" class="btn btn-sm btn-secondary" data-toggle="modal"
                                data-target=".bd-example-modal-lg">
                                <i class="fa fa-upload"></i> Nhập file
                            </a>
                            <a href="" class="btn btn-sm btn-success">
                                <i class="fa fa-download"></i> Xuất file
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-responsive-md">
                                <thead>
                                    <tr>
                                        <th style="width:50px;">
                                            <div class="custom-control custom-checkbox checkbox-success check-lg mr-3">
                                                <input type="checkbox" class="custom-control-input" id="checkAll"
                                                    required="">
                                                <label class="custom-control-label" for="checkAll"></label>
                                            </div>
                                        </th>
                                        <th><strong>Mã NV</strong></th>
                                        <th><strong>Họ tên</strong></th>
                                        <th><strong>Email</strong></th>
                                        <th><strong>SĐT</strong></th>
                                        <th><strong>Chức vụ</strong></th>
                                        <th><strong>Trạng thái</strong></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($nhanViens as $nhanVien)
                                        <tr>
                                            <td>
                                                <div class="custom-control custom-checkbox checkbox-success">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="checkbox{{ $nhanVien->id }}">
                                                    <label class="custom-control-label"
                                                        for="checkbox{{ $nhanVien->id }}"></label>
                                                </div>
                                            </td>
                                            <td>{{ $nhanVien->ma_nhan_vien }}</td>
                                            <td>{{ $nhanVien->ho_ten }}</td>
                                            <td>{{ $nhanVien->email }}</td>
                                            <td>{{ $nhanVien->so_dien_thoai }}</td>
                                            <td>{{ $nhanVien->chucVu->ten_chuc_vu }}</td>
                                            <td>
                                                <!-- Hiển thị trạng thái -->
                                                @switch($nhanVien->trang_thai)
                                                    @case('dang_lam_viec')
                                                        <span class="badge bg-success">Đang làm việc</span>
                                                    @break

                                                    @case('nghi_viec')
                                                        <span class="badge bg-danger">Nghỉ việc</span>
                                                    @break
                                                @endswitch
                                            </td>
                                            <td>
                                                <a href="{{ route('nhan-vien.detail', $nhanVien->id) }}"
                                                    class="btn btn-primary btn-sm"> <i class="fa fa-eye"></i></a>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nhập file -->
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="importFileModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importFileModalLabel">Nhập file</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"
                        aria-label="Close"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <!-- Form nhập file -->
                    <form action="" method="POST" enctype="multipart/form-data" id="importFileForm">
                        @csrf
                        <div class="mb-3">
                            <label for="fileUpload" class="form-label">Chọn file</label>
                            <input type="file" name="file" id="fileUpload" class="form-control" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" form="importFileForm" class="btn btn-primary">Xác nhận</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hiển thị phân trang -->
    {{ $nhanViens->links('pagination::bootstrap-5') }}
@endsection
