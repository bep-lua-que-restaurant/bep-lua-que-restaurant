import "./bootstrap";
import dayjs from "dayjs";
import isSameOrAfter from "dayjs/plugin/isSameOrAfter";
import isSameOrBefore from "dayjs/plugin/isSameOrBefore";
dayjs.extend(isSameOrAfter);
dayjs.extend(isSameOrBefore);

window.Echo.channel("datban-channel").listen("DatBanUpdated", (event) => {
    console.log("Sự kiện cập nhật đặt bàn nhận được:", event);

    const danhSachBan = event.danh_sach_ban;
    const maDatBanSet = new Set(danhSachBan.map((item) => item.ma_dat_ban));

    // Reset tất cả các button có ma_dat_ban trùng với event
    document.querySelectorAll(".selectable-slot").forEach((button) => {
        const buttonMaDatBan = button.getAttribute("data-ma-dat-ban");
        if (maDatBanSet.has(buttonMaDatBan)) {
            button.classList.remove("btn-success", "btn-danger", "btn-warning");
            button.classList.add("bg-light");

            // 🛑 Kiểm tra nếu tooltip tồn tại trước khi gọi .dispose()
            const tooltipInstance = bootstrap.Tooltip.getInstance(button);
            if (tooltipInstance !== null && tooltipInstance !== undefined) {
                tooltipInstance.dispose(); // Xóa tooltip nếu có
            }

            // ✅ Xóa thuộc tính data
            button.removeAttribute("data-ma-dat-ban");
            button.removeAttribute("data-bs-title");
            button.removeAttribute("title"); // Xóa luôn title nếu Bootstrap còn giữ

            // Log kiểm tra
            console.log(`✅ Đã reset button:`, {
                button,
                "Class sau khi xóa": button.classList,
                "data-ma-dat-ban": button.getAttribute("data-ma-dat-ban"),
                "data-bs-title": button.getAttribute("data-bs-title"),
                title: button.getAttribute("title"),
            });
        }
    });

    // Kiểm tra và cập nhật lại màu
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
                    timeSlotDate.isSameOrBefore(gioDuKienDate)
                ) {
                    // console.log(
                    //     `✅ Đổi màu cho slot: ${buttonTimeSlot} (Bàn ${banAnId})`
                    // );

                    button.classList.remove("bg-light");
                    button.classList.add("btn-success");

                    // Cập nhật data-ma-dat-ban
                    button.setAttribute("data-ma-dat-ban", maDatBan);
                }
            }
        });
    });
});
