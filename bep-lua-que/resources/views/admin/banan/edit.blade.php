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

                            <!-- Số ghế -->
                            <div class="form-group">
                                <label for="so_ghe">Số ghế</label>
                                <input type="number" id="so_ghe" name="so_ghe" class="form-control"
                                    value="{{ old('so_ghe', $banAn->so_ghe) }}">
                                @if ($errors->has('so_ghe'))
                                    <small class="text-danger">*{{ $errors->first('so_ghe') }}</small>
                                @endif
                            </div>

                            <!-- Mô tả -->
                            <div class="form-group">
                                <label for="mo_ta">Mô tả</label>
                                <textarea id="mo_ta" name="mo_ta" class="form-control">{{ old('mo_ta', $banAn->mo_ta) }}</textarea>
                                @if ($errors->has('mo_ta'))
                                    <small class="text-danger">*{{ $errors->first('mo_ta') }}</small>
                                @endif
                            </div>

                            <!-- Vị trí -->
                            <div class="form-group">
                                <label for="vi_tri">Vị trí</label>
                                <select name="vi_tri" id="vi_tri" class="form-control">
                                    <option value="">Chọn vị trí bàn</option>
                                    @foreach ($phongAns as $phongAn)
                                        <option value="{{ $phongAn->id }}"
                                            {{ old('vi_tri', $banAn->vi_tri) == $phongAn->id ? 'selected' : '' }}>
                                            {{ $phongAn->ten_phong_an }}
                                            @if ($phongAn->deleted_at)
                                                (Phòng không còn sử dụng)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>

                                @if ($errors->has('vi_tri'))
                                    <small class="text-danger">*{{ $errors->first('vi_tri') }}</small>
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
