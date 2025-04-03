@extends('layouts.admin')

@section('title')
    Danh mục Bàn Ăn
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Hi, welcome back!</h4>
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
        <div class="row">
            @include('admin.filter')
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Danh sách</h4>



                        <div class="btn-group">
                            <a href="{{ route('ban-an.themNhanh') }}" class="btn btn-sm btn-primary">
                                <i class="fa fa-plus"></i> Thêm nhanh
                            </a>
                            <!-- Nút Thêm mới -->
                            <a href="{{ route('ban-an.create') }}" class="btn btn-sm btn-primary">
                                <i class="fa fa-plus"></i> Thêm mới
                            </a>

                            <!-- Nút Nhập file (Mở Modal) -->
                            <a href="#" class="btn btn-sm btn-secondary" data-toggle="modal"
                                data-target="#importExcelModal">
                                <i class="fa fa-upload"></i> Nhập file
                            </a>

                            <!-- Nút Xuất file -->
                            <a href="{{ route('ban-an.export') }}" class="btn btn-sm btn-success">
                                <i class="fa fa-download"></i> Xuất file
                            </a>

                            <!-- Nút Danh sách -->
                            <a href="{{ route('ban-an.index') }}" class="btn btn-sm btn-info">
                                <i class="fa fa-list"></i> Danh sách
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
                                        <th><strong>Số ghế </strong></th>
                                        {{-- <th><strong>Vị trí </strong></th> --}}


                                        <th><strong>Trạng Thái</strong></th>
                                        <th><strong>Hành động</strong></th>
                                    </tr>
                                </thead>


                                <tbody id="{{ $tableId }}">
                                    @include('admin.banan.body-list')
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @include('admin.search-srcip')
    <!-- Hiển thị phân trang -->
    {{ $data->links('pagination::bootstrap-5') }}
@endsection
