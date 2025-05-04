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
                            <button type="button" class="btn btn-sm btn-primary btn-create">
                                <i class="fa fa-plus"></i> Thêm mới
                            </button>

                            <!-- Nút Nhập file sẽ hiển thị Modal -->
                            {{--                            <a href="#" class="btn btn-sm btn-secondary" data-toggle="modal" --}}
                            {{--                                data-target=".bd-example-modal-lg"> --}}
                            {{--                                <i class="fa fa-download"></i> Nhập file --}}
                            {{--                            </a> --}}

                            <a href="#" class="btn btn-sm btn-secondary" data-bs-toggle="modal"
                                data-bs-target="#importFileModal">
                                <i class="fa fa-upload"></i> Nhập file
                            </a>

                            <a href="{{ route('danh-muc-mon-an.export') }}" class="btn btn-sm btn-success">
                                <i class="fa fa-upload"></i> Xuất file
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle text-center"
                                id="{{ $tableId }}">
                                <thead class="table-dark">
                                    <tr>
                                        <th><strong>STT</strong></th>
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
                            <input style="height: auto" type="file" name="file" id="fileUpload" class="form-control"
                                required>
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

    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chỉnh sửa danh mục</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit-id" name="id">
                        <div class="mb-3">
                            <label>Tên</label>
                            <input type="text" id="edit-ten" name="ten" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Mô tả</label>
                            <textarea id="edit-mo-ta" name="mo_ta" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Lưu</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="createForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm danh mục mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Tên</label>
                            <input type="text" name="ten" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Mô tả</label>
                            <textarea name="mo_ta" class="form-control"></textarea>
                        </div>
                        {{-- <div class="mb-3">
                            <label>Hình ảnh</label>
                            <input type="file" name="hinh_anh" class="form-control">
                        </div> --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btn-save-create" class="btn btn-primary">Lưu</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    </div>
                </div>
            </form>
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
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
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

            // Xử lý submit form xóa với SweetAlert2
            $(document).on('submit', '.delete-form', function(e) {
                e.preventDefault();
                var form = $(this);

                Swal.fire({
                    title: 'Bạn muốn ngừng kinh doanh mục này chứ?',
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
                                if (response.success) {
                                    Swal.fire(
                                        'Thành công!',
                                        response
                                        .message, // "Xóa danh mục thành công."
                                        'success'
                                    );
                                    table.ajax.reload();
                                } else {
                                    Swal.fire(
                                        'Lỗi!',
                                        response
                                        .message, // "Danh mục này có món ăn đang được sử dụng, không thể xóa."
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr) {
                                let message = xhr.responseJSON?.message ||
                                    'Có lỗi xảy ra, vui lòng thử lại!';
                                Swal.fire(
                                    'Lỗi!',
                                    message,
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            // Xử lý submit form khôi phục
            $(document).on('submit', 'form:not(.delete-form)', function(e) {
                e.preventDefault();
                var form = $(this);

                Swal.fire({
                    title: 'Bạn có chắc muốn khôi phục mục này không?',
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
                                    response.message ||
                                    'Danh mục đã được khôi phục.',
                                    'success'
                                );
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                let message = xhr.responseJSON?.message ||
                                    'Có lỗi xảy ra, vui lòng thử lại!';
                                Swal.fire(
                                    'Lỗi!',
                                    message,
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            // Nút sửa
            $(document).on('click', '.btn-edit', function() {
                const id = $(this).data('id');

                $.ajax({
                    url: `/danh-muc-mon-an/${id}`,
                    type: 'GET',
                    success: function(data) {
                        $('#edit-id').val(data.id);
                        $('#edit-ten').val(data.ten);
                        $('#edit-mo-ta').val(data.mo_ta ?? 'Chưa có mô tả');
                        $('#editForm').attr('action', `/danh-muc-mon-an/${id}`);
                        $('#editModal').modal('show');
                    },
                    error: function(xhr) {
                        Swal.fire('Lỗi!', 'Lỗi khi lấy dữ liệu: ' + xhr.responseText, 'error');
                    }
                });
            });

            $(document).on('click', '#editForm button[type="submit"]', function(e) {
                e.preventDefault();
                const id = $('#edit-id').val();
                const formData = {
                    ten: $('#edit-ten').val(),
                    mo_ta: $('#edit-mo-ta').val(),
                    _token: $('input[name="_token"]').val(),
                    _method: 'PUT'
                };

                $.ajax({
                    url: `/danh-muc-mon-an/${id}`,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#editModal').modal('hide');
                        table.ajax.reload();
                        Swal.fire('Thành công!', response.message, 'success');
                    },
                    error: function(xhr) {
                        let message = xhr.responseJSON?.message || 'Đã có lỗi xảy ra.';
                        Swal.fire('Lỗi!', message, 'error');
                    }
                });
            });

            // Thêm mới
            $(document).on('click', '.btn-create', function() {
                $('#createForm')[0].reset();
                $('#createModal').modal('show');
            });

            $(document).on('click', '#btn-save-create', function(e) {
                e.preventDefault();
                const form = $('#createForm')[0];
                const formData = new FormData(form);

                $.ajax({
                    url: '/danh-muc-mon-an',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        $('#createModal').modal('hide');
                        Swal.fire('Thành công!', res.message, 'success');
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON?.errors;
                        let message = errors ? Object.values(errors).join('<br>') :
                            'Có lỗi xảy ra!';
                        Swal.fire('Lỗi!', message, 'error');
                    }
                });
            });

            // Nhập file
            $(document).on('click', '#btn-import-confirm', function(e) {
                e.preventDefault();
                const form = $('#importFileForm')[0];
                const formData = new FormData(form);

                $.ajax({
                    url: '{{ route('danh-muc-mon-an.import') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        $('#importFileModal').modal('hide');
                        Swal.fire('Thành công!', res.message || 'File đã được nhập thành công.',
                            'success');
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON?.errors;
                        let message = errors ? Object.values(errors).join('<br>') :
                            'Có lỗi xảy ra khi nhập file!';
                        Swal.fire('Lỗi!', message, 'error');
                    }
                });
            });
        });
    </script>
@endsection
