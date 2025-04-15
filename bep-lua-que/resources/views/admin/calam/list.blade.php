@extends('layouts.admin')

@section('title')
    Ca Làm
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
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Ca làm</a></li>
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
                            class="form-control border-1" placeholder="Tìm kiếm theo ca" onkeyup="filterDiscountCodes()">
                    </div>

                    <!-- Lựa chọn trạng thái -->
                    <div>
                        <select style="padding: 11px 0 11px 0; width: 142px" id="statusFilter"
                            class="btn btn-primary btn-sm" onchange="filterDiscountCodes()">
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
                            <a href="{{ route('ca-lam.create') }}" class="btn btn-sm btn-primary">
                                <i class="fa fa-plus"></i> Thêm mới
                            </a>
                            <!-- Nút Nhập file sẽ hiển thị Modal -->
                            <a href="{{ route('ca-lam.import') }}" class="btn btn-sm btn-secondary" data-toggle="modal"
                                data-target=".bd-example-modal-lg">
                                <i class="fa fa-upload"></i> Nhập file
                            </a>

                            <a href="{{ route('ca-lam.export') }}" class="btn btn-sm btn-success">
                                <i class="fa fa-download"></i> Xuất file
                            </a>
                            {{-- <a href="#" class="btn btn-sm btn-info">
                                <i class="fa fa-list"></i> Danh sách
                            </a> --}}
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
                                        <th><strong>STT</strong></th>
                                        <th><strong>Ca làm việc </strong></th>
                                        <th><strong>Thời gian </strong></th>

                                        <th><strong>Tổng giờ làm việc</strong></th>
                                        <th><strong>Trạng thái</strong></th>
                                        <th><strong>Hành động</strong></th>
                                    </tr>
                                </thead>
                                <tbody id="calam">
                                    @foreach ($data as $index => $item)
                                        <tr data-toggle="collapse" data-target="#detail{{ $index }}"
                                            class="clickable-row">
                                            <td>
                                                <div class="custom-control custom-checkbox checkbox-success check-lg mr-3">
                                                    <input type="checkbox" class="custom-control-input" id="customCheckBox2"
                                                        required="">
                                                    <label class="custom-control-label" for="customCheckBox2"></label>
                                                </div>
                                            </td>
                                            <td><strong>{{ $item->id }}</strong></td>
                                            <td class="ten-ca">
                                                <div class="d-flex align-items-center"><span
                                                        class="w-space-no">{{ $item->ten_ca }}</span></div>
                                            </td>

                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="w-space-no">{{ $item->gio_bat_dau }} -
                                                        {{ $item->gio_ket_thuc }}</span>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php
                                                    // Tính toán tổng thời gian làm việc
                                                    $startTime = \Carbon\Carbon::parse($item->gio_bat_dau);
                                                    $endTime = \Carbon\Carbon::parse($item->gio_ket_thuc);
                                                    $duration = $startTime->diff($endTime);
                                                    
                                                    // Tổng thời gian làm việc (giờ và phút)
                                                    $totalHours = $duration->h;
                                                    $totalMinutes = $duration->i;
                                                    ?>
                                                    <span class="w-space-no">{{ $totalHours }} giờ {{ $totalMinutes }}
                                                        phút</span>
                                                </div>
                                            </td>

                                            <td class="trang-thai">
                                                @if ($item->deleted_at != null)
                                                    <div class="d-flex align-items-center"><i
                                                            class="fa fa-circle text-danger mr-1"></i> Đã ngừng hoạt động
                                                    </div>
                                                @else
                                                    <div class="d-flex align-items-center"><i
                                                            class="fa fa-circle text-success mr-1"></i> Đang hoạt động
                                                    </div>
                                                    {{ $item->deleted_at }}
                                                @endif

                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <a href="{{ route('ca-lam.show', $item->id) }}"
                                                        class="btn btn-info btn-sm m-1" title="Xem chi tiết">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('ca-lam.edit', $item->id) }}"
                                                        class="btn btn-warning btn-sm m-1" title="Chỉnh sửa">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    @if ($item->deleted_at)
                                                        <form action="{{ route('ca-lam.restore', $item->id) }}"
                                                            method="POST" class="d-inline" style="margin: 0;">
                                                            @csrf
                                                            <button type="submit"
                                                                onclick="return confirm('Bạn có chắc muốn khôi phục này không?')"
                                                                class="btn btn-success btn-sm m-1" title="Khôi phục">
                                                                <i class="fa fa-recycle"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form action="{{ route('ca-lam.destroy', $item->id) }}"
                                                            method="POST" class="d-inline" style="margin: 0;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                onclick="return confirm('Bạn muốn ngừng mục này chứ?')"
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
                    <form action="{{ route('ca-lam.import') }}" method="POST" enctype="multipart/form-data"
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

    @include('admin.search-srcip')
    <!-- Hiển thị phân trang -->
    {{ $data->links('pagination::bootstrap-5') }}
@endsection

<script>
    function filterDiscountCodes() {
        // Get the values from the filter inputs
        let searchInput = document.getElementById("searchInput").value.toLowerCase();
        let statusFilter = document.getElementById("statusFilter").value;
        let rows = document.querySelectorAll("#calam tr"); // Select all rows in the "Ca làm" table

        rows.forEach(row => {
            // Get the relevant data from the row using class names
            let tenCa = row.querySelector(".ten-ca")?.textContent.toLowerCase(); // For "Ca làm"
            let status = row.querySelector(".trang-thai")?.textContent.trim().toLowerCase(); // For "Trạng thái"

            // Check if the row matches the search input and the selected status filter
            let matchesSearch = tenCa && tenCa.includes(searchInput);
            let matchesStatus = statusFilter === "" || statusFilter === "Tất cả" || status === statusFilter
                .toLowerCase();

            // Show or hide the row based on the conditions
            if (matchesSearch && matchesStatus) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }
</script>
