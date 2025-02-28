@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Bảng chấm công</h4>
            <input type="text" class="form-control w-25" placeholder="Tìm kiếm nhân viên">
            <a href="{{ route('cham-cong.export') }}" class="btn btn-sm btn-success">
                <i class="fa fa-download"></i> Xuất file
            </a>
        </div>

        <div class="d-flex justify-content-between mb-3">
            <a href="{{ route('cham-cong.danhsach') }}" class="btn btn-info">
                <i class="fa fa-list"></i> Danh sách chi tiết
            </a>

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
                                                    $chamCong->ca_lam_id == $caLam->id && // Đảm bảo kiểm tra đúng ca làm
                                                    $chamCong->ngay_cham_cong == $date->format('Y-m-d'); // Đảm bảo đúng ngày
                                            });

                                            // Xác định màu của badge
                                            if ($nhanVien->deleted_at) {
                                                $badgeClass = 'bg-danger'; // Bị hủy
                                            } else {
                                                $badgeClass = $daChamCong ? 'bg-success' : 'bg-warning'; // Đã chấm công hoặc chưa chấm công
                                            }
                                        @endphp

                                        <span class="badge {{ $badgeClass }} cham-cong"
                                            data-id="{{ $nhanVien->ca_lam_nhan_vien_id }}"
                                            data-nhanvien-id="{{ $nhanVien->nhan_vien_id }}"
                                            data-ngay="{{ $date->format('Y-m-d') }}" data-ca="{{ $caLam->id }}">
                                            {{ $nhanVien->ten_nhan_vien }}
                                        </span>
                                        @if ($nhanVien->deleted_at)
                                            {{-- Nút Khôi phục --}}
                                            <form action="{{ route('cham-cong.restore') }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="nhan_vien_id"
                                                    value="{{ $nhanVien->nhan_vien_id }}">
                                                <input type="hidden" name="ca_lam_id" value="{{ $nhanVien->ca_lam_id }}">
                                                <input type="hidden" name="ngay_cham_cong"
                                                    value="{{ $nhanVien->ngay_cham_cong }}">
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
                                                    value="{{ $nhanVien->nhan_vien_id }}">
                                                <input type="hidden" name="ca_lam_id" value="{{ $nhanVien->ca_lam_id }}">
                                                <input type="hidden" name="ngay_cham_cong"
                                                    value="{{ $nhanVien->ngay_cham_cong }}">
                                                <button type="submit"
                                                    onclick="return confirm('Bạn muốn hủy chấm công nhân viên này chứ?')"
                                                    class="btn btn-danger btn-sm" title="Xóa">
                                                    <i class="fa fa-trash"></i> Xóa
                                                </button>
                                            </form>
                                        @endif
                                    @endforeach


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
                    // let deletedAt = event.target.dataset.deletedAt; // Lấy giá trị deleted_at

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

            $(document).on("click", "#btnLuuChamCong", function() {



                let nhan_vien_id = $("#inputNhanVienId").val();
                let ca = $("#inputCaId").val();
                let ngay = $("#inputNgayChamCong").val();
                var csrfToken = "{{ csrf_token() }}"; // Lấy token từ Laravel

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

                // Kiểm tra chấm công trước khi thực hiện hành động
                $.ajax({
                    url: `/cham-cong/check/${nhan_vien_id}/${ca}/${ngay}`,
                    type: "GET",
                    success: function(response) {
                        console.log("Dữ liệu trả về:", response);
                        if (response.trim() === "1") {
                            // Đã có chấm công → Cập nhật
                            updateChamCong(data, nhan_vien_id, ca, ngay);
                        } else {
                            // Chưa có chấm công → Tạo mới
                            storeChamCong(data);
                        }
                    },
                    error: function(xhr) {
                        console.error("Lỗi kiểm tra chấm công:", xhr.responseText);
                        alert("Lỗi kiểm tra chấm công!");
                    }
                });
            });

            // Hàm tạo mới chấm công
            function storeChamCong(data) {
                $.ajax({
                    url: "{{ route('chamcong.store') }}",
                    type: "POST",
                    data: data,
                    success: function() {
                        alert("Chấm công thành công!");
                        $("#myModal").modal("hide"); // Ẩn modal sau khi lưu
                        location.reload();
                    },
                    error: function(xhr) {
                        console.error("Lỗi khi tạo mới chấm công:", xhr.responseText);
                        alert("Lỗi khi tạo mới: " + xhr.responseText);
                    }
                });
            }

            // Hàm cập nhật chấm công
            function updateChamCong(data, nhan_vien_id, ca, ngay) {
                $.ajax({
                    url: `/cham-cong/update/${nhan_vien_id}/${ca}/${ngay}`,
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': data._token
                    },
                    data: {
                        ...data,
                        _method: "PATCH"
                    },
                    success: function() {
                        alert("Cập nhật thành công!");
                        $("#myModal").modal("hide"); // Ẩn modal sau khi cập nhật
                        location.reload();
                    },
                    error: function(xhr) {
                        console.error("Lỗi khi cập nhật:", xhr.responseText);
                        alert("Lỗi khi cập nhật: " + xhr.responseText);
                    }
                });
            }

        });

        function changeWeek(offset) {
            let currentOffset = {{ $weekOffset }};
            let newOffset = currentOffset + offset;
            window.location.href = "{{ route('cham-cong.index') }}?week_offset=" + newOffset;
        }
    </script>
@endsection
