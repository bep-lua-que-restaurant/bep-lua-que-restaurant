import "./bootstrap";

window.Echo.channel("datban-channel").listen("DatBanCreated", (e) => {
    alert("Có sự kiện mới của đặt bàn !");
    console.log("Sự kiện nhận được:", e);
});
