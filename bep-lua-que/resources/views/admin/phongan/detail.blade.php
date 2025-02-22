@extends('layouts.admin')

@section('title')
    Chi tiết phòng ăn
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Tiêu đề trang -->
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Chi tiết phòng ăn</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('phong-an.index') }}">Danh sách phòng ăn</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Chi tiết phòng ăn</a></li>
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

                        <!-- Hiển thị thông tin phòng ăn -->
                        <table class="table table-bordered">
                            <tr>
                                <th>ID</th>
                                <td>{{ $phongAn->id }}</td>
                            </tr>
                            <tr>
                                <th>Tên phòng</th>
                                <td>{{ $phongAn->ten_phong_an }}</td>
                            </tr>
                            <tr>
                                <th>Trạng thái</th>
                                <td>
                                    @if ($phongAn->deleted_at)
                                        <span class="badge badge-danger">Ngừng sử dụng</span>
                                    @else
                                        <span class="badge badge-success">Đang sử dụng</span>
                                    @endif
                                </td>
                            </tr>
                        </table>

                        <hr>

                        <!-- Các nút thao tác -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('phong-an.index') }}" class="btn btn-secondary btn-sm p-2 m-2">
                                <i class="fa fa-arrow-left"></i> Quay lại
                            </a>

                            <div>
                                <!-- Nút chỉnh sửa -->
                                @if (!$phongAn->deleted_at)
                                    <a href="{{ route('phong-an.edit', $phongAn->id) }}"
                                        class="btn btn-warning btn-sm p-2 m-2">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                @endif

                                @if ($phongAn->deleted_at)
                                    <!-- Nút khôi phục nếu phòng ăn đã bị xóa -->
                                    <form action="{{ route('phong-an.restore', $phongAn->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit"
                                            onclick="return confirm('Bạn có chắc muốn khôi phục phòng ăn này không?')"
                                            class="btn btn-success">
                                            <i class="fa fa-recycle"></i> Khôi phục
                                        </button>
                                    </form>
                                @else
                                    <!-- Nút xóa (ngừng sử dụng) -->
                                    <form action="{{ route('phong-an.destroy', $phongAn->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('Bạn muốn ngừng sử dụng phòng ăn này chứ?')"
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
