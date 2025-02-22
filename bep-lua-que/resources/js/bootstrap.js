import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// Import Echo và Pusher
import Echo from "laravel-echo";
import Pusher from "pusher-js";

// Cấu hình Pusher và Echo cho việc lắng nghe sự kiện
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

// Lắng nghe sự kiện trên channel 'datban-channel'
window.Echo.channel("datban-channel")
    .listen("DatBanStored", (event) => {
        console.log("Đã nhận sự kiện DatBanStored:", event);
        // Cập nhật giao diện khi nhận sự kiện DatBanStored
    })
    .listen("DatBanUpdated", (event) => {
        console.log("Đã nhận sự kiện DatBanUpdated:", event);
        // Cập nhật giao diện khi nhận sự kiện DatBanUpdated
    })
    .listen("DatBanDeleted", (event) => {
        console.log("Đã nhận sự kiện DatBanDeleted:", event);
        // Cập nhật giao diện khi nhận sự kiện DatBanDeleted
    });
