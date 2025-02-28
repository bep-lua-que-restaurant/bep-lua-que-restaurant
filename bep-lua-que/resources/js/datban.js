import "./bootstrap";

window.Echo.channel("datban-channel").listen("DatBanCreated", (e) => {
    console.log("Sự kiện nhận được:", e);
});
