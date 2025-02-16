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
                        <h4 class="card-title">Danh sách nhập kho</h4>

                        <div class="btn-group">
                            <a href="{{ route('phieu-nhap-kho.create') }}" class="btn btn-sm btn-primary">
                                <i class="fa fa-plus"></i> Thêm phiếu nhập
                            </a>
                            <a href="{{ route('phieu-nhap-kho.export') }}" class="btn btn-sm btn-success">
                                <i class="fa fa-upload"></i> Xuất file
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
                                    @include('admin.phieunhap.body-list')
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hiển thị phân trang -->
        <div class="mt-3">
            {{ $data->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <!-- AJAX để cập nhật danh sách -->
    <script>
        document.getElementById("search-phieu-nhap").addEventListener("keyup", function() {
            let keyword = this.value;
            fetch("{{ route('phieu-nhap-kho.index') }}?ma_phieu_nhap=" + keyword, {
                headers: { "X-Requested-With": "XMLHttpRequest" }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById("list-container").innerHTML = data.html;
            });
        });
    </script>
@endsection
