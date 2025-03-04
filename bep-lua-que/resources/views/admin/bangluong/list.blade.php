@extends('layouts.admin')

@section('title')
    Bảng lương
@endsection

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
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Bảng lương</a></li>
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
                        <h4 class="card-title">Bảng lương</h4>

                        <div class="btn-group">
                            <a href="{{ route('luong.create') }}" class="btn btn-sm btn-primary">
                                <i class="fa fa-plus"></i> Tính lương
                            </a>
                            <!-- Nút Nhập file sẽ hiển thị Modal -->


                            <a href="{{ route('dich-vu.export') }}" class="btn btn-sm btn-success">
                                <i class="fa fa-download"></i> Xuất file
                            </a>
                            {{-- <select class="btn btn-sm btn-info" id="filterPeriod">
                                <option value="ngay">Theo ngày</option>
                                <option value="tuan">Theo tuần</option>
                                <option value="thang">Theo tháng</option>
                                <option value="nam">Theo năm</option>
                            </select> --}}
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
                                        <th>Mã</th>
                                        <th>Tên</th>
                                        <th>Kỳ hạn trả</th>
                                        <th>Kỳ làm việc</th>
                                        <th>Tổng lương</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody id="{{ $tableId }}">
                                    @include('admin.bangluong.body-list')
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
                    <form action="{{ route('dich-vu.import') }}" method="POST" enctype="multipart/form-data"
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
    </div>

    @include('admin.search-srcip')
    <!-- Hiển thị phân trang -->
    {{ $data->links('pagination::bootstrap-5') }}
@endsection
<script>
    $(document).ready(function() {
        $('#filterPeriod').on('change', function() {
            let filterValue = $(this).val(); // Lấy giá trị đã chọn

            $.ajax({
                url: "{{ route('bang-luong.filter') }}", // Route xử lý request
                type: "GET",
                data: {
                    filter: filterValue
                },
                success: function(data) {
                    $('#dataTable').html(data); // Cập nhật bảng dữ liệu
                }
            });
        });
    });
</script>
