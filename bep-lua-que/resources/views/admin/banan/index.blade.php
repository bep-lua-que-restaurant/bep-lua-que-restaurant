@extends('layouts.admin')

@section('title')
    Danh mục Bàn Ăn
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
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Danh mục bàn ăn</a></li>
                </ol>
            </div>
        </div>
        <!-- row -->
        {{-- <div class="row">
            @include('admin.filter')
        </div> --}}

        <div class="row mb-3">
            <div class="col-md-4">
                <input type="text" id="filter-ten" class="form-control" placeholder="Nhập tên bàn">
            </div>
            <div class="col-md-4">
                <select id="filter-status" class="form-control">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="Đang kinh doanh">Đang sử dụng</option>
                    <option value="Ngừng kinh doanh">Ngừng sử dụng</option>
                </select>
            </div>
            <div class="col-md-4">
                <button id="btn-filter" class="btn btn-primary">Lọc</button>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Danh sách</h4>
                        <div class="btn-group">

                            <!-- Nút hiển thị modal -->

                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalThemNhanhBanAn">
                                <i class="fa fa-plus"></i> Thêm nhanh bàn ăn
                            </button>


                            <!-- Nút Nhập file (Mở Modal) -->
                            <a href="#" class="btn btn-sm btn-secondary" data-toggle="modal"
                                data-target="#importExcelModal">
                                <i class="fa fa-upload"></i> Nhập file
                            </a>

                            <!-- Nút Xuất file -->
                            <a href="{{ route('ban-an.export') }}" class="btn btn-sm btn-success">
                                <i class="fa fa-download"></i> Xuất file
                            </a>

                        </div>

                        <!-- Modal Nhập File -->
                        <div class="modal fade" id="importExcelModal" tabindex="-1" role="dialog"
                            aria-labelledby="importExcelModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="importExcelModalLabel">Nhập dữ liệu từ Excel</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('ban-an.import') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <label for="file">Chọn file Excel (.xlsx, .xls)</label>
                                                <input type="file" name="file" id="file" class="form-control"
                                                    required>
                                                @if ($errors->has('file'))
                                                    <small class="text-danger">*{{ $errors->first('file') }}</small>
                                                @endif
                                            </div>
                                            <div class="text-right">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Hủy</button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fa fa-upload"></i> Nhập dữ liệu
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
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
                                        <th><strong>ID.</strong></th>
                                        <th><strong>Tên bàn </strong></th>
                                        {{-- <th><strong>Số ghế </strong></th> --}}
                                        <th><strong>Trạng Thái</strong></th>
                                        <th><strong>Tình Trạng </strong></th>
                                        <th><strong>Hành động</strong></th>
                                    </tr>
                                </thead>


                                <tbody id="table-body">
                                    <!-- Dữ liệu render bằng JS -->
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- <div id="pagination"></div> --}}
        {{-- <div id="pagination" class="mt-3 d-flex justify-content-center"></div> --}}

        <nav aria-label="Pagination" class="mt-4">
            <ul id="pagination" class="pagination justify-content-center mb-0">
                <!-- Các nút phân trang sẽ được JS đổ vào đây -->
            </ul>
        </nav>




    </div>

    <script>
        function renderTable(data) {
            let html = '';
            data.forEach((item, index) => {
                let deleted = item.deleted_at !== null;
                let statusKD = deleted ?
                    '<div class="d-flex align-items-center"><i class="fa fa-circle text-danger mr-1"></i> Ngừng sử dụng</div>' :
                    '<div class="d-flex align-items-center"><i class="fa fa-circle text-success mr-1"></i> Đang sử dụng</div>';

                const labels = {
                    trong: 'Trống',
                    co_khach: 'Có khách',
                    da_dat_truoc: 'Đã đặt trước'
                };

                html += `
                    <tr>
                        <td>
                            <div class="custom-control custom-checkbox checkbox-success check-lg mr-3">
                                <input type="checkbox" class="custom-control-input" id="customCheckBox${index}">
                                <label class="custom-control-label" for="customCheckBox${index}"></label>
                            </div>
                        </td>
                        <td><strong>${item.id}</strong></td>
                        <td><div class="d-flex align-items-center"><span class="w-space-no">${item.ten_ban}</span></div></td>
                        <td>${statusKD}</td>
                        <td><span>${labels[item.trang_thai] ?? item.trang_thai}</span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <button class="btn btn-info btnChiTietBanAn" data-id="${item.id}"><i class="fa fa-eye"></i></button>
                                ${!deleted ? `<button class="btn btn-warning btn-sm p-2 m-2 btnEditBanAn" data-id="${item.id}"><i class="fa fa-edit"></i></button>` : ''}
                                ${deleted
                                    ? `<button class="btn btn-success btn-sm p-2 m-2 btnRestoreBanAn" data-id="${item.id}"><i class="fa fa-recycle"></i></button>`
                                    : (item.trang_thai === 'trong'
                                        ? `<button class="btn btn-danger btn-sm p-2 m-2 btnDeleteBanAn" data-id="${item.id}"><i class="fa fa-trash"></i></button>`
                                        : '')
                                }
                            </div>
                        </td>
                    </tr>
                `;
            });

            $('#table-body').html(html);
            attachEventListeners(); // Gắn lại các sự kiện nếu cần
        }

        function renderPagination(paginationData) {
            let currentPage = paginationData.current_page;
            let lastPage = paginationData.last_page;
            let html = '';

            // Previous
            html += `
        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <button class="page-link" data-page="${currentPage - 1}" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </button>
        </li>
    `;

            // Pages
            for (let page = 1; page <= lastPage; page++) {
                html += `
            <li class="page-item ${page === currentPage ? 'active' : ''}">
                <button class="page-link" data-page="${page}">${page}</button>
            </li>
        `;
            }

            // Next
            html += `
        <li class="page-item ${currentPage === lastPage ? 'disabled' : ''}">
            <button class="page-link" data-page="${currentPage + 1}" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </button>
        </li>
    `;

            $('#pagination').html(html);

            // Bắt sự kiện khi click
            $('#pagination .page-link').on('click', function() {
                let page = $(this).data('page');
                if (page && !$(this).parent().hasClass('disabled') && !$(this).parent().hasClass('active')) {
                    fetchPage(page);
                }
            });
        }



        function fetchPage(page = 1) {
            let ten = $('#filter-ten').val();
            let status = $('#filter-status').val();

            $.ajax({
                url: '{{ route('ban-an.fetch') }}?page=' + page,
                method: 'GET',
                data: {
                    ten: ten,
                    statusFilter: status
                },
                success: function(res) {
                    renderTable(res.data.data);
                    renderPagination(res.data);
                },
                error: function(err) {
                    console.error(err);
                }
            });
        }

        $('#btn-filter').on('click', function() {
            fetchPage(1); // luôn quay về trang 1 khi filter
        });

        function fetchAllData() {
            $.ajax({
                url: '{{ route('ban-an.fetch') }}',
                method: 'GET',
                data: {},
                success: function(res) {
                    renderTable(res.data.data);
                    renderPagination(res.data);
                },
                error: function(err) {
                    console.error(err);
                }
            });
        }

        // Hàm này có thể khai báo các sự kiện nút như xem chi tiết, sửa, xoá,...
        function attachEventListeners() {
            $('.btnChiTietBanAn').on('click', function() {
                let id = $(this).data('id');
                console.log("Xem chi tiết bàn ăn:", id);
                // show modal hoặc call ajax chi tiết tại đây
            });

            // Các sự kiện khác: edit, delete, restore...
        }

        // Load mặc định khi trang được tải
        $(document).ready(function() {
            fetchPage(1);
        });
    </script>



    <div class="modal fade" id="modalThemNhanhBanAn" tabindex="-1" aria-hidden="true" data-bs-backdrop="false">

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm nhanh bàn ăn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formThemNhanhBanAn">
                        @csrf
                        <div class="form-group mb-2">
                            <label for="so_luong">Số lượng bàn:</label>
                            <input type="number" class="form-control" id="so_luong" name="so_luong" min="1"
                                required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="prefix">Tiền tố tên bàn:</label>
                            <input type="text" class="form-control" id="prefix" name="prefix" value="Bàn "
                                required>
                            <small class="form-text text-muted">Tên bàn sẽ là: [Tiền tố] + Số thứ tự (ví dụ: Bàn 1, Bàn
                                2...)</small>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Thêm nhanh</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="chiTietBanAnModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chi tiết bàn ăn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <tr>
                            <th>ID</th>
                            <td id="modal-ban-id"></td>
                        </tr>
                        <tr>
                            <th>Tên bàn</th>
                            <td id="modal-ten-ban"></td>
                        </tr>
                        <tr>
                            <th>Số ghế</th>
                            <td id="modal-so-ghe"></td>
                        </tr>
                        <tr>
                            <th>Mô tả</th>
                            <td id="modal-mo-ta"></td>
                        </tr>
                        <tr>
                            <th>Trạng thái</th>
                            <td id="modal-trang-thai"></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal chỉnh sửa -->
    <div class="modal fade" id="editBanAnModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="formEditBanAn">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chỉnh sửa bàn ăn</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit-id" name="id">
                        <div class="mb-3">
                            <label>Tên bàn</label>
                            <input type="text" class="form-control" id="edit-ten-ban" name="ten_ban" required>
                        </div>
                        {{-- <div class="mb-3">
                            <label>Số ghế</label>
                            <input type="number" class="form-control" id="edit-so-ghe" name="so_ghe" required>
                        </div> --}}
                        <div class="mb-3">
                            <label>Mô tả</label>
                            <textarea class="form-control" id="edit-mo-ta" name="mo_ta"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            // THÊM NHANH
            $('#formThemNhanhBanAn').on('submit', function(e) {
                e.preventDefault();

                const $form = $(this);

                $.ajax({
                    url: '{{ route('ban-an.store-quick') }}',
                    type: 'POST',
                    data: $form.serialize(),
                    success: function(response) {
                        // ✅ Hiển thị thông báo thành công
                        alert(response.message || 'Thêm nhanh thành công!');

                        // ✅ Cập nhật bảng dữ liệu
                        renderTable(response.data);

                        // ✅ Reset lại form để người dùng thêm tiếp
                        $form[0].reset();
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let messages = Object.values(errors).flat().join('\n');
                            alert(messages);
                        } else {
                            alert('Có lỗi xảy ra!');
                        }
                    }
                });
            });


            $('#modalThemNhanhBanAn').on('hidden.bs.modal', function() {
                $('#formThemNhanhBanAn')[0].reset();
            });






            // XEM CHI TIẾT
            $(document).on('click', '.btnChiTietBanAn', function() {
                const id = $(this).data('id');

                $.ajax({
                    url: `/ban-an/ajax/${id}`,
                    type: 'GET',
                    success: function(data) {
                        $('#modal-ban-id').text(data.id);
                        $('#modal-ten-ban').text(data.ten_ban);
                        $('#modal-so-ghe').text(data.so_ghe);
                        $('#modal-mo-ta').text(data.mo_ta ?? 'Không có mô tả');
                        $('#modal-trang-thai').html(data.deleted_at ?
                            '<span class="badge bg-danger">Ngừng sử dụng</span>' :
                            '<span class="badge bg-success">Đang sử dụng</span>'
                        );
                        $('#chiTietBanAnModal').modal('show');
                    },
                    error: function() {
                        alert('Không thể lấy thông tin bàn ăn.');
                    }
                });
            });

            // CHỈNH SỬA
            $(document).on('click', '.btnEditBanAn', function() {
                const id = $(this).data('id');

                $.ajax({
                    url: `/ban-an/ajax/${id}`,
                    type: 'GET',
                    success: function(data) {
                        $('#edit-id').val(data.id);
                        $('#edit-ten-ban').val(data.ten_ban);
                        $('#edit-mo-ta').val(data.mo_ta);
                        $('#editBanAnModal').modal('show');
                    },
                    error: function() {
                        alert('Không thể lấy dữ liệu bàn ăn.');
                    }
                });
            });

            // GỬI FORM CHỈNH SỬA
            $('#formEditBanAn').on('submit', function(e) {
                e.preventDefault();

                const id = $('#edit-id').val();
                const formData = {
                    ten_ban: $('#edit-ten-ban').val(),
                    mo_ta: $('#edit-mo-ta').val(),
                    _token: '{{ csrf_token() }}',
                    _method: 'PUT'
                };

                $.ajax({
                    url: `/ban-an/${id}`,
                    type: 'POST',
                    data: formData,
                    success: function() {
                        $('#editBanAnModal').modal('hide');
                        alert('Cập nhật thành công!');
                        fetchAllData(); // không reload trangg
                    },
                    error: function(xhr) {
                        let msg = 'Đã xảy ra lỗi';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        alert(msg);
                    }
                });
            });

            // XOÁ
            $(document).on('click', '.btnDeleteBanAn', function() {
                const id = $(this).data('id');

                if (!confirm('Bạn có chắc muốn ngừng sử dụng bàn ăn này không?')) return;

                $.ajax({
                    url: `/ban-an/${id}`,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        alert('Đã ngừng sử dụng bàn ăn!');
                        // location.reload();
                        fetchAllData(); // load lại danh sách
                    },
                    error: function(xhr) {
                        if (xhr.status === 403 || xhr.status === 422) {
                            alert(xhr.responseJSON.message ?? 'Không thể xoá bàn ăn!');
                        } else {
                            alert('Lỗi khi xóa bàn ăn!');
                        }
                    }
                });
            });

            // KHÔI PHỤC
            $(document).on('click', '.btnRestoreBanAn', function() {
                const id = $(this).data('id');

                if (!confirm('Bạn có chắc muốn khôi phục bàn ăn này không?')) return;

                $.ajax({
                    url: `/ban-an/restore/${id}`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert(response.message ?? 'Khôi phục thành công!');
                        // location.reload();
                        fetchAllData();
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON.message ?? 'Lỗi khi khôi phục bàn ăn!');
                    }
                });
            });
        });
    </script>
@endsection
