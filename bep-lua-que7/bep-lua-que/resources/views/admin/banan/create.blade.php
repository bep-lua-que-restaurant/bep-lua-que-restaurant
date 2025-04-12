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
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
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

                            <!-- Vị trí -->
                            {{-- <div class="form-group">
                                <label for="vi_tri">Vị trí</label>
                                <select name="vi_tri" id="vi_tri" class="form-control" onchange="laySoLuongBan()">
                                    <option value="">Chọn vị trí bàn</option>
                                    @foreach ($phongAn as $item)
                                        <option value="{{ $item->id }}">{{ $item->ten_phong_an }}</option>
                                    @endforeach
                                </select>
                                <small id="vi_tri_error" class="text-danger"></small>
                            </div> --}}

                            <!-- Loại bàn -->
                            {{-- <div class="form-group">
                                <label for="so_ghe">Loại bàn</label>
                                <select name="so_ghe" id="so_ghe" class="form-control">
                                    <option value="">Chọn loại bàn</option>
                                    <option value="4" id="ban4">Loại bàn 2-4 ghế</option>
                                    <option value="8" id="ban8">Loại bàn 6-8 ghế</option>
                                    <option value="10" id="ban10">Loại bàn 8-10 ghế</option>
                                </select>
                                <small id="so_ghe_error" class="text-danger"></small>
                            </div> --}}

                            <!-- Mô tả -->
                            <div class="form-group">
                                <label for="mo_ta">Mô tả</label>
                                <textarea id="mo_ta" name="mo_ta" class="form-control" placeholder="Nhập mô tả">{{ old('mo_ta') }}</textarea>
                                @if ($errors->has('mo_ta'))
                                    <small class="text-danger">*{{ $errors->first('mo_ta') }}</small>
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

    <!-- Gửi yêu cầu AJAX -->

    <!-- Hiển thị số lượng bàn -->
    {{-- <div id="soLuongBanHienCo" style="margin-top: 10px; font-weight: bold;"></div> --}}

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let soLuongBanHienTai = {};
        const gioiHan = {
            "4": 6,
            "8": 4,
            "10": 2
        };

        function laySoLuongBan() {
            let viTri = $("#vi_tri").val();
            if (!viTri) return;

            $.ajax({
                url: `/api/get-so-luong-ban?vi_tri=${viTri}`,
                type: "GET",
                success: function(data) {
                    if (data.success) {
                        soLuongBanHienTai = data.soLuongBan;
                        capNhatThongTin();
                        capNhatTrangThaiTuyChon();
                    } else {
                        $("#soLuongBanHienCo").html("❌ Không thể lấy dữ liệu.");
                    }
                },
                error: function() {
                    $("#soLuongBanHienCo").html("❌ Lỗi kết nối đến server.");
                }
            });
        }

        function capNhatThongTin() {
            let hienThi = `
                                        🔹 Bàn 4 ghế: ${soLuongBanHienTai[4] || 0}/6 <br>
                                        🔹 Bàn 8 ghế: ${soLuongBanHienTai[8] || 0}/4 <br>
                                        🔹 Bàn 10 ghế: ${soLuongBanHienTai[10] || 0}/2
                                    `;
            $("#soLuongBanHienCo").html(hienThi);
        }

        function capNhatTrangThaiTuyChon() {
            ["4", "8", "10"].forEach((loai) => {
                let soBanHienCo = soLuongBanHienTai[loai] || 0;
                if (soBanHienCo >= gioiHan[loai]) {
                    $(`#ban${loai}`).prop("disabled", true).hide();
                } else {
                    $(`#ban${loai}`).prop("disabled", false).show();
                }
            });
        }

        function kiemTraVaGui() {
            let viTri = $("#vi_tri").val();
            let soGhe = $("#so_ghe").val();

            if (!viTri) {
                $("#vi_tri_error").text("Vui lòng chọn vị trí!");
                return;
            } else {
                $("#vi_tri_error").text("");
            }

            if (!soGhe) {
                $("#so_ghe_error").text("Vui lòng chọn loại bàn!");
                return;
            } else {
                $("#so_ghe_error").text("");
            }

            let soBanHienCo = soLuongBanHienTai[soGhe] || 0;

            if (soBanHienCo >= gioiHan[soGhe]) {
                alert(`⚠️ Bàn ${soGhe} ghế đã đạt tối đa ${gioiHan[soGhe]} bàn!`);
                return;
            }

            // Gửi AJAX để thêm bàn
            $.ajax({
                url: "/api/ban-an/store",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    vi_tri: viTri,
                    so_ghe: soGhe
                },
                success: function(data) {
                    if (data.success) {
                        alert(data.message);
                        laySoLuongBan(); // Cập nhật số bàn mới
                    } else {
                        alert(data.message);
                    }
                },
                error: function() {
                    alert("❌ Có lỗi xảy ra khi thêm bàn.");
                }
            });
        }
    </script>
@endsection
