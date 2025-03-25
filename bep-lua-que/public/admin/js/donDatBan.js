function showOrders() {

    let ordersList = document.getElementById("ordersList");
    // Xóa danh sách cũ trước khi tải mới
    ordersList.innerHTML = "<p>Đang tải...</p>";
    // Gửi AJAX để lấy danh sách đơn đặt trước
    $.ajax({
        url: "/thu-ngan/get-orders", // Đường dẫn API lấy danh sách đơn
        method: "GET",
        success: function (response) {
            ordersList.innerHTML = "";
            if (response.length > 0) {
                response.forEach((order, index) => {
                    let row = `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${order.ma_dat_ban}</td>
                            <td>${order.ho_ten}</td>
                            <td>${order.so_nguoi}</td>
                            <td>${order.danh_sach_ban}</td>
                           <td>${formatDateTime(order.thoi_gian_den)}</td>
                            <td><span class="badge bg-warning">Đặt trước</span></td>
                        </tr>`;
                    ordersList.innerHTML += row;
                });
            } else {
                ordersList.innerHTML = `<tr><td colspan="4">Không có đơn đặt trước nào!</td></tr>`;
            }
        },
        error: function () {
            ordersList.innerHTML = `<tr><td colspan="4">Lỗi khi tải danh sách đơn!</td></tr>`;
        },
    });

    // Hiển thị modal
    var modal = new bootstrap.Modal(document.getElementById("ordersModal"));
    modal.show();
}

function formatDateTime(dateTimeStr) {
    let date = new Date(dateTimeStr);
    let hours = date.getHours();
    let minutes = date.getMinutes().toString().padStart(2, "0");
    let day = date.getDate().toString().padStart(2, "0");
    let month = (date.getMonth() + 1).toString().padStart(2, "0");
    let year = date.getFullYear();

    return `${hours}h${minutes} ${day}-${month}-${year}`;
}