import "./bootstrap";
import dayjs from "dayjs";
import isBetween from "dayjs/plugin/isBetween";

// Lắng nghe sự kiện từ Laravel Echo
window.Echo.channel("banan-channel").listen("BanAnUpdated", (data) => {
    // console.log("Có thông báo mới từ server:", data);

    // Duyệt qua tất cả các button với class .selectable-slot
    document.querySelectorAll(".text-center").forEach((button) => {
        const banIdButton = button.getAttribute("data-ban-id"); // Lấy data-ban-id của button

        // Kiểm tra nếu banIdButton trùng với id nhận được từ sự kiện
        if (banIdButton == data.id) {
            if (data.trang_thai === "co_khach") {
                button.classList.add("bg-info"); // Nếu trạng thái là 'co_khach', thêm class bg-info
            } else if (data.trang_thai === "trong") {
                button.classList.remove("bg-info"); // Nếu trạng thái là 'trong', xóa class bg-info
            }
        }
    });
});

// window.Echo.channel("datban-channel").listen("DatBanCreated", (event) => {
//     // alert("Nhan su kien thanh cong");
//     console.log("Sự kiện nhận được:", event);
// });

// Kích hoạt plugin isBetween
// dayjs.extend(isBetween);

// Giả sử bạn đã nhận được sự kiện (event) từ Pusher
window.Echo.channel("datban-channel").listen("DatBanCreated", (event) => {
    // Dữ liệu từ sự kiện
    const danhSachBan = event.danh_sach_ban;
    //console.log("Sự kiện nhận được:", event); // Log sự kiện nhận được

    // Mảng để lưu các giá trị đã tách
    const banAnId = [];
    const maDatBan = [];
    const date = [];
    const gioBatDau = [];
    const gioDuKien = [];

    // Duyệt qua danh sách bàn và xử lý dữ liệu
    danhSachBan.forEach((item) => {
        // Lấy và chuyển đổi thời gian thoi_gian_den
        const thoiGianDen = dayjs(item.thoi_gian_den);
        const thoiGianDenFormat = thoiGianDen.format("YYYY-MM-DD"); // Định dạng y-m-d
        const gioBatDauFormat = thoiGianDen.format("HH:mm"); // Định dạng h:i

        // Lấy và chuyển đổi thời gian gio_du_kien
        const gioDuKienFormat = dayjs(
            `${thoiGianDen.format("YYYY-MM-DD")} ${item.gio_du_kien}`
        ).format("HH:mm"); // Định dạng h:i

        // Đẩy vào các mảng
        banAnId.push(item.ban_an_id);
        maDatBan.push(item.ma_dat_ban);
        date.push(thoiGianDenFormat);
        gioBatDau.push(gioBatDauFormat);
        gioDuKien.push(gioDuKienFormat);
    });

    // Log kết quả đã tách
    // console.log("ban_an_id:", banAnId);
    // console.log("ma_dat_ban:", maDatBan);
    // console.log("date:", date);
    // console.log("gio_bat_dau:", gioBatDau);
    // console.log("gio_du_kien:", gioDuKien);

    // Duyệt qua các button trên trang để thay đổi class khi có sự kiện
    document.querySelectorAll(".selectable-slot").forEach((button) => {
        const maDatBanButton = button.getAttribute("data-ma-dat-ban");
        const banIdButton = button.getAttribute("data-ban-id");
        const timeSlotButton = button.getAttribute("data-time-slot");
        const dateButton = button.getAttribute("data-date");

        // console.log("Button data-ma-dat-ban:", maDatBanButton);
        // console.log("Button data-ban-id:", banIdButton);
        // console.log("Button data-time-slot:", timeSlotButton);
        // console.log("Button data-date:", dateButton);

        // Duyệt qua dữ liệu danh sách đặt bàn
        for (let i = 0; i < maDatBan.length; i++) {
            // Kiểm tra trùng khớp giữa dữ liệu từ button và danh sách đặt bàn
            if (banIdButton == banAnId[i] && dateButton == date[i]) {
                const timeSlotDate = dayjs(`${dateButton} ${timeSlotButton}`);
                const gioBatDauDate = dayjs(`${dateButton} ${gioBatDau[i]}`);
                const gioDuKienDate = dayjs(`${dateButton} ${gioDuKien[i]}`);

                // console.log("timeSlotDate:", timeSlotDate.format());
                // console.log("gioBatDauDate:", gioBatDauDate.format());
                // console.log("gioDuKienDate:", gioDuKienDate.format());

                // Kiểm tra xem timeSlot nằm trong khoảng giữa gioBatDau và gioDuKien
                if (
                    timeSlotDate.isAfter(gioBatDauDate) &&
                    timeSlotDate.isBefore(gioDuKienDate)
                ) {
                    // Thêm class btn-info vào button
                    button.classList.remove("bg-light");

                    button.classList.remove("btn-info");
                    button.classList.add("btn-danger");

                    // Cập nhật giá trị data-ma-dat-ban của button
                    button.setAttribute("data-ma-dat-ban", maDatBan[i]);

                    // console.log("Thêm class btn-info cho button:", button);

                    break; // Khi trùng khớp thì thoát khỏi vòng lặp
                } else {
                    // console.log(
                    //     "Không thỏa mãn điều kiện thời gian: ",
                    //     timeSlotDate.format()
                    // );
                }
            } else {
                // console.log(
                //     "Không trùng với dữ liệu đặt bàn: ",
                //     banIdButton,
                //     dateButton
                // );
            }
        }
    });
});
