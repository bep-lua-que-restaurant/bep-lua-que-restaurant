@extends('layouts.admin')

@section('title')
    Bảng lương
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Chào mừng đến Bếp lửa quê !</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Bảng lương</a></li>
                </ol>
            </div>
        </div>
        <!-- row -->
        {{-- <div class="row">
            @include('admin.filter')
        </div> --}}

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Bảng lương</h4>

                        <div class="btn-group">
                            <a href="{{ route('luong.create') }}" class="btn btn-sm btn-primary">
                                <i class="fa fa-plus"></i> Tính lương
                            </a>
                            <a href="{{ route('bang-luong.import') }}" class="btn btn-sm btn-secondary"
                               data-bs-toggle="modal" data-bs-target="#importFileModal">
                                <i class="fa fa-upload"></i> Nhập file
                            </a>
                            <a id="exportLink" href="{{ route('bang-luong.export') }}" class="btn btn-sm btn-success">
                                <i class="fa fa-download"></i> Xuất file
                            </a>


                            <select id="monthSelect" class="btn btn-sm btn-primary">
                                @for ($m = 1; $m <= 12; $m++)
                                    @php
                                        $year = now()->year;
                                    @endphp
                                    <option value="{{ $m }}" {{ now()->month == $m ? 'selected' : '' }}>
                                        Tháng {{ $m }} - {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-responsive-md">
                                <thead>
                                    <tr style="text-align: center">
                                        <th style="width:50px;">
                                            <div class="custom-control custom-checkbox checkbox-success check-lg mr-3">
                                                <input type="checkbox" class="custom-control-input" id="checkAll"
                                                    required="">
                                                <label class="custom-control-label" for="checkAll"></label>
                                            </div>
                                        </th>
                                        <th>Mã</th>
                                        <th>Tên</th>
                                        <th>Tổng số ca làm </th>
                                        <th>Mức lương</th>
                                        <th>Tổng lương</th>
                                        <th>Thời gian</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody id="bangluong">
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
                    <form action="{{ route('bang-luong.import') }}" method="POST" enctype="multipart/form-data"
                        id="importFileForm">
                        @csrf
                        <div class="mb-3">
                            <label for="fileUpload" class="form-label">Chọn file</label>
                            <input style="height: auto" type="file" name="file" id="fileUpload" class="form-control" required>
                            <input type="hidden" name="month" id="selectedMonth">
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


    <script>
        $(document).ready(function() {
            $('#monthSelect').change(function() {
                var selectedMonth = $(this).val();

                $.ajax({
                    url: "{{ route('luong.index') }}",
                    type: "GET",
                    data: {
                        month: selectedMonth
                    },
                    success: function(response) {
                        $('#bangluong').html(response);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });
        const monthSelect = document.getElementById('monthSelect');
        const exportLink = document.getElementById('exportLink');

        // Khi chọn tháng, update link export
        monthSelect.addEventListener('change', function() {
            const selectedMonth = this.value;
            const baseUrl = "{{ route('bang-luong.export') }}"; // Link export
            exportLink.href = baseUrl + '?month=' + selectedMonth;
        });

        // Ngay khi load trang cũng set đúng tháng hiện tại
        document.addEventListener('DOMContentLoaded', function() {
            const selectedMonth = monthSelect.value;
            const baseUrl = "{{ route('bang-luong.export') }}";
            exportLink.href = baseUrl + '?month=' + selectedMonth;
        });
        // Lắng nghe sự kiện thay đổi chọn tháng
        $('#monthSelect').change(function() {
            var selectedMonth = $(this).val();

            // Cập nhật giá trị của tháng đã chọn vào trường ẩn trong form
            $('#selectedMonth').val(selectedMonth);
        });
        // Đảm bảo rằng tháng hiện tại sẽ được gửi khi load trang lần đầu
        document.addEventListener('DOMContentLoaded', function() {
            const selectedMonth = $('#monthSelect').val();
            $('#selectedMonth').val(selectedMonth);
        });
    </script>
@endsection
