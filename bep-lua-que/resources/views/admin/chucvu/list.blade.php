@extends('layouts.admin')

@section('title')
    Chức vụ
@endsection

@section('content')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

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
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Chức vụ</a></li>
                </ol>
            </div>
        </div>

        {{-- <div class="row">
            <div class="col-lg-12 my-4">
                <div class="d-flex justify-content-between align-items-center">
                    <!-- Ô tìm kiếm -->
                    <div class="input-group" style="max-width: 300px;">
                        <input style="height: 45px;" type="text" id="customSearch" class="form-control border-1"
                            placeholder="Tìm kiếm theo chức vụ">
                    </div>
                </div>
            </div>
        </div> --}}

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Danh sách</h4>
                        <div class="btn-group">
                            <a href="{{ route('chuc-vu.create') }}" class="btn btn-sm btn-primary">
                                <i class="fa fa-plus"></i> Thêm mới
                            </a>
                            <a href="#" class="btn btn-sm btn-secondary" data-bs-toggle="modal"
                                data-bs-target="#importFileModal">
                                <i class="fa fa-upload"></i> Nhập file
                            </a>
                            <a href="{{ route('chuc-vu.export') }}" class="btn btn-sm btn-success">
                                <i class="fa fa-download"></i> Xuất file
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="chucvuTable" class="table table-responsive-md">
                                <thead>
                                    <tr>
                                        <th><strong>STT</strong></th>
                                        <th><strong>Tên chức vụ</strong></th>
                                        <th><strong>Trạng thái</strong></th>
                                        <th><strong>Hành động</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $index => $item)
                                        <tr>
                                            <td><strong>{{ $loop->iteration }}</strong></td>
                                            <td>{{ $item->ten_chuc_vu }}</td>
                                            <td>
                                                @if ($item->deleted_at)
                                                    <div class="d-flex align-items-center"><i
                                                            class="fa fa-circle text-danger mr-1"></i> Đã ngừng hoạt động
                                                    </div>
                                                @else
                                                    <div class="d-flex align-items-center"><i
                                                            class="fa fa-circle text-success mr-1"></i> Đang hoạt động</div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if (!$item->deleted_at)
                                                        <a href="{{ route('chuc-vu.edit', $item->id) }}"
                                                            class="btn btn-warning btn-sm m-1">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    @endif

                                                    @if ($item->deleted_at)
                                                        <form action="{{ route('chuc-vu.restore', $item->id) }}"
                                                            method="POST" class="d-inline" style="margin: 0;">
                                                            @csrf
                                                            <button type="submit"
                                                                onclick="return confirm('Bạn có chắc muốn khôi phục mục này không?')"
                                                                class="btn btn-success btn-sm m-1" title="Khôi phục">
                                                                <i class="fa fa-recycle"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form action="{{ route('chuc-vu.destroy', $item->id) }}"
                                                            method="POST" class="d-inline" style="margin: 0;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                onclick="return confirm('Bạn muốn ngừng hoạt động chức vụ này chứ?')"
                                                                class="btn btn-danger btn-sm m-1" title="Xóa">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div id="pagination">
                                {{ $data->links('pagination::bootstrap-5') }}
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
                            aria-label="Close"><span>×</span></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('chuc-vu.import') }}" method="POST" enctype="multipart/form-data"
                            id="importFileForm">
                            @csrf
                            <div class="mb-3">
                                <label for="fileUpload" class="form-label">Chọn file</label>
                                <input style="height: auto" type="file" name="file" id="fileUpload"
                                    class="form-control" required>
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
    </div>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>


    <script>
        $(document).ready(function() {
            var table = $('#chucvuTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/vi.json' // Ngôn ngữ tiếng Việt
                },
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                order: [
                    [0, 'desc']
                ],
                columnDefs: [{
                        orderable: false,
                        targets: 3
                    } // Vô hiệu hóa sắp xếp trên cột Hành động
                ]
            });

            // Tìm kiếm tùy chỉnh
            $('#customSearch').on('keyup', function() {
                table.search(this.value).draw();
            });
        });
    </script>
@endsection
