@extends('layouts.admin')

@section('title')
    Mã giảm giá
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
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Mã giám giá </a></li>
                </ol>
            </div>
        </div>
        <!-- row -->
        <div class="row">
            {{-- @include('admin.filter') --}}
            <div class="col-lg-12 my-4">
                <div class="d-flex justify-content-between align-items-center">
                    <!-- Ô tìm kiếm mã giảm giá -->
                    <div class="input-group">
                        <input style="height: 45px; margin-right: 10px" type="text" id="searchInput"
                            class="form-control border-1" placeholder="Tìm kiếm theo mã" onkeyup="locMaGiamGia()">
                    </div>

                    <!-- Lựa chọn trạng thái -->
                    <div>
                        <select style="padding: 11px 0 11px 0; width: 142px" id="statusFilter"
                            class="btn btn-primary btn-sm" onchange="locMaGiamGia()">
                            <option value="" hidden>Lọc theo trạng thái</option>
                            <option value="Đang hoạt động">Đang hoạt động</option>
                            <option value="Đã ngừng hoạt động">Ngừng hoạt động</option>
                            <option value="Tất cả">Tất cả</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Danh sách</h4>

                        <div class="btn-group">
                            <a href="{{ route('ma-giam-gia.create') }}" class="btn btn-sm btn-primary">
                                <i class="fa fa-plus"></i> Thêm mới
                            </a>
                            <a href="#" class="btn btn-sm btn-secondary" data-bs-toggle="modal"
                                data-bs-target="#importFileModal">
                                <i class="fa fa-upload"></i> Nhập file
                            </a>

                            <a href="{{ route('ma-giam-gia.export') }}" class="btn btn-sm btn-success">
                                <i class="fa fa-download"></i> Xuất file
                            </a>
                            {{-- <a href="#" class="btn btn-sm btn-info">
                                <i class="fa fa-list"></i> Danh sách
                            </a> --}}
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="maGiamGiaTable" class="table table-responsive-md">
                                <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Mã</th>
                                    <th>Loại</th>
                                    <th>Giá trị</th>
                                    <th>Hiệu lực</th>
                                    <th>Số lượt đã dùng</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($data as $index => $item)
                                    <tr class="ma-giam-gia-row">
                                        <td>{{ $data->firstItem() + $index }}</td>
                                        <td class="ma-giam-gia-code">{{ $item->code }}</td>
                                        <td>{{ $item->type == 'percentage' ? 'Phần trăm' : 'Tiền' }}</td>
                                        <td class="text-center">
                                            @if ($item->type == 'percentage')
                                                {{ number_format($item->value, 0, ',', '.') . '%' }}
                                            @else
                                                {{ number_format($item->value, 0, ',', '.') . 'VND' }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->start_date && $item->end_date)
                                                Từ: {{ \Carbon\Carbon::parse($item->start_date)->format('d/m/Y') }}<br>
                                                Đến: {{ \Carbon\Carbon::parse($item->end_date)->format('d/m/Y') }}
                                            @else
                                                Không xác định
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $item->usage_count ?? 0 }}</td>

                                        <td class="ma-giam-gia-status">
                                            {{ $item->deleted_at ? 'Đã ngừng hoạt động' : 'Đang hoạt động' }}
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="{{ route('ma-giam-gia.show', $item->id) }}"
                                                   class="btn btn-info btn-sm m-1">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                @if (!$item->deleted_at)
                                                    <a href="{{ route('ma-giam-gia.edit', $item->id) }}"
                                                       class="btn btn-warning btn-sm m-1">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endif

                                                @if ($item->deleted_at)
                                                    <form action="{{ route('ma-giam-gia.restore', $item->id) }}"
                                                          method="POST" class="d-inline" style="margin: 0;">
                                                        @csrf
                                                        <button type="submit"
                                                                onclick="return confirm('Bạn có chắc muốn khôi phục mục này không?')"
                                                                class="btn btn-success btn-sm m-1" title="Khôi phục">
                                                            <i class="fa fa-recycle"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('ma-giam-gia.destroy', $item->id) }}"
                                                          method="POST" class="d-inline" style="margin: 0;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                onclick="return confirm('Bạn muốn ngừng mã giảm này chứ?')"
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
                    <form action="{{ route('ma-giam-gia.import') }}" method="POST" enctype="multipart/form-data"
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

    <script>
        function locMaGiamGia(page = 1) {
            var searchInput = $('#searchInput').val();
            var statusFilter = $('#statusFilter').val();

            $.ajax({
                url: '{{ route("ma-giam-gia.index") }}',
                type: 'GET',
                data: {
                    searchInput: searchInput,
                    statusFilter: statusFilter,
                    page: page
                },
                success: function (response) {
                    $('#maGiamGiaTable').html($(response.html).find('#maGiamGiaTable').html());
                    $('#pagination').html($(response.pagination).html());
                },
                error: function () {
                    alert('Lỗi khi tải dữ liệu!');
                }
            });
        }
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            locMaGiamGia(page);
        });
    </script>
@endsection


