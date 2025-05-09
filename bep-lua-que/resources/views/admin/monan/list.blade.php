@extends('layouts.admin')

@section('title')
    Món ăn
@endsection

@section('content')
    {{-- @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: '{{ session('error') }}',
                confirmButtonText: 'Đóng'
            });
        </script>
    @endif --}}

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
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Món ăn</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Danh sách Món ăn</h4>

                        <div class="btn-group">
                            <a href="{{ route('mon-an.create') }}" class="btn btn-sm btn-primary">
                                <i class="fa fa-plus"></i> Thêm mới
                            </a>
                            {{-- <a href="#" class="btn btn-sm btn-info">
                                <i class="fa fa-list"></i> Danh sách
                            </a> --}}
                            <!-- Nút Nhập file sẽ hiển thị Modal -->
                            <a href="#" class="btn btn-sm btn-secondary" data-bs-toggle="modal"
                                data-bs-target=".bd-example-modal-lg">
                                <i class="fa fa-download"></i> Nhập file
                            </a>

                            <a href="{{ route('mon-an.export') }}" class="btn btn-sm btn-success">
                                <i class="fa fa-upload"></i> Xuất file
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-responsive-md" id="{{ $tableId }}">
                                <thead>
                                    <tr>
                                        <th><strong>STT</strong></th>
                                        <th><strong>Tên món</strong></th>
                                        <th><strong>Danh mục</strong></th>
                                        <th><strong>Giá</strong></th>
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
                        <form action="{{ route('mon-an.import') }}" method="POST" enctype="multipart/form-data"
                            id="importFileForm">
                            @csrf
                            <div class="mb-3">
                                <label for="fileUpload" class="form-label">Chọn file</label>
                                <input style="height: auto" type="file" name="file" id="fileUpload" class="form-control" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" id="btn-import-confirm" class="btn btn-primary">Xác nhận</button>
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
                        data: 'ten',
                        name: 'ten'
                    },
                    {
                        data: 'danh_muc',
                        name: 'danh_muc'
                    },
                    {
                        data: 'gia',
                        name: 'gia'
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

            $(document).on('submit', 'form', function(e) {
                e.preventDefault();
                var form = $(this);
                var isDelete = form.find('button[title="Xóa"]').length > 0;

                Swal.fire({
                    title: isDelete ? 'Bạn muốn ngừng bán món ăn này?' :
                        'Bạn muốn khôi phục món ăn này?',
                    text: "Hành động này sẽ thay đổi trạng thái món ăn!",
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
                                    isDelete ? 'Đã ngừng bán món ăn.' :
                                    'Đã khôi phục món ăn.',
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
            // import
            $(document).on('click', '#btn-import-confirm', function(e) {
                e.preventDefault();

                const form = $('#importFileForm')[0];
                const formData = new FormData(form);

                $.ajax({
                    url: '{{ route('mon-an.import') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        $('#importFileModal').modal('hide');
                        Swal.fire('Thành công!', res.message || 'File đã được nhập thành công.',
                            'success');
                        $('#{{ $tableId }}').DataTable().ajax.reload();
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON?.errors;
                        let message = 'Có lỗi xảy ra khi nhập file!';
                        if (errors) {
                            message = Object.values(errors).join('<br>');
                        }
                        Swal.fire('Lỗi', message, 'error');
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
