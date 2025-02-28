@extends('layouts.admin')

@section('title')
    Chấm công
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <a href="{{ route('cham-cong.index') }}" class="btn btn-primary">
                        <i class="fa fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Chi tiết chấm công</a></li>
                </ol>
            </div>
        </div>
        <!-- row -->
        {{-- <div class="row">
            @include('admin.filter')
        </div> --}}

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Danh sách chi tiết chấm công</h4>

                        <div class="btn-group">
                            <!-- Nút Nhập file sẽ hiển thị Modal -->


                            <a href="{{ route('cham-cong.export') }}" class="btn btn-sm btn-success">
                                <i class="fa fa-download"></i> Xuất file
                            </a>
                            <a href="#" class="btn btn-sm btn-info">
                                <i class="fa fa-list"></i> Danh sách
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-responsive-md">
                                <thead>
                                    <tr>
                                        <th>Nhân viên</th>
                                        <th>Ca làm</th>
                                        <th>Ngày chấm công</th>
                                        <th>Trạng thái</th>
                                        <th>Hành động</th>
                                    </tr>

                                    @foreach ($danhSachChamCong as $item)
                                        <tr>
                                            <td>{{ $item->ten_nhan_vien }}</td>
                                            <td>{{ $item->ten_ca }}</td>
                                            <td>{{ $item->ngay_cham_cong ? \Carbon\Carbon::parse($item->ngay_cham_cong)->format('d-m-Y') : 'Chưa có' }}
                                            </td>

                                            <td>
                                                @if ($item->deleted_at)
                                                    <span class="text-danger">Đã hủy chấm công</span>
                                                @else
                                                    <span class="text-success">Đã chấm công</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item->deleted_at)
                                                    {{-- Nút Khôi phục --}}
                                                    <form action="{{ route('cham-cong.restore') }}" method="POST"
                                                        style="display:inline;">
                                                        @csrf
                                                        <input type="hidden" name="nhan_vien_id"
                                                            value="{{ $item->nhan_vien_id }}">
                                                        <input type="hidden" name="ca_lam_id"
                                                            value="{{ $item->ca_lam_id }}">
                                                        <input type="hidden" name="ngay_cham_cong"
                                                            value="{{ $item->ngay_cham_cong }}">
                                                        <button type="submit"
                                                            onclick="return confirm('Bạn có chắc muốn khôi phục chấm công này không?')"
                                                            class="btn btn-success btn-sm" title="Khôi phục">
                                                            <i class="fa fa-recycle"></i> Khôi phục
                                                        </button>
                                                    </form>
                                                @else
                                                    {{-- Nút Xóa --}}
                                                    <form action="{{ route('cham-cong.softDelete') }}" method="POST"
                                                        style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="nhan_vien_id"
                                                            value="{{ $item->nhan_vien_id }}">
                                                        <input type="hidden" name="ca_lam_id"
                                                            value="{{ $item->ca_lam_id }}">
                                                        <input type="hidden" name="ngay_cham_cong"
                                                            value="{{ $item->ngay_cham_cong }}">
                                                        <button type="submit"
                                                            onclick="return confirm('Bạn muốn hủy chấm công nhân viên này chứ?')"
                                                            class="btn btn-danger btn-sm" title="Xóa">
                                                            <i class="fa fa-trash"></i> Xóa
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach


                                    </tr>
                                </thead>
                                {{-- <tbody>
                                    @include('admin.chamcong.chamcong')
                                </tbody> --}}

                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal Nhập file -->
    {{-- <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="importFileModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importFileModalLabel">Nhập file</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"
                        aria-label="Close"><span>&times;</span></button>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" form="importFileForm" class="btn btn-primary">Xác nhận</button>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- @include('admin.search-srcip') --}}
    <!-- Hiển thị phân trang -->
    {{-- {{ $danhSachChamChong->links('pagination::bootstrap-5') }} --}}
@endsection
