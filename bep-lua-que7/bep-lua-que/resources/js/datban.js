import "./bootstrap";
import dayjs from "dayjs";
import isSameOrAfter from "dayjs/plugin/isSameOrAfter";
import isSameOrBefore from "dayjs/plugin/isSameOrBefore";

dayjs.extend(isSameOrAfter);
dayjs.extend(isSameOrBefore);

// 📢 Lắng nghe sự kiện cập nhật trạng thái bàn ăn
window.Echo.channel("banan-channel").listen("BanAnUpdated", (data) => {
    console.log("🔄 Cập nhật trạng thái bàn ăn:", data);
    updateBanStatus(data);
});

// 📌 Hàm cập nhật trạng thái bàn ăn
function updateBanStatus(data) {
    document.querySelectorAll(".text-center").forEach((button) => {
        if (button.getAttribute("data-ban-id") == data.id) {
            button.classList.remove("bg-info"); // Xóa trạng thái cũ

            if (data.trang_thai === "co_khach") {
                button.classList.add("bg-info");
            }
        }
    });

    // Nếu bàn trống, kiểm tra & reset các slot
    if (data.trang_thai === "trong") {
        document.querySelectorAll(".selectable-slot").forEach((button) => {
            if (button.getAttribute("data-ban-id") == data.id) {
                resetButton(button);
            }
        });
    }
}

// 📢 Lắng nghe các sự kiện đặt bàn
window.Echo.channel("datban-channel")
    .listen("DatBanCreated", (event) => {
        console.log("🆕 Sự kiện đặt bàn mới:", event);
        event.danh_sach_ban.forEach((item) => {
            updateButtonState(item);
        });
    })
    .listen("DatBanUpdated", (event) => {
        console.log("🔄 Sự kiện cập nhật đặt bàn:", event);
        event.danh_sach_ban.forEach((item) => {
            updateButtonState(item);
        });
    })
    .listen("DatBanDeleted", (event) => {
        console.log("🗑 Sự kiện xóa đặt bàn nhận được:", event);
        const maDatBan = event.maDatBan;
        document.querySelectorAll(".selectable-slot").forEach((button) => {
            if (button.getAttribute("data-ma-dat-ban") === maDatBan) {
                resetButton(button);
            }
        });
    });

// 📌 Hàm cập nhật trạng thái button đặt bàn
function updateButtonState(item) {
    const banAnId = item.ban_an_id;
    const maDatBan = item.ma_dat_ban;
    const date = dayjs(item.thoi_gian_den).format("YYYY-MM-DD");
    const gioBatDau = dayjs(item.thoi_gian_den).format("HH:mm");
    const gioDuKien = dayjs(`${date} ${item.gio_du_kien}`).format("HH:mm");

    document.querySelectorAll(".selectable-slot").forEach((button) => {
        const buttonBanId = button.getAttribute("data-ban-id");
        const buttonDate = button.getAttribute("data-date");
        const buttonTimeSlot = button.getAttribute("data-time-slot");

        if (buttonBanId == banAnId && buttonDate == date) {
            const timeSlotDate = dayjs(`${date} ${buttonTimeSlot}`);
            const gioBatDauDate = dayjs(`${date} ${gioBatDau}`);
            const gioDuKienDate = dayjs(`${date} ${gioDuKien}`);

            if (
                timeSlotDate.isSameOrAfter(gioBatDauDate) &&
                timeSlotDate.isSameOrBefore(gioDuKienDate)
            ) {
                button.classList.remove(
                    "bg-light",
                    "btn-danger",
                    "btn-success"
                );

                // Xác định trạng thái của đơn đặt bàn
                if (item.trang_thai === "dang_xu_ly") {
                    button.classList.add("btn-danger"); // Đơn mới tạo
                } else if (item.trang_thai === "xac_nhan") {
                    button.classList.add("btn-success"); // Đơn đã xác nhận
                }

                // Cập nhật data-ma-dat-ban
                button.setAttribute("data-ma-dat-ban", maDatBan);
            }
        }
    });
}

// 📌 Hàm reset trạng thái button
function resetButton(button) {
    button.classList.remove(
        "btn-success",
        "btn-danger",
        "btn-warning",
        "bg-info"
    );
    button.classList.add("bg-light");
    button.removeAttribute("data-ma-dat-ban");
    button.removeAttribute("data-bs-title");
    button.removeAttribute("title");
}
