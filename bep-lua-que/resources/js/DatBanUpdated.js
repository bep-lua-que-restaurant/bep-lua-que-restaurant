import "./bootstrap";
import dayjs from "dayjs";
import isSameOrAfter from "dayjs/plugin/isSameOrAfter";
import isSameOrBefore from "dayjs/plugin/isSameOrBefore";
dayjs.extend(isSameOrAfter);
dayjs.extend(isSameOrBefore);

window.Echo.channel("datban-channel").listen("DatBanUpdated", (event) => {
    console.log("Sự kiện cập nhật đặt bàn nhận được:", event);

    const danhSachBan = event.danh_sach_ban;

    danhSachBan.forEach((item) => {
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
                    timeSlotDate.isSameOrBefore(gioDuKienDate) // Cho phép cả ô 13:30
                ) {
                    console.log(
                        `✅ Đổi màu cho slot: ${buttonTimeSlot} (Bàn ${banAnId})`
                    );

                    // Xóa các class cũ
                    button.classList.remove(
                        "bg-light",
                        "btn-danger",
                        "btn-warning"
                    );

                    // Thêm class mới
                    button.classList.add("btn-success");

                    // Cập nhật data-ma-dat-ban
                    button.removeAttribute("data-ma-dat-ban");
                    button.setAttribute("data-ma-dat-ban", maDatBan);
                } else {
                    // console.log(`❌ Không đổi màu cho slot: ${buttonTimeSlot}`);
                }
            }
        });
    });
});
