@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Bảng chấm công</h4>
            <input type="text" class="form-control w-25" placeholder="Tìm kiếm nhân viên">
            <button class="btn btn-success">Xuất file</button>
        </div>

        <div class="d-flex justify-content-between mb-3">
            <select class="form-select w-25">
                <option>Theo tuần</option>
            </select>
            <div>
                <button class="btn btn-light" onclick="changeWeek(-1)">&#60;</button>
                <span id="week-label">{{ $weekLabel }}</span>
                <button class="btn btn-light" onclick="changeWeek(1)">&#62;</button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>Ca làm việc</th>
                        @foreach ($dates as $date)
                            <th>
                                {{ ucfirst($date->translatedFormat('l')) }} <br>
                                <span class="badge bg-secondary">{{ $date->format('d') }}</span>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($caLams as $caLam)
                        <tr>
                            <td>{{ $caLam->ten_ca }} ({{ $caLam->gio_bat_dau }} - {{ $caLam->gio_ket_thuc }})</td>
                            @foreach ($dates as $date)
                                @php
                                    $nhanViens = $chamCongs->filter(function ($chamCong) use ($caLam, $date) {
                                        return $chamCong->ca_lam_id == $caLam->id &&
                                            $chamCong->ngay_lam == $date->format('Y-m-d');
                                    });
                                @endphp

                                <td>
                                    @foreach ($nhanViens as $nhanVien)
                                        @php
                                            // Kiểm tra xem nhân viên này có chấm công cho ca làm cụ thể chưa
                                            $daChamCong = collect($chamCongs)->contains(function ($chamCong) use (
                                                $nhanVien,
                                                $date,
                                                $caLam,
                                            ) {
                                                return $chamCong->nhan_vien_id == $nhanVien->nhan_vien_id &&
                                                    $chamCong->ca_lam_id == $caLam->id && //  Đảm bảo kiểm tra đúng ca làm
                                                    $chamCong->ngay_cham_cong == $date->format('Y-m-d'); // Đảm bảo đúng ngày
                                            });
                                        @endphp

                                        <span class="badge {{ $daChamCong ? 'bg-success' : 'bg-warning' }} cham-cong"
                                            data-id="{{ $nhanVien->ca_lam_nhan_vien_id }}"
                                            data-nhanvien-id="{{ $nhanVien->nhan_vien_id }}"
                                            data-ngay="{{ $date->format('Y-m-d') }}" data-ca="{{ $caLam->id }}">
                                            {{ $nhanVien->ten_nhan_vien }}
                                        </span>
                                    @endforeach
                                    @if ($nhanViens->isEmpty())
                                        <span class='badge bg-danger'>Chưa có</span>
                                    @endif

                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal chấm công -->
    @include('admin.chamcong.bangchamcong')

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.addEventListener("click", function(event) {
                if (event.target.classList.contains("cham-cong")) {
                    event.preventDefault();

                    document.querySelector(".btn-close").addEventListener("click", function() {
                        modal.hide();
                    });

                    document.querySelector(".btn-secondary").addEventListener("click", function() {
                        modal.hide();
                    });

                    let nhanVienId = event.target.dataset.nhanvienId;
                    let ngay = event.target.dataset.ngay;
                    let ca = event.target.dataset.ca;

                    document.getElementById("modalNhanVien").innerText = nhanVienId;
                    document.getElementById("modalNgay").innerText = ngay;
                    document.getElementById("modalCa").innerText = ca;

                    document.getElementById("inputNhanVienId").value = nhanVienId;
                    document.getElementById("inputNgayChamCong").value = ngay;
                    document.getElementById("inputCaId").value = ca;

                    let modal = new bootstrap.Modal(document.getElementById("modalChamCong"));



                    modal.show();
                }
            });

            $("#btnLuuChamCong").click(function() {

                // Lấy giá trị giờ vào và giờ ra
                let gioVao = $("#modalGioVao").val().trim();
                let gioRa = $("#modalGioRa").val().trim();

                // Kiểm tra xem cả 2 trường có được nhập không
                if (gioVao === "" || gioRa === "") {
                    alert("Vui lòng nhập đầy đủ giờ vào và giờ ra.");
                    return;
                }

                // Kiểm tra định dạng giờ theo mẫu HH: mm(ví dụ: 08: 30)
                let timeRegex = /^([01]\d|2[0-3]):([0-5]\d)$/;
                if (!timeRegex.test(gioVao) || !timeRegex.test(gioRa)) {
                    alert("Giờ phải có định dạng HH:mm (ví dụ: 08:30).");
                    return;
                }

                // So sánh thời gian: chuyển đổi chuỗi thành đối tượng Date giả định cùng 1 ngày (ví dụ ngày 1970-01-01)
                let timeVao = new Date("1970-01-01T" + gioVao + ":00");
                let timeRa = new Date("1970-01-01T" + gioRa + ":00");

                if (timeRa <= timeVao) {
                    alert("Giờ ra phải lớn hơn giờ vào.");
                    return;
                }


                let data = {
                    _token: "{{ csrf_token() }}",
                    nhan_vien_id: $("#inputNhanVienId").val(),
                    ca_lam_id: $("#inputCaId").val(),
                    ngay_cham_cong: $("#inputNgayChamCong").val(),
                    mo_ta: $("#modalGhiChu").val(),
                    gio_vao_lam: $("#modalGioVao").val(),
                    gio_ket_thuc: $("#modalGioRa").val()
                };
                console.log("Dữ liệu gửi lên:", data);
                let csrfToken = "{{ csrf_token() }}";

                $.ajax({
                    url: "{{ route('chamcong.store') }}",
                    type: "POST",
                    data: data,
                    success: function(response) {
                        $("#modalChamCong").modal("hide");
                        location.reload();
                    },
                    error: function(xhr) {
                        console.log(xhr.responseJSON); // Kiểm tra lỗi chi tiết
                        alert("Lỗi: " + JSON.stringify(xhr.responseJSON.errors));
                    }
                });


            });
        });

        function changeWeek(offset) {
            let currentOffset = {{ $weekOffset }};
            let newOffset = currentOffset + offset;
            window.location.href = "{{ route('cham-cong.index') }}?week_offset=" + newOffset;
        }
    </script>
@endsection
