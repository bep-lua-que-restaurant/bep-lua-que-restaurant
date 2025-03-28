@extends('layouts.admin')

@section('title')
    Chỉnh sửa bàn ăn
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Chỉnh sửa bàn ăn</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('ban-an.index') }}">Danh sách bàn ăn</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Chỉnh sửa bàn ăn</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-primary">Cập nhật thông tin bàn ăn</h3>
                        <hr>

                        <!-- Form chỉnh sửa -->
                        <form action="{{ route('ban-an.update', $banAn->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Tên bàn -->
                            <div class="form-group">
                                <label for="ten_ban">Tên bàn ăn</label>
                                <input type="text" id="ten_ban" name="ten_ban" class="form-control"
                                    value="{{ old('ten_ban', $banAn->ten_ban) }}">
                                @if ($errors->has('ten_ban'))
                                    <small class="text-danger">*{{ $errors->first('ten_ban') }}</small>
                                @endif
                            </div>

                            <!-- Loại bàn -->
                            {{-- <div class="form-group">
                                <label for="so_ghe">Loại bàn</label>
                                <select name="so_ghe" id="so_ghe" class="form-control">
                                    <option value="4" {{ old('so_ghe', $banAn->so_ghe ?? 4) == 4 ? 'selected' : '' }}>
                                        Loại bàn 2-4 ghế</option>
                                    <option value="8" {{ old('so_ghe', $banAn->so_ghe ?? 4) == 8 ? 'selected' : '' }}>
                                        Loại bàn 6-8 ghế</option>
                                    <option value="10" {{ old('so_ghe', $banAn->so_ghe ?? 4) == 10 ? 'selected' : '' }}>
                                        Loại bàn 8-10 ghế</option>
                                </select>
                                @if ($errors->has('so_ghe'))
                                    <small class="text-danger">*{{ $errors->first('so_ghe') }}</small>
                                @endif
                            </div> --}}
                            <input type="hidden" name="so_ghe" value="4">
                            <!-- Mô tả -->
                            <div class="form-group">
                                <label for="mo_ta">Mô tả</label>
                                <textarea id="mo_ta" name="mo_ta" class="form-control">{{ old('mo_ta', $banAn->mo_ta) }}</textarea>
                                @if ($errors->has('mo_ta'))
                                    <small class="text-danger">*{{ $errors->first('mo_ta') }}</small>
                                @endif
                            </div>

                            <!-- Nút lưu -->
                            <div class="form-group text-right">
                                <a href="{{ route('ban-an.index') }}" class="btn btn-secondary">
                                    <i class="fa fa-arrow-left"></i> Quay lại
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Cập nhật
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
