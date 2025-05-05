@extends('layouts.admin')

@section('title')
    Phiếu nhập kho
@endsection

@section('content')
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: '{{ session('error') }}',
                confirmButtonText: 'Đóng'
            });
        </script>
    @endif

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
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Phiếu nhập kho</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Danh sách Phiếu nhập kho</h4>
                        <div class="btn-group">
                            <a href="{{ route('phieu-nhap-kho.create') }}" class="btn btn-sm btn-primary">
                                <i class="fa fa-plus"></i> Thêm mới
                            </a>
                            <a href="{{ route('phieu-nhap-kho.export') }}" class="btn btn-sm btn-success">
                                <i class="bi bi-file-earmark-excel"></i> Xuất file
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="{{ $tableId }}">
                                <thead>
                                    <tr>
                                        <th><strong>STT</strong></th>
                                        <th><strong>Mã phiếu</strong></th>
                                        <th><strong>Nhân viên</strong></th>
                                        <th><strong>Ngày nhập</strong></th>
                                        <th><strong>Trạng thái</strong></th>
                                        <th><strong>Hành động</strong></th> <!-- Cột Action -->
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            var table = $('#{{ $tableId }}').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ $route }}',
                columns: [{
                        data: 'DT_RowIndex', // ✅ Số thứ tự
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'ma_phieu',
                        name: 'ma_phieu'
                    },
                    {
                        data: 'nhanvien',
                        name: 'nhanvien'
                    },
                    {
                        data: 'ngay_nhap',
                        name: 'ngay_nhap'
                    },

                    {
                        data: 'trang_thai',
                        name: 'trang_thai',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    processing: "Đang xử lý...",
                    lengthMenu: "Hiển thị _MENU_ dòng mỗi trang",
                    zeroRecords: "Không tìm thấy dữ liệu phù hợp",
                    info: "Hiển thị _START_ đến _END_ của _TOTAL_ dòng",
                    infoEmpty: "Hiển thị 0 đến 0 của 0 dòng",
                    infoFiltered: "(lọc từ _MAX_ tổng số dòng)",
                    search: "Tìm kiếm:",
                    paginate: {
                        first: "Đầu",
                        previous: "Trước",
                        next: "Tiếp",
                        last: "Cuối"
                    },
                    aria: {
                        sortAscending: ": Sắp xếp tăng dần",
                        sortDescending: ": Sắp xếp giảm dần"
                    }
                },
                pagingType: 'full_numbers',
                lengthMenu: [5, 10, 25, 50],
                pageLength: 10
            });

            // Toggling row details (Show/Hide)
            $('#{{ $tableId }}').on('click', 'tr', function() {
                var row = table.row(this);
                var rowData = row.data();

                if (row.child.isShown()) {
                    row.child.hide();
                    $(this).removeClass('shown');
                } else {
                    row.child(formatDetails(rowData)).show();
                    $(this).addClass('shown');
                }
            });

            // Format Row Details
            function formatDetails(data) {
                console.log(data);

                const loaiPhieuText = data.loai_phieu === 'nhap_tu_bep' ? 'Nhập từ bếp' :
                    data.loai_phieu === 'nhap_tu_ncc' ? 'Nhập từ nhà cung cấp' :
                    'Không xác định';

                return `
                    <div class="row p-4 bg-light rounded" style="border-left: 5px solid #007bff; margin-bottom: 20px;">
                        <h5>Thông tin phiếu nhập kho</h5>

                        <table class="table table-bordered">
                            <tr>
                                <th>Loại phiếu:</th>

                               <td>${loaiPhieuText}</td>
                            </tr>
                            <tr>
                                <th>Nhà cung Cấp:</th>
                                <td>${data.nhaCungCap ?? '—'}</td>
                            </tr>

                            <tr>
                                <th>Ngày nhập:</th>
                                <td>${data.ngay_nhap}</td>
                            </tr>
                            <tr>
                                <th>Ghi chú:</th>
                                <td>${data.ghi_chu ?? ''} </td>
                            </tr>
                             <tr>
                                <th>Tổng tiền:</th>
                                <td>${Number(data.tong_tien ?? 0).toLocaleString('vi-VN', { maximumFractionDigits: 0 })} VND</td>
                            </tr>

                        </table>
                    </div>
                `;
            }
        });
    </script>

    <style>
        .dataTables_paginate .pagination {
            justify-content: center;
        }

        .dataTables_paginate .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
        }

        .dataTables_paginate .page-link {
            color: #007bff;
            border-radius: 5px;
            margin: 0 5px;
        }

        .dataTables_paginate .page-link:hover {
            background-color: #e9ecef;
        }

        .badge.bg-warning {
            background-color: #ffc107 !important;
            color: #000;
        }

        .badge.bg-success {
            background-color: #28a745 !important;
        }

        .badge.bg-danger {
            background-color: #dc3545 !important;
        }

        /* Styling for row details */
        .table tbody tr.shown {
            background-color: #f1f1f1;
        }

        .table tbody tr.shown td {
            padding-top: 20px;
            padding-bottom: 20px;
        }

        .table .action-buttons {
            display: flex;
            gap: 10px;
        }

        .table .action-buttons button {
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .table .action-buttons button.approve {
            background-color: #28a745;
            color: white;
        }

        .table .action-buttons button.reject {
            background-color: #dc3545;
            color: white;
        }
    </style>
@endsection
