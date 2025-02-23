import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";


import Echo from "laravel-echo";
import Pusher from "pusher-js";

// Cấu hình Pusher và Echo cho việc lắng nghe sự kiện
window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: "pusher",

    key: "68ba0bbb9dd9a3292951", // Thay thế với key của bạn
    cluster: "ap1", // Thay thế với cluster của bạn

    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? "ap1",

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

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
//     wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });

window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: "pusher",
    key: "your_app_key", // Thay bằng key thật của bạn
    cluster: "your_app_cluster",
    forceTLS: true,
});

window.Echo.channel("table-booking").listen("TableBooked", (event) => {
    console.log("🔔 Bàn đã được đặt:", event.table);
    alert("Bàn số " + event.table.id + " đã được đặt!");
});
window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? "mt1",
    wsHost: import.meta.env.VITE_PUSHER_HOST
        ? import.meta.env.VITE_PUSHER_HOST
        : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
    wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
    wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? "https") === "https",
    enabledTransports: ["ws", "wss"],
});

