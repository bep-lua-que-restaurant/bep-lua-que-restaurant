@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Bảng chấm công</h4>
            {{-- <input type="text" class="form-control w-25" placeholder="Tìm kiếm nhân viên"> --}}

            <div>
                <button class="btn btn-light" onclick="changeDate(-1)">&#60;</button>
                <span id="date-label" data-date="{{ $selectedDate }}">{{ $dayLabel }}</span>
                <button class="btn btn-light" onclick="changeDate(1)">&#62;</button>
                <a href="{{ route('cham-cong.export') }}" class="btn btn-sm btn-success">
                    <i class="fa fa-download"></i> Xuất file
                </a>
            </div>
        </div>


        <div id="cham-cong-table" class="table-responsive">
            @include('admin.chamcong.listchamcong')

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
            // function storeChamCong(data) {
            //     $.ajax({
            //         url: "{{ route('chamcong.store') }}",
            //         type: "POST",
            //         data: data,
            //         success: function() {
            //             alert("Chấm công thành công!");
            //             $("#myModal").modal("hide"); // Ẩn modal sau khi lưu
            //             location.reload();
            //         },
            //         error: function(xhr) {
            //             console.error("Lỗi khi tạo mới chấm công:", xhr.responseText);
            //             alert("Lỗi khi tạo mới: " + xhr.responseText);
            //         }
            //     });
            // }

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

        function changeDate(offset) {
            let currentDate = $("#date-label").data("date"); // Lấy ngày hiện tại
            let newDate = new Date(currentDate);
            newDate.setDate(newDate.getDate() + offset); // Cộng/trừ số ngày

            let formattedDate = newDate.toISOString().split('T')[0]; // Định dạng YYYY-MM-DD

            $.ajax({
                url: @json(route('cham-cong.index')), // Đảm bảo URL chính xác
                type: "GET",
                data: {
                    selected_date: formattedDate // Đúng với tên biến trong controller
                },
                success: function(response) {
                    $("#date-label").text(response.dayLabel); // Cập nhật nhãn ngày
                    $("#date-label").data("date", formattedDate); // Cập nhật ngày mới
                    $("#cham-cong-table").html(response.html); // Cập nhật bảng chấm công
                },
                error: function() {
                    alert("Có lỗi xảy ra, vui lòng thử lại!");
                }
            });
        }
    </script>
@endsection
