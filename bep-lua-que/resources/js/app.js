
import './bootstrap';
import './thungan.js';  // Đảm bảo thungan.js thực sự có trong thư mục resources/js



// Import axios để gửi các request HTTP
import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// Import Echo và Pusher
import Echo from "laravel-echo";
import Pusher from "pusher-js";

// Cấu hình Pusher và Echo
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: "68ba0bbb9dd9a3292951", // Thay thế với key của bạn
    cluster: "ap1", // Thay thế với cluster của bạn
    forceTLS: true,
    encrypted: true,
    app_id: "1943319", // Thay thế với app_id của bạn
    secret: "d4315086e5a2434725d7", // Thay thế với secret của bạn
});

// Lắng nghe các sự kiện từ channel 'datban-channel'
window.Echo.channel("datban-channel")
    .listen("DatBanStored", (event) => {
        console.log("Đã nhận sự kiện DatBanStored:", event);
        // Logic cập nhật UI khi nhận sự kiện DatBanStored
        alert("Đặt bàn mới đã được lưu!");
    })
    .listen("DatBanUpdated", (event) => {
        console.log("Đã nhận sự kiện DatBanUpdated:", event);
        // Logic cập nhật UI khi nhận sự kiện DatBanUpdated
        alert("Đặt bàn đã được cập nhật!");
    })
    .listen("DatBanDeleted", (event) => {
        console.log("Đã nhận sự kiện DatBanDeleted:", event);
        // Logic cập nhật UI khi nhận sự kiện DatBanDeleted
        alert("Đặt bàn đã bị xóa!");
    });
