@extends('layouts.admin')

@section('title')
    Loại nguyên liệu
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
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Loại nguyên liệu</a></li>
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
                            <a href="#" class="btn btn-sm btn-secondary" data-toggle="modal"
                                data-target=".bd-example-modal-lg">
                                <i class="fa fa-download"></i> Nhập file
                            </a>
                            <a href="{{ route('loai-nguyen-lieu.export') }}" class="btn btn-sm btn-success">
                                <i class="fa fa-upload"></i> Xuất file
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle text-center"
                                id="loaiNguyenLieuTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên loại nguyên liệu</th>
                                        <th>Trạng thái</th>
                                        <th>Hành động</th>
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

    {{-- Modal Nhập file --}}
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="importFileModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('loai-nguyen-lieu.import') }}" method="POST" enctype="multipart/form-data"
                    id="importFileForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Nhập file</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="fileUpload">Chọn file</label>
                            <input type="file" name="file" id="fileUpload" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Xác nhận</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Thêm mới --}}
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="createForm"method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm loại nguyên liệu mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Tên</label>
                            <input type="text" name="ten_loai" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Mô tả</label>
                            <textarea name="ghi_chu" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btn-save-create" class="btn btn-primary">Lưu</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Sửa --}}
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="editForm" method="POST"enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chỉnh sửa loại nguyên liệu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit-id" name="id">
                        <div class="mb-3">
                            <label>Tên</label>
                            <input type="text" id="edit-ten-loai" name="ten_loai" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Mô tả</label>
                            <textarea id="edit-mo-ta" name="ghi_chu" class="form-control"></textarea>
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

    {{-- JS --}}
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('#loaiNguyenLieuTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('loai-nguyen-lieu.index') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'ten_loai',
                        name: 'ten_loai'
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

            // Xử lý submit form (xóa/khôi phục)
            $(document).on('submit', 'form.form-delete-restore', function(e) {
                e.preventDefault();

                const form = $(this);
                const isDelete = form.find('button[title="Xóa"]').length > 0;

                Swal.fire({
                    title: isDelete ? 'Bạn muốn ngừng kinh doanh loại nguyên liệu này chứ?' :
                        'Bạn có chắc muốn khôi phục loại nguyên liệu này không?',
                    text: "Hành động này có thể thay đổi trạng thái của loại nguyên liệu!",
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
                                    response.message || (isDelete ?
                                        'Đã ngừng kinh doanh.' : 'Đã khôi phục.'),
                                    'success'
                                );
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Lỗi!',
                                    xhr.responseJSON?.message ||
                                    'Đã có lỗi xảy ra, vui lòng thử lại.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });


            // Sửa
            $(document).on('click', '.btn-edit', function() {
                const id = $(this).data('id');

                $.ajax({
                    url: `/loai-nguyen-lieu/${id}`,
                    type: 'GET',
                    success: function(data) {
                        $('#edit-id').val(data.id);
                        $('#edit-ten-loai').val(data.ten_loai);
                        $('#edit-mo-ta').val(data.ghi_chu ?? 'Chưa có mô tả');

                        $('#editForm').attr('action', `/loai-nguyen-lieu/${id}`);
                        $('#editModal').modal('show');
                    },
                    error: function(xhr) {
                        alert('Lỗi khi lấy dữ liệu: ' + xhr.responseText);
                    }
                });
            });

            // Cập nhật
            $(document).on('click', '#editForm button[type="submit"]', function(e) {
                e.preventDefault();

                const id = $('#edit-id').val();

                const formData = {
                    ten_loai: $('#edit-ten-loai').val(),
                    mo_ta: $('#edit-mo-ta').val(),
                    _token: $('input[name="_token"]').val(),
                    _method: 'PUT'
                };

                $.ajax({
                    url: `/loai-nguyen-lieu/${id}`,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#editModal').modal('hide');
                        $('#loaiNguyenLieuTable').DataTable().ajax.reload();
                        Swal.fire('Thành công!', 'Loại nguyên liệu đã được cập nhật.',
                            'success');
                    },
                    error: function(xhr) {
                        let msg = 'Đã có lỗi xảy ra.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        Swal.fire('Lỗi!', msg, 'error');
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
                // console.log(formData);
                // for (let [key, value] of formData.entries()) {
                //     console.log(key, value);
                // }


                $.ajax({
                    url: '/loai-nguyen-lieu',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        $('#createModal').modal('hide');
                        Swal.fire('Thành công!', res.message, 'success');
                        $('#loaiNguyenLieuTable').DataTable().ajax.reload();
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON?.errors;
                        let message = 'Có lỗi xảy ra!';
                        if (errors) {
                            message = Object.values(errors).join('<br>');
                        }
                        Swal.fire('Lỗi', message, 'error');
                    }
                });
            });
        });
    </script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
