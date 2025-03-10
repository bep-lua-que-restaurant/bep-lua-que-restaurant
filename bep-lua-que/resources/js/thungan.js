import "./bootstrap";

// Lấy danh sách bàn ăn khi click vào nút "Bàn ăn"
document.getElementById("btn-ban-an").addEventListener("click", function () {
    fetch(apiUrlGetBanAn, {
        headers: {
            "X-Requested-With": "XMLHttpRequest",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            document.getElementById("list-container").innerHTML = data.html;
        })
        .catch((error) => console.error("Lỗi:", error));
});

// Lấy danh sách món ăn khi click vào nút "Món ăn"
document.getElementById("btn-thuc-don").addEventListener("click", function () {
    fetch(apiUrlGetThucDon, {
        headers: {
            "X-Requested-With": "XMLHttpRequest",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            document.getElementById("list-container").innerHTML = data.html;
        })
        .catch((error) => console.error("Lỗi:", error));
});

// Bộ lọc
$(document).ready(function () {
    let currentType = "ban"; // Mặc định hiển thị danh sách bàn

    function fetchFilteredData() {
        let searchQuery = $("#search-name").val();
        let statusFilter = $("#statusFilter").val();
        let selectedRoom = $("input[name='filter-room']:checked").val(); // Lấy phòng được chọn
        $("#list-container").html(
            '<div class="text-center">Đang tải dữ liệu...</div>'
        );

        $.ajax({
            url: currentType === "ban" ? apiUrlGetBanAn : apiUrlGetThucDon,
            method: "GET",
            data: {
                ten: searchQuery,
                statusFilter: currentType === "ban" ? statusFilter : null,
                vi_tri: currentType === "ban" ? selectedRoom : null, // Gửi phòng lên server
            },
            success: function (response) {
                $("#list-container").html(response.html);
            },
            error: function (xhr) {
                console.error("Lỗi khi tải dữ liệu:", xhr);
            },
        });
    }

    $("#search-name").on("input", function () {
        fetchFilteredData();
    });

    $("#statusFilter").on("change", function () {
        if (currentType === "ban") {
            fetchFilteredData();
        }
    });

    $("input[name='filter-room']").on("change", function () {
        if (currentType === "ban") {
            fetchFilteredData();
        }
    });

    $("#btn-ban-an").on("click", function () {
        currentType = "ban";
        $("#statusFilter").show(); // Hiện bộ lọc trạng thái bàn
        fetchFilteredData();
    });

    $("#btn-thuc-don").on("click", function () {
        currentType = "menu";
        $("#statusFilter").hide(); // Ẩn bộ lọc trạng thái bàn
        fetchFilteredData();
    });

    fetchFilteredData();
});

// Thông báo cho bếp

$(document).ready(function () {
    $(".btn-thong-bao").on("click", function () {
        let hoaDonId = $("#ten-ban").data("hoaDonId");

        if (!hoaDonId) {
            alert("Không tìm thấy hóa đơn cho bàn này!");
            return;
        }

        $.ajax({
            url: apiUrlThongBaoBep,

            method: "POST",
            data: {
                hoa_don_id: hoaDonId,
                _token: $("meta[name='csrf-token']").attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    var dingSound = new Audio(dingSoundUrl);
                    dingSound.play();
                    showToast("Đã gửi thông báo đến bếp!", "success");
                } else {
                    alert("Có lỗi xảy ra, vui lòng thử lại!");
                }
            },
            error: function (xhr) {
                console.error("🔥 Lỗi cập nhật trạng thái:", xhr.responseText);
                alert("Không thể cập nhật trạng thái!");
            },
        });
    });
});
//

window.Echo.channel("datban-channel").listen("DatBanCreated", (e) => {
    let banId = e.ban_an_id; // Nhận ID bàn ăn từ Laravel
    let icon = document.getElementById(`icon-${banId}`);

    if (icon) {
        icon.classList.remove("d-none"); // Hiển thị icon 🔔
    }
});
