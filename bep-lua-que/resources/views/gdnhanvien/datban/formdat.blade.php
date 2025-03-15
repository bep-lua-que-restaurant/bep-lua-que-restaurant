<!-- Modal Form Đặt Bàn -->
<div class="modal fade" id="orderFormModal" tabindex="-1" aria-labelledby="orderFormLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderFormLabel">Thông Tin Đặt Bàn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Ô tìm kiếm khách hàng -->
                <div class="mb-3">
                    <label class="form-label">Tìm khách hàng:</label>
                    <input type="text" class="form-control" id="searchCustomer"
                        placeholder="Nhập số điện thoại hoặc tên">
                    <ul id="customerList" class="list-group mt-2" style="display: none;"></ul>
                </div>

                <!-- Thông tin khách hàng -->
                <div class="mb-3">
                    <label class="form-label">Họ và Tên:</label>
                    <input type="text" class="form-control" id="customerName" name="customer_name" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Số điện thoại:</label>
                    <input type="text" class="form-control" id="customerPhone" name="customer_phone" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Căn cước công dân:</label>
                    <input type="text" class="form-control" id="customerID" name="customer_id">
                </div>


                <!-- Thông tin đặt bàn -->
                <div class="mb-3">
                    <label class="form-label">Ngày:</label>
                    <input type="date" class="form-control" id="selectedDate" name="date">
                </div>
                <div class="mb-3">
                    <label class="form-label">Giờ:</label>
                    <input type="time" class="form-control" id="selectedTime" name="time" min="08:00"
                        max="22:00">
                </div>

                <div class="mb-3">
                    <label class="form-label">Bàn:</label>
                    <input type="text" class="form-control" id="selectedBanName" name="ban_name" readonly>
                    <input type="hidden" id="selectedBanId" name="ban_id">
                </div>
                <div class="mb-3">
                    <label class="form-label">Số người:</label>
                    <input type="number" class="form-control" id="numPeople" name="num_people" min="1" required>
                </div>

                <button type="submit" class="btn btn-primary">Xác Nhận Đặt Bàn</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    // Khi trang được tải xong, xử lý điền thông tin vào form từ URL
    document.addEventListener('DOMContentLoaded', function() {
        // Lấy các giá trị từ query parameters trong URL
        const urlParams = new URLSearchParams(window.location.search);

        const date = urlParams.get('date');
        const time = urlParams.get('time');
        const tenBan = urlParams.get('ten_ban');
        const idBan = urlParams.get('id_ban');

        // Điền thông tin vào form modal
        if (date) {
            document.getElementById('selectedDate').value = date;
        }
        if (time) {
            document.getElementById('selectedTime').value = time;
        }
        if (tenBan) {
            document.getElementById('selectedBanName').value = tenBan;
        }
        if (idBan) {
            document.getElementById('selectedBanId').value = idBan;
        }
    });
</script>
