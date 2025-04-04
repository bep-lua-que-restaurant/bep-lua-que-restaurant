@extends('layouts.admin')

@section('title')
    Danh mục món ăn
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
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Danh mục món ăn</a></li>
                </ol>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Danh sách</h4>

                        <div class="btn-group">
                            <a href="{{ route('danh-muc-mon-an.create') }}" class="btn btn-sm btn-primary">
                                <i class="fa fa-plus"></i> Thêm mới
                            </a>
                            <!-- Nút Nhập file sẽ hiển thị Modal -->
                            <a href="#" class="btn btn-sm btn-secondary" data-toggle="modal"
                                data-target=".bd-example-modal-lg">
                                <i class="fa fa-download"></i> Nhập file
                            </a>

                            <a href="{{ route('danh-muc-mon-an.export') }}" class="btn btn-sm btn-success">
                                <i class="fa fa-upload"></i> Xuất file
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
                                        <th><strong>Tên</strong></th>
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
                    <form action="{{ route('danh-muc-mon-an.import') }}" method="POST" enctype="multipart/form-data"
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
    <!-- Script -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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
                columns: [

                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'ten',
                        name: 'ten'
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
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/vi.json' // Ngôn ngữ tiếng Việt
                },
                // Tùy chỉnh phân trang
                pagingType: 'full_numbers', // Hiển thị đầy đủ: First, Previous, số trang, Next, Last
                renderer: 'bootstrap', // Dùng kiểu Bootstrap cho phân trang
                lengthMenu: [5, 10, 25, 50], // Tùy chọn số dòng mỗi trang
                pageLength: 10 // Số dòng mặc định mỗi trang
            });

            // Checkbox "Chọn tất cả"
            $('#checkAll').on('click', function() {
                $('input[name="ids[]"]').prop('checked', this.checked);
            });

            // Xử lý submit form với SweetAlert2
            $(document).on('submit', 'form', function(e) {
                e.preventDefault();
                var form = $(this);
                var isDelete = form.find('button[title="Xóa"]').length > 0;

                Swal.fire({
                    title: isDelete ? 'Bạn muốn ngừng kinh doanh mục này chứ?' :
                        'Bạn có chắc muốn khôi phục mục này không?',
                    text: "Hành động này có thể thay đổi trạng thái của danh mục!",
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
                                    isDelete ?
                                    'Danh mục đã được ngừng kinh doanh.' :
                                    'Danh mục đã được khôi phục.',
                                    'success'
                                );
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Lỗi!',
                                    'Có lỗi xảy ra, vui lòng thử lại!',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>

    <!-- Thêm CSS tùy chỉnh cho phân trang -->
    <style>
        .dataTables_paginate .pagination {
            justify-content: center;
            /* Căn giữa phân trang */
        }

        .dataTables_paginate .page-item.active .page-link {
            background-color: #007bff;
            /* Màu xanh cho trang hiện tại */
            border-color: #007bff;
            color: white;
        }

        .dataTables_paginate .page-link {
            color: #007bff;
            /* Màu chữ nút phân trang */
            border-radius: 5px;
            margin: 0 5px;
        }

        .dataTables_paginate .page-link:hover {
            background-color: #e9ecef;
            /* Hiệu ứng hover */
        }
    </style>
@endsection
