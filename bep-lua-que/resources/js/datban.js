import "./bootstrap";
import dayjs from "dayjs";

// Lắng nghe sự kiện từ Laravel Echo
window.Echo.channel("banan-channel").listen("BanAnUpdated", (data) => {
    // Cập nhật trạng thái bàn
    updateBanStatus(data);

    // Kiểm tra và xóa class btn-success nếu cần
    removeSuccessClass(data);
    removeWarningClass(data);
});

// Hàm cập nhật trạng thái bàn
function updateBanStatus(data) {
    document.querySelectorAll(".text-center").forEach((button) => {
        if (button.getAttribute("data-ban-id") == data.id) {
            if (data.trang_thai === "co_khach") {
                button.classList.add("bg-info");
            } else if (data.trang_thai === "trong") {
                button.classList.remove("bg-info");
            }
        }
    });
}

// Hàm kiểm tra và xóa class btn-success
function removeSuccessClass(data) {
    document.querySelectorAll(".selectable-slot").forEach((button) => {
        if (
            button.getAttribute("data-ban-id") == data.id &&
            button.classList.contains("btn-success")
        ) {
            button.classList.remove("btn-success");
        }
    });
}

function removeWarningClass(data) {
    document.querySelectorAll(".selectable-slot").forEach((button) => {
        if (
            button.getAttribute("data-ban-id") == data.id &&
            button.classList.contains("btn-warning")
        ) {
            button.classList.remove("btn-warning");
        }
    });
}

// Lắng nghe sự kiện đặt bàn
window.Echo.channel("datban-channel").listen("DatBanCreated", (event) => {
    const danhSachBan = event.danh_sach_ban;

    const banAnId = [];
    const maDatBan = [];
    const date = [];
    const gioBatDau = [];
    const gioDuKien = [];

    danhSachBan.forEach((item) => {
        const thoiGianDen = dayjs(item.thoi_gian_den);
        const thoiGianDenFormat = thoiGianDen.format("YYYY-MM-DD");
        const gioBatDauFormat = thoiGianDen.format("HH:mm");
        const gioDuKienFormat = dayjs(
            `${thoiGianDen.format("YYYY-MM-DD")} ${item.gio_du_kien}`
        ).format("HH:mm");

        banAnId.push(item.ban_an_id);
        maDatBan.push(item.ma_dat_ban);
        date.push(thoiGianDenFormat);
        gioBatDau.push(gioBatDauFormat);
        gioDuKien.push(gioDuKienFormat);
    });

    document.querySelectorAll(".selectable-slot").forEach((button) => {
        const maDatBanButton = button.getAttribute("data-ma-dat-ban");
        const banIdButton = button.getAttribute("data-ban-id");
        const timeSlotButton = button.getAttribute("data-time-slot");
        const dateButton = button.getAttribute("data-date");

        for (let i = 0; i < maDatBan.length; i++) {
            if (banIdButton == banAnId[i] && dateButton == date[i]) {
                const timeSlotDate = dayjs(`${dateButton} ${timeSlotButton}`);
                const gioBatDauDate = dayjs(`${dateButton} ${gioBatDau[i]}`);
                const gioDuKienDate = dayjs(`${dateButton} ${gioDuKien[i]}`);

                if (
                    timeSlotDate.isAfter(gioBatDauDate) &&
                    timeSlotDate.isBefore(gioDuKienDate)
                ) {
                    button.classList.remove("bg-light");
                    button.classList.remove("btn-info");
                    button.classList.add("btn-danger"); // Chuyển thành btn-success
                    button.setAttribute("data-ma-dat-ban", maDatBan[i]);
                    break;
                }
            }
        }
    });
});
