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



                            <!-- Vị trí -->
                            <div class="form-group">
                                <label for="vi_tri">Vị trí</label>
                                <select name="vi_tri" id="vi_tri" class="form-control" onchange="laySoLuongBan()">
                                    <option value="">Chọn vị trí bàn</option>
                                    @foreach ($phongAns as $phongAn)
                                        <option value="{{ $phongAn->id }}"
                                            {{ old('vi_tri', $banAn->vi_tri ?? '') == $phongAn->id ? 'selected' : '' }}>
                                            {{ $phongAn->ten_phong_an }}
                                            @if ($phongAn->deleted_at)
                                                (Phòng không còn sử dụng)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <small id="vi_tri_error" class="text-danger"></small>
                            </div>

                            <!-- Loại bàn -->
                            <div class="form-group">
                                <label for="so_ghe">Loại bàn</label>
                                <select name="so_ghe" id="so_ghe" class="form-control">
                                    <option value="">Chọn loại bàn</option>
                                </select>
                                <small id="so_ghe_error" class="text-danger"></small>
                            </div>

                            <!-- Hiển thị số bàn hiện có -->
                            {{-- <div id="soLuongBanHienCo" style="margin-top: 10px; font-weight: bold;"></div> --}}




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
            // Xóa tất cả option cũ và tạo lại từ đầu
            $("#so_ghe").empty().append(`<option value="">Chọn loại bàn</option>`);

            Object.keys(gioiHan).forEach((loai) => {
                let soBanHienCo = soLuongBanHienTai[loai] || 0;

                if (soBanHienCo < gioiHan[loai]) {
                    $("#so_ghe").append(
                        `<option value="${loai}">Loại bàn ${loai} ghế</option>`
                    );
                }
            });

            // Nếu không còn bàn nào khả dụng, thông báo
            if ($("#so_ghe option").length === 1) {
                $("#so_ghe").append(`<option disabled>⚠️ Tất cả loại bàn đã đủ số lượng</option>`);
            }
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

            // Gửi AJAX để cập nhật bàn
            $.ajax({
                url: "/api/ban-an/update",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    _method: "PUT",
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
                    alert("❌ Có lỗi xảy ra khi cập nhật bàn.");
                }
            });
        }
    </script>
@endsection
