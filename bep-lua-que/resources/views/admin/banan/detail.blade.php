@extends('layouts.admin')

@section('title')
    Chi tiết bàn ăn
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Tiêu đề trang -->
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Chi tiết bàn ăn</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('ban-an.index') }}">Danh sách bàn ăn</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Chi tiết bàn ăn</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <!-- Tiêu đề -->
                        <h3 class="text-primary">Thông tin chi tiết</h3>
                        <hr>

                        <!-- Hiển thị thông tin bàn ăn -->
                        <table class="table table-bordered">
                            <tr>
                                <th>ID</th>
                                <td>{{ $banAn->id }}</td>
                            </tr>
                            <tr>
                                <th>Tên bàn</th>
                                <td>{{ $banAn->ten_ban }}</td>
                            </tr>
                            <tr>
                                <th>Số ghế</th>
                                <td>{{ $banAn->so_ghe }}</td>
                            </tr>
                            <tr>
                                <th>Mô tả</th>
                                <td>{{ $banAn->mo_ta ?? 'Không có mô tả' }}</td>
                            </tr>


                            <tr class="{{ $phongAn && $phongAn->deleted_at ? 'table-danger' : '' }}">
                                <th>Vị trí</th>
                                <td>
                                    @if ($phongAn)
                                        {{ $phongAn->ten_phong_an }}
                                        @if ($phongAn->deleted_at)
                                            <small class="text-danger">* Phòng không còn sử dụng</small>
                                        @endif
                                    @else
                                        <span class="text-danger">Không tìm thấy phòng ăn</span>
                                    @endif
                                </td>
                            </tr>

                            <tr class="{{ $banAn->deleted_at ? 'table-danger' : '' }}">
                                <th>Trạng thái</th>
                                <td>
                                    @if ($banAn->deleted_at)
                                        <span class="badge badge-danger">Ngừng sử dụng</span>
                                    @else
                                        <span class="badge badge-success">Đang sử dụng</span>
                                        @if ($phongAn && $phongAn->deleted_at)
                                            <span class="badge badge-warning">Phòng đã ngừng hoạt động</span>
                                        @endif
                                    @endif
                                </td>
                            </tr>


                        </table>

                        <hr>

                        <!-- Các nút thao tác -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('ban-an.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Quay lại
                            </a>

                            <div>
                                <!-- Nút chỉnh sửa -->
                                @if (!$banAn->deleted_at)
                                    <a href="{{ route('ban-an.edit', $banAn->id) }}" class="btn btn-warning">
                                        <i class="fa fa-edit"></i> Chỉnh sửa
                                    </a>
                                @endif

                                @if ($banAn->deleted_at)
                                    <!-- Nút khôi phục nếu bàn ăn đã bị xóa -->
                                    <form action="{{ route('ban-an.restore', $banAn->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit"
                                            onclick="return confirm('Bạn có chắc muốn khôi phục bàn ăn này không?')"
                                            class="btn btn-success">
                                            <i class="fa fa-recycle"></i> Khôi phục
                                        </button>
                                    </form>
                                @else
                                    <!-- Nút xóa (ngừng sử dụng) -->
                                    <form action="{{ route('ban-an.destroy', $banAn->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('Bạn muốn ngừng sử dụng bàn ăn này chứ?')"
                                            class="btn btn-danger">
                                            <i class="fa fa-trash"></i> Ngừng sử dụng
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
