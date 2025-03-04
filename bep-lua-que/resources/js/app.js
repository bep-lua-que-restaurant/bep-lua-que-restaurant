import './bootstrap';
import "./thungan.js"; // Đảm bảo thungan.js thực sự có trong thư mục resources/js
import "./datban.js";
document.addEventListener("DOMContentLoaded", () => {
    console.log("🚀 Ứng dụng đã tải xong!");
    
    try {
        const channel = window.Echo.channel("bep-channel");
        console.log("🔍 Đăng ký kênh:", channel);
        
        channel.listen(".mon-moi-duoc-them", (data) => {
            console.log("🔥 Món mới được thêm:", data);
        });
    } catch (error) {
        console.error("❌ Lỗi khi đăng ký kênh:", error);
    }
});


