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
                            <a style="padding: 7px 10px 0 10px" href="{{ route('phieu-xuat-kho.create') }}" class="btn btn-sm btn-primary">
                                <i class="fa fa-plus"></i> Thêm mới
                            </a>
                            <a style="font-size: 14px" href="{{ route('phieu-xuat-kho.export') }}" class="btn btn-success">
                                <i class="fa fa-file-excel"></i> Xuất file
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-responsive-md" id="{{ $tableId }}">
                                <thead>
                                    <tr>
                                        <th><strong>STT</strong></th>
                                        <th><strong>Mã phiếu</strong></th>
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
                        name: 'trang_thai'
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
                renderer: 'bootstrap',
                lengthMenu: [5, 10, 25, 50],
                pageLength: 10
            });

            // Tính năng Row Details khi click vào dòng
            $('#{{ $tableId }}').on('click', 'tbody tr', function() {
                var tr = $(this);
                var row = table.row(tr);

                if (row.child.isShown()) {
                    // Nếu dòng con đang mở, đóng lại
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Mở dòng con để hiển thị chi tiết
                    row.child(format(row.data())).show();
                    tr.addClass('shown');
                }
            });

            // Định dạng chi tiết của phiếu xuất kho
            function format(d) {
                return `

                          <div class="container">
                        <h5>Thông tin phiếu xuất kho</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th>Người nhận:</th>
                                <td>${d.nguoi_nhan}</td>
                            </tr>
                             <tr>
                                <th>Nhân viên:</th>
                               <td>${d.nhanvien}</td>
                            </tr>

                            <tr>
                                <th>Nhà cung cấp:</th>
                               <td>${d.nhaCungCap}</td>
                            </tr>
                            <tr>
                                <th>Ngày xuất:</th>
                                <td>${d.ngay_xuat}</td>
                            </tr>

                            <tr>
                                <th>Trạng thái:</th>
                                <td>${d.trang_thai}</td>
                            </tr>
                            <tr>
                                <th>Ghi chú:</th>
                                    <td>${d.ghi_chu ? d.ghi_chu : 'Không có'}</td>
                            </tr>
                             <tr>
                                <th>Tổng tiền:</th>
                                <td>${d.tong_tien} VNĐ</td>
                            </tr>

                        </table>
                    </div>
                `;
            }

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

        .shown td {
            background-color: #f8f9fa;
        }
    </style>
@endsection
