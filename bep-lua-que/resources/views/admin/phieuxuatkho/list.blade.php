@extends('layouts.admin')

@section('title')
    Phiếu xuất kho
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
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Phiếu xuất kho</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Danh sách Phiếu xuất kho</h4>

                        <div class="btn-group">
                            <a href="{{ route('phieu-xuat-kho.create') }}" class="btn btn-sm btn-primary">
                                <i class="fa fa-plus"></i> Thêm mới
                            </a>
                            <a href="#" class="btn btn-sm btn-info">
                                <i class="fa fa-list"></i> Danh sách
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-responsive-md" id="{{ $tableId }}">
                                <thead>
                                    <tr>
                                        <th><strong>ID</strong></th>
                                        <th><strong>Mã phiếu</strong></th>
                                        <th><strong>Người nhận</strong></th>
                                        <th><strong>Nhà cung cấp</strong></th>
                                        <th><strong>Ngày xuất</strong></th>
                                        <th><strong>Loại phiếu</strong></th>
                                        <th><strong>Trạng thái</strong></th>
                                        <th><strong>Hành động</strong></th>
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
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('#{{ $tableId }}').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ $route }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'ma_phieu',
                        name: 'ma_phieu'
                    },
                    {
                        data: 'nguoi_nhan',
                        name: 'nguoi_nhan'
                    },
                    {
                        data: 'nha_cung_cap',
                        name: 'nha_cung_cap'
                    },
                    {
                        data: 'ngay_xuat',
                        name: 'ngay_xuat'
                    },
                    {
                        data: 'loai_phieu',
                        name: 'loai_phieu',
                        render: function(data, type, row) {
                            switch (data) {
                                case 'xuat_bep':
                                    return 'Xuất Bếp';
                                case 'xuat_tra_hang':
                                    return 'Trả Nhà Cung Cấp';
                                case 'xuat_huy':
                                    return 'Hủy Hàng';
                                default:
                                    return 'Không xác định';
                            }
                        }
                    },
                    {
                        data: 'trang_thai',
                        name: 'deleted_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/vi.json'
                },
                pagingType: 'full_numbers',
                renderer: 'bootstrap',
                lengthMenu: [5, 10, 25, 50],
                pageLength: 10
            });

            $(document).on('submit', 'form', function(e) {
                e.preventDefault();
                var form = $(this);
                var isDelete = form.find('button[title="Xóa"]').length > 0;

                Swal.fire({
                    title: isDelete ? 'Bạn muốn ngừng sử dụng phiếu xuất kho này?' :
                        'Bạn muốn khôi phục phiếu xuất kho này?',
                    text: "Hành động này sẽ thay đổi trạng thái của phiếu xuất kho!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Xác nhận',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: form.attr('action'),
                            method: form.attr('method'),
                            data: form.serialize(),
                            success: function(response) {
                                Swal.fire(
                                    'Thành công!',
                                    isDelete ? 'Đã ngừng sử dụng phiếu xuất kho.' :
                                    'Đã khôi phục phiếu xuất kho.',
                                    'success'
                                );
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                let message = 'Có lỗi xảy ra, vui lòng thử lại!';

                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    message = xhr.responseJSON.message;
                                }

                                Swal.fire('Lỗi!', message, 'error');
                            }

                        });
                    }
                });
            });
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
    </style>
@endsection
