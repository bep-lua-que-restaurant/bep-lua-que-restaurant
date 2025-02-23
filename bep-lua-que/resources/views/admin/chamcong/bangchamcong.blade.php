<!-- Modal Chấm Công -->
<div class="modal fade" id="modalChamCong" tabindex="-1" aria-labelledby="modalChamCongLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalChamCongLabel">Chấm công</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Nhân viên:</strong> <span id="modalNhanVien"></span></p>
                <p><strong>Ngày:</strong> <span id="modalNgay"></span></p>
                <p><strong>Ca:</strong> <span id="modalCa"></span></p>

                <!-- Hidden inputs để lưu thông tin -->
                <input type="hidden" id="inputNhanVienId">
                <input type="hidden" id="inputNgayChamCong">
                <input type="hidden" id="inputCaId">

                <ul class="nav nav-tabs" id="tabChamCong">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#chamCongContent">Chấm công</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#lichSuContent">Lịch sử chấm công</a>
                    </li>
                </ul>

                <div class="tab-content mt-3">
                    <!-- Tab Chấm Công -->
                    <div class="tab-pane fade show active" id="chamCongContent">
                        <div class="mb-3">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <label for="modalGhiChu" class="form-label">Ghi chú</label>
                            <input type="text" class="form-control" id="modalGhiChu">
                        </div>
                        <div class="mb-3">
                            <label for="modalGioVao" class="form-label">Giờ vào</label>
                            <input type="time" class="form-control" id="modalGioVao">
                        </div>
                        <div class="mb-3">
                            <label for="modalGioRa" class="form-label">Giờ ra</label>
                            <input type="time" class="form-control" id="modalGioRa">
                        </div>

                    </div>

                    <!-- Tab Lịch Sử Chấm Công -->
                    <div class="tab-pane fade" id="lichSuContent">
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>Thời gian</th>
                                    <th>Trạng thái</th>
                                    <th>Hình thức</th>
                                    <th>Ghi chú</th>
                                </tr>
                            </thead>
                            <tbody id="modalLichSuChamCong">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="deleteButton">Xóa</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" id="btnLuuChamCong">Lưu</button>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".cham-cong").forEach(item => {
            item.addEventListener("click", function() {
                let nhanVienId = this.dataset.nhanvienId;
                let ngay = this.dataset.ngay;
                let ca = this.dataset.ca;
                let tenNhanVien = this.innerText.trim();

                // Đổ dữ liệu vào modal
                document.getElementById("modalNhanVien").innerText = tenNhanVien;
                document.getElementById("modalNgay").innerText = ngay;
                document.getElementById("modalCa").innerText = ca;

                document.getElementById("inputNhanVienId").value = nhanVienId;
                document.getElementById("inputNgayChamCong").value = ngay;
                document.getElementById("inputCaId").value = ca;



                // Gọi API lấy dữ liệu chấm công và đổ vào modal
                fetch(`/cham-cong/edit/${nhanVienId}/${ca}/${ngay}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`Lỗi HTTP! Mã trạng thái: ${response.status}`);
                        }
                        return response.text();
                    })
                    .then(html => {
                        document.getElementById("chamCongContent").innerHTML = html;
                    })
                    .catch(error => console.error("Lỗi khi lấy dữ liệu chấm công:", error));

                // Gọi AJAX để lấy HTML lịch sử chấm công
                fetch(`/lich-su-cham-cong?`)
                    .then(response => response.text()) // Lấy HTML từ server
                    .then(html => {
                        document.getElementById("modalLichSuChamCong").innerHTML = html;
                    });

                // Hiển thị modal
                modal.show();


            });
        });
    });
</script>
