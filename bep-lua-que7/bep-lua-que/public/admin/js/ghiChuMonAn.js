$(document).on("click", ".toggle-ghi-chu", function (event) {
    const ghiChuWrapper = $(this).siblings(".ghi-chu-wrapper");

    // Kiểm tra nếu ghi chú đang mở, click vào nút ghi chú sẽ đóng
    ghiChuWrapper.stop(true, true).slideToggle(200);

    // Ngừng sự kiện để không làm nó bắn ra ngoài
    event.stopPropagation();
});

// Đóng ghi chú khi click ra ngoài
$(document).on("click", function (event) {
    // Kiểm tra nếu click ra ngoài phần tử ghi chú và không phải nút ghi chú
    if (!$(event.target).closest(".ghi-chu-wrapper, .toggle-ghi-chu").length) {
        $(".ghi-chu-wrapper").slideUp(200); // Ẩn tất cả các ghi chú
    }
});

$(document).on("click", ".save-ghi-chu", function () {
    const parent = $(this).closest(".ghi-chu-wrapper");
    const input = parent.find(".ghi-chu-input");
    const id = input.data("id");
    const ghiChu = input.val();

    if (ghiChu.trim() === "") {
        parent.slideUp(200);
        return;
    }

    // Thêm lớp spin để tạo hiệu ứng xoay
    const saveIcon = $(this); // Biểu tượng chữ V
    saveIcon.addClass("spin");
    parent.slideUp(200);

    // console.log(id, ghiChu);
    $.ajax({
        url: "thu-ngan/luu-ghi-chu-mon", // thay bằng route thực tế
        method: "POST",
        data: {
            id_chi_tiet: id,
            ghi_chu: ghiChu,
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (res) {
            // console.log(res);
            saveIcon.removeClass('spin');
            parent.slideUp(200); // Ẩn lại sau khi lưu
        },
        error: function () {
            toastr.error("Lỗi khi lưu ghi chú");

            saveIcon.removeClass('spin');
        },
    });
});
