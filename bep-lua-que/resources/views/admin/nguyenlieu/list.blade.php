@extends('layouts.admin')

@section('title')
    Nguyên liệu
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Chào mừng đến Bếp lửa quê!</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Nguyên liệu</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Danh sách nguyên liệu</h4>

                        <div class="btn-group mb-3">
                            <!-- Nút mở modal import -->
                            {{-- <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#importModal">
                                <i class="fa fa-upload"></i> Import
                            </button> --}}
                        
                            <!-- Export -->
                            <a href="{{ route('nguyen-lieu.export') }}" class="btn btn-success">
                                <i class="fa fa-download"></i> Export
                            </a>
                        
                            <!-- Kiểm tra tồn kho -->
                            <a href="{{ route('nguyen-lieu.kiemtra') }}" class="btn btn-warning">
                                <i class="bi bi-bar-chart-line"></i> Kiểm tra tồn kho
                            </a>
                        
                            <!-- Danh sách -->
                            <a href="#" class="btn btn-sm btn-info">
                                <i class="fa fa-list"></i> Danh sách
                            </a>
                        </div>
                        
                        <!-- Modal import -->
                        <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content border-0 shadow-lg rounded-4">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="importModalLabel">
                                            <i class="fa fa-upload"></i> Import Nguyên Liệu
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('tools.nguyen-lieu.import') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="file" class="form-label">Chọn file Excel (*.xlsx, *.csv):</label>
                                                <input type="file" name="file" class="form-control" required accept=".xlsx,.csv">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">Import</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-responsive-md" id="{{ $tableId }}">
                                <thead>
                                    <tr>
                                        <th><strong>ID</strong></th>
                                        <th><strong>Tên nguyên liệu</strong></th>
                                        <th><strong>Loại</strong></th>
                                        <th><strong>Đơn vị tồn</strong></th>
                                        <th><strong>Số lượng tồn</strong></th>
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
        $(document).ready(function () {
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
                    { data: 'id', name: 'id' },
                    { data: 'ten_nguyen_lieu', name: 'ten_nguyen_lieu' },
                    { data: 'loai_nguyen_lieu', name: 'loai_nguyen_lieu.ten_loai' },
                    { data: 'don_vi_ton', name: 'don_vi_ton' },
                    { data: 'so_luong_ton', name: 'so_luong_ton' },
                    
                    { data: 'trang_thai', name: 'deleted_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/vi.json'
                },
                pagingType: 'full_numbers',
                renderer: 'bootstrap',
                lengthMenu: [5, 10, 25, 50],
                pageLength: 10
            });

            $(document).on('submit', 'form', function (e) {
                e.preventDefault();
                var form = $(this);
                var isDelete = form.find('button[title="Xóa"]').length > 0;

                Swal.fire({
                    title: isDelete ? 'Bạn muốn ngừng sử dụng nguyên liệu này?' : 'Bạn muốn khôi phục nguyên liệu này?',
                    text: "Hành động này sẽ thay đổi trạng thái nguyên liệu!",
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
                            success: function (response) {
                                Swal.fire(
                                    'Thành công!',
                                    isDelete ? 'Đã ngừng sử dụng nguyên liệu.' : 'Đã khôi phục nguyên liệu.',
                                    'success'
                                );
                                table.ajax.reload();
                            },
                            error: function (xhr) {
                                Swal.fire('Lỗi!', 'Có lỗi xảy ra, vui lòng thử lại!', 'error');
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
