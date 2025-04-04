import "./bootstrap";

// 📡 Lắng nghe sự kiện realtime từ Laravel Echo
document.addEventListener("DOMContentLoaded", function () {
    console.log("📡 Lắng nghe sự kiện realtime từ Laravel Echo...");

    // 🗑 Lắng nghe khi đặt bàn bị hủy
    window.Echo.channel("datban-channel").listen("DatBanDeleted", (event) => {
        // console.log("🗑 Hủy đặt bàn:", event);
        removeDatBanRow(event.maDatBan);
    });

    // 🆕 Lắng nghe khi có đặt bàn mới
    window.Echo.channel("datban-channel").listen("DatBanCreated", (event) => {
        // console.log("🆕 Đặt bàn mới:", event);
        addDatBanToTable(event);
    });

    // 🔄 Lắng nghe khi có cập nhật đặt bàn
    window.Echo.channel("datban-channel").listen("DatBanUpdated", (event) => {
        console.log("🔄 Cập nhật đặt bàn:", event);
        updateDatBanRow(event);
    });
});

// 📌 Thêm đặt bàn mới vào bảng
function addDatBanToTable(event) {
    const { danh_sach_ban, customer } = event;
    if (!Array.isArray(danh_sach_ban) || danh_sach_ban.length === 0) {
        console.error("❌ Lỗi: danh_sach_ban không hợp lệ", event);
        return;
    }

    const tableBody = document.querySelector("#tableBanDat tbody");
    const newRow = document.createElement("tr");
    newRow.setAttribute("data-id", danh_sach_ban[0].ma_dat_ban);
    newRow.classList.add("text-center");

    newRow.innerHTML = `
        <td class="align-middle">${formatDateTime(
            danh_sach_ban[0]?.thoi_gian_den || "Không rõ"
        )}</td>
        <td class="align-middle" >${customer?.ho_ten || "Không rõ"}</td>
        <td class="align-middle" >${customer?.so_dien_thoai || "Không rõ"}</td>
        <td class="align-middle" >${
            danh_sach_ban[0]?.so_nguoi || "Không rõ"
        }</td>
        <td class="align-middle" >${renderBanList(danh_sach_ban)}</td>
        <td class="align-middle" >${renderStatusBadge(
            danh_sach_ban[0]?.trang_thai
        )}</td>
        <td class="align-middle"  id="action-buttons-${
            danh_sach_ban[0].datban_id
        }">${renderActionButtons(danh_sach_ban[0])}</td>
    `;

    tableBody.prepend(newRow);
}

// 📌 Hiển thị danh sách bàn (sửa lỗi danh sách bị trống)
function renderBanList(danh_sach_ban) {
    return danh_sach_ban
        .map(
            (ban) =>
                `<span class="badge bg-primary">${
                    ban.ten_ban || "Không xác định"
                }</span><br>`
        )
        .join("");
}

// 📌 Hàm cập nhật trạng thái bàn
function updateDatBanRow(event) {
    const { danh_sach_ban, customer } = event;
    if (!Array.isArray(danh_sach_ban) || danh_sach_ban.length === 0) {
        console.error("❌ Lỗi: danh_sach_ban không hợp lệ", event);
        return;
    }

    const maDatBan = danh_sach_ban[0].ma_dat_ban;
    const tableBody = document.querySelector("#tableBanDat tbody");
    let row = document.querySelector(
        `#tableBanDat tbody tr[data-id="${maDatBan}"]`
    );

    if (!row) {
        row = document.createElement("tr");
        row.setAttribute("data-id", maDatBan);
        row.classList.add("text-center");
        tableBody.prepend(row);
    }

    row.innerHTML = `
    <td class="align-middle" >${formatDateTime(
        danh_sach_ban[0]?.thoi_gian_den || "Không rõ"
    )}</td>
    <td class="align-middle" >${customer?.ho_ten || "Không rõ"}</td>
    <td class="align-middle" >${customer?.so_dien_thoai || "Không rõ"}</td>
    <td class="align-middle" >${danh_sach_ban[0]?.so_nguoi || "Không rõ"}</td>
    <td class="align-middle" >${renderBanList(danh_sach_ban)}</td>
    <td class="align-middle" >${renderStatusBadge(
        danh_sach_ban[0]?.trang_thai
    )}</td>
    <td class="align-middle"  id="action-buttons-${maDatBan}">${renderActionButtons(
        danh_sach_ban[0]
    )}</td>
`;
}

// 📌 Xóa đặt bàn khỏi bảng
function removeDatBanRow(maDatBan) {
    const row = document.querySelector(
        `#tableBanDat tbody tr[data-id="${maDatBan}"]`
    );
    if (row) row.remove();
}

// 📌 Format ngày giờ
function formatDateTime(datetime) {
    if (!datetime) return "Không rõ";
    const dateObj = new Date(datetime);
    return dateObj.toLocaleString("vi-VN", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
}

// 📌 Hiển thị trạng thái đặt bàn
function renderStatusBadge(status) {
    const statusMap = {
        xac_nhan: `<span class="badge bg-success">Đã nhận bàn</span>`,
        dang_xu_ly: `<span class="badge bg-warning">Đang xử lý</span>`,
        da_thanh_toan: `<span class="badge bg-info">Đã thanh toán</span>`,
        da_huy: `<span class="badge bg-danger">Đã hủy</span>`,
    };
    return (
        statusMap[status] || `<span class="badge bg-secondary">${status}</span>`
    );
}

// 📌 Hiển thị nút hành động (sửa lỗi để JavaScript hoạt động với Blade)
// 📌 Hiển thị nút hành động với CSRF token
function renderActionButtons(datban) {
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content");

    const today = new Date();
    const todayStr = today.toISOString().split("T")[0]; // 'YYYY-MM-DD'

    // Chuyển đổi thời gian đến thành YYYY-MM-DD
    const datbanDateStr = new Date(datban.thoi_gian_den)
        .toISOString()
        .split("T")[0];

    // Nếu không phải trạng thái đang xử lý, chỉ hiển thị nút xem
    if (datban.trang_thai !== "dang_xu_ly") {
        return `<a href="/dat-ban/${datban.ma_dat_ban}" class="btn btn-primary btn-sm">
                    <i class="fas fa-eye"></i>
                </a>`;
    }

    // Nếu trạng thái đang xử lý
    let buttons = `
        <a href="/dat-ban/${datban.ma_dat_ban}" class="btn btn-primary btn-sm">
            <i class="fas fa-eye"></i>
        </a>
        <form action="/dat-ban/${datban.ma_dat_ban}" method="post" class="d-inline-block">
            <input type="hidden" name="_token" value="${csrfToken}">
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit" class="btn btn-danger btn-sm">
                <i class="fas fa-times"></i>
            </button>
        </form>
    `;

    // Nếu đúng ngày hôm nay thì thêm nút xác nhận
    if (datbanDateStr === todayStr) {
        buttons += `
            <a href="/dat-ban/${datban.ma_dat_ban}/edit" class="btn btn-success btn-sm">
                <i class="fas fa-check"></i>
            </a>
        `;
    }

    return buttons;
}
