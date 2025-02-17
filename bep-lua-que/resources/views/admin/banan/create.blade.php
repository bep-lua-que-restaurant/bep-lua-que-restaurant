@extends('layouts.admin')

@section('title')
    Thêm mới bàn ăn
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Thêm mới bàn ăn</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Thêm mới bàn ăn</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('ban-an.store') }}" method="POST">
                            @csrf

                            <!-- Tên bàn ăn -->
                            <div class="form-group">
                                <label for="ten_ban">Tên bàn ăn</label>
                                <input type="text" id="ten_ban" name="ten_ban" class="form-control"
                                    placeholder="Nhập tên bàn ăn" value="{{ old('ten_ban') }}">
                                @if ($errors->has('ten_ban'))
                                    <small class="text-danger">*{{ $errors->first('ten_ban') }}</small>
                                @endif
                            </div>

                            <!-- Số ghế -->
                            <div class="form-group">
                                <label for="so_ghe">Số ghế</label>
                                <input type="number" id="so_ghe" name="so_ghe" class="form-control"
                                    placeholder="Nhập số ghế" value="{{ old('so_ghe') }}">
                                @if ($errors->has('so_ghe'))
                                    <small class="text-danger">*{{ $errors->first('so_ghe') }}</small>
                                @endif
                            </div>

                            <!-- Mô tả -->
                            <div class="form-group">
                                <label for="mo_ta">Mô tả</label>
                                <textarea id="mo_ta" name="mo_ta" class="form-control" placeholder="Nhập mô tả">{{ old('mo_ta') }}</textarea>
                                @if ($errors->has('mo_ta'))
                                    <small class="text-danger">*{{ $errors->first('mo_ta') }}</small>
                                @endif
                            </div>

                            <!-- Vị trí (THÊM TRƯỜNG BỊ THIẾU) -->
                            <div class="form-group">
                                <label for="vi_tri">Vị trí</label>
                                <select name="vi_tri" id="vi_tri" class="form-control">
                                    <option value="">Chọn vị trí bàn</option> <!-- Đặt ngoài vòng lặp -->
                                    @foreach ($phongAn as $item)
                                        <option value="{{ $item->id }}">{{ $item->ten_phong_an }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('vi_tri'))
                                    <small class="text-danger">*{{ $errors->first('vi_tri') }}</small>
                                @endif
                            </div>


                            <!-- Nút submit -->
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus"></i> Thêm mới
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
