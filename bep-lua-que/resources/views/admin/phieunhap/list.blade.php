@extends('layouts.admin')

@section('title')
    Lịch sử nhập kho
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Quản lý lịch sử nhập kho</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Lịch sử nhập kho</a></li>
                </ol>
            </div>
        </div>

        <!-- Bộ lọc -->
        <div class="row">
            @include('admin.filter')
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">

                        <h4 class="card-title">Danh sách phiếu nhập kho</h4>


                        <div class="btn-group">
                            <a href="{{ route('phieu-nhap-kho.create') }}" class="btn btn-sm btn-primary">
                                <i class="fa fa-plus"></i> Thêm phiếu nhập
                            </a>
                            {{-- <a href="{{ route('phieu-nhap.import') }}" class="btn btn-sm btn-secondary" data-toggle="modal"
                                data-target=".bd-example-modal-lg">
                                <i class="fa fa-upload"></i> Nhập file
                            </a> --}}

                            <a href="{{ route('phieu-nhap.export') }}" class="btn btn-sm btn-success">
                                <i class="fa fa-download"></i> Xuất file
                            </a>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-responsive-md">
                                <thead>
                                    <tr>
                                        <th><strong>ID</strong></th>
                                        <th><strong>Mã nhập kho</strong></th>
                                        <th><strong>Nhân viên nhập</strong></th>
                                        <th><strong>Nhà cung cấp</strong></th>
                                        <th><strong>Ngày nhập</strong></th>
                                        <th><strong>Trạng thái</strong></th>
                                        <th><strong>Hành động</strong></th>
                                    </tr>
                                </thead>
                                <tbody id="list-container">

                                    @include('admin.phieunhap.body-list', ['data' => $data])

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Nhập file -->
        {{-- <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="importFileModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importFileModalLabel">Nhập file</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"
                            aria-label="Close"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <!-- Form nhập file -->
                        <form action="{{ route('phieu-nhap.import') }}" method="POST" enctype="multipart/form-data"
                            id="importFileForm">
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
        </div> --}}



        <!-- Hiển thị phân trang -->
        <div class="mt-3">
            {{ $data->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
