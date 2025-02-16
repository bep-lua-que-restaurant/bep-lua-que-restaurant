import "./bootstrap";
window.Echo.channel("banan-channel").listen("BanAnUpdated", (data) => {
    let updatedBan = $("#ban-" + data.id);

    if (updatedBan.length) {
        // Nếu bàn ăn đã có trên giao diện, cập nhật thông tin
        updatedBan.find(".card-title").text(data.ten_ban);

        // Xử lý class trạng thái bàn
        let badge = updatedBan.find(".badge");
        badge.removeClass(
            "badge-success badge-danger badge-warning badge-primary"
        );

        if (data.trang_thai === "trong") {
            badge.addClass("badge-success").text("Có sẵn");
        } else if (data.trang_thai === "co_khach") {
            badge.addClass("badge-warning").text("Có khách");
        } else if (data.trang_thai === "da_dat_truoc") {
            badge.addClass("badge-primary").text("Đã đặt trước");
        } else {
            badge.addClass("badge-secondary").text("Không xác định"); // Trường hợp lỗi
        }
    } else {
        // Nếu bàn ăn chưa có trên UI, gọi AJAX để tải lại danh sách
        fetchUpdatedList();
    }

    if (data.deleted) {
        // Nếu bàn ăn bị xóa mềm, xóa khỏi giao diện
        updatedBan.fadeOut(100, function () {
            $(this).remove();
        });
    } else {
        // Nếu bàn ăn chưa có trên UI, gọi AJAX để tải lại danh sách
        fetchUpdatedList();
    }
});

function fetchUpdatedList() {
    $.ajax({
        url: apiUrl,
        method: "GET",
        success: function (response) {
            $("#list-container").html(response.html);
            reinitializeSwiper();
        },
        error: function (xhr) {
            console.error("Lỗi khi tải dữ liệu:", xhr);
        },
    });
}

// thực đơn

window.Echo.channel("thucdon-channel").listen("ThucDonUpdated", (data) => {
    let updatedItem = $("#mon-" + data.id);

    if (updatedItem.length) {
        // Nếu món ăn đã có trên giao diện, cập nhật thông tin
        updatedItem.find(".card-title").text(data.ten_mon);
        updatedItem.find(".gia").text(data.gia + " đ");

        // Cập nhật hình ảnh món ăn
        updatedItem.find("img").attr("src", data.hinh_anh);

        // Kiểm tra trạng thái món ăn
        let badge = updatedItem.find(".badge");
        badge.removeClass("badge-success badge-danger");

        if (data.trang_thai === "con_hang") {
            badge.addClass("badge-success").text("Còn hàng");
        } else {
            badge.addClass("badge-danger").text("Hết hàng");
        }
    } else {
        // Nếu món ăn chưa có trên UI, gọi AJAX để tải lại danh sách thực đơn
        fetchUpdatedMenu();
    }

    if (data.deleted) {
        // Nếu món ăn bị xóa, loại bỏ khỏi giao diện
        updatedItem.fadeOut(100, function () {
            $(this).remove();
        });
    } else {
        fetchUpdatedMenu();
    }
});

function fetchUpdatedMenu() {
    $.ajax({
        url: apiUrlThucDon, // Đặt URL API của bạn ở đây
        method: "GET",
        success: function (response) {
            $("#list-container").html(response.html);
        },
        error: function (xhr) {
            console.error("Lỗi khi tải thực đơn:", xhr);
        },
    });
}

// Lắng nghe sự kiện real-time từ server
window.Echo.channel("hoa-don-channel").listen("HoaDonUpdated", (data) => {
    console.log("🔥 Hóa đơn được cập nhật real-time:", data);

    let hoaDonId = $("#ten-ban").data("hoaDonId");
    if (hoaDonId && hoaDonId == data.id) {
        console.log("🔄 Cập nhật danh sách hóa đơn...");
        fetchUpdatedHoaDon(hoaDonId);
    }
});

function fetchUpdatedHoaDon(hoaDonId) {
    $.ajax({
        url: apiUrlChiTietHoaDon,
        method: "GET",
        data: { hoa_don_id: hoaDonId },
        success: function (response) {
            $("#hoa-don-body").html(response.html);
        },
        error: function (xhr) {
            console.error("🔥 Lỗi khi tải hóa đơn:", xhr.responseText);
        },
    });
}
