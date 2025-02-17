<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giao diện bếp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>

    <style>
        body {
            background-color: #004080;
        }

        .container-custom {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            height: 80vh;
            overflow-y: auto;
        }

        .status-btn {
            min-width: 100px;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <div class="row">
            <!-- Cột Trái: Chờ chế biến -->
            <div class="col-md-6">
                <div class="container-custom">
                    <h5 class="text-primary">Chờ chế biến</h5>
                    <div class="list-group" id="cho-che-bien-list">
                        @foreach ($monAnChoCheBien as $mon)
                            <div id="dish-{{ $mon->id }}"
                                class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $mon->monAn ? $mon->monAn->ten : 'Không xác định' }}</strong> -
                                    @if ($mon->hoaDon && $mon->hoaDon->banAns->isNotEmpty())
                                        @foreach ($mon->hoaDon->banAns as $ban)
                                            Bàn {{ $ban->ten_ban }}@if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    @else
                                        <span class="text-danger">Chưa có bàn</span>
                                    @endif
                                    <br> <small>Số lượng: {{ $mon->so_luong }}</small>
                                </div>
                                <div class="status-buttons">
                                    <button class="btn btn-warning btn-sm status-btn"
                                        onclick="updateStatus({{ $mon->id }}, 'dang_nau')">
                                        Đang nấu
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Cột Phải: Đang nấu -->
            <div class="col-md-6">
                <div class="container-custom">
                    <h5 class="text-primary">Đang nấu</h5>
                    <div class="list-group" id="dang-nau-list">
                        @foreach ($monAnDangNau as $mon)
                            <div id="dish-{{ $mon->id }}"
                                class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $mon->monAn ? $mon->monAn->ten : 'Không xác định' }}</strong> -
                                    @if ($mon->hoaDon && $mon->hoaDon->banAns->isNotEmpty())
                                        @foreach ($mon->hoaDon->banAns as $ban)
                                            Bàn {{ $ban->ten_ban }}@if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    @else
                                        <span class="text-danger">Chưa có bàn</span>
                                    @endif
                                    <br> <small>Số lượng: {{ $mon->so_luong }}</small>
                                </div>
                                <div class="status-buttons">
                                    <button class="btn btn-success btn-sm status-btn"
                                        onclick="updateStatus({{ $mon->id }}, 'hoan_thanh')">
                                        Hoàn thành
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Laravel Echo -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.2/echo.iife.min.js"></script>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        function updateStatus(id, status) {
            let message = status === "dang_nau" ? "Bạn có chắc muốn bắt đầu nấu món này?" : "Món này đã hoàn thành?";
            if (!confirm(message)) return;

            fetch(`/bep/update/${id}`, {
                    method: "PUT",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        "Content-Type": "application/json",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({
                        trang_thai: status
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        moveDish(id, status);
                    } else {
                        alert("Cập nhật thất bại: " + data.message);
                    }
                })
                .catch(error => console.error("Lỗi cập nhật:", error));
        }


        function moveDish(id, newStatus) {
            let dishElement = document.getElementById(`dish-${id}`);
            if (!dishElement) return;

            // Xóa món khỏi danh sách cũ
            dishElement.remove();

            // Thêm vào danh sách mới
            if (newStatus === "dang_nau") {
                document.getElementById("dang-nau-list").appendChild(dishElement);
            } else if (newStatus === "hoan_thanh") {
                return; // Nếu hoàn thành thì ẩn đi
            }

            // Cập nhật lại nút bấm
            let buttonContainer = dishElement.querySelector(".status-buttons");
            buttonContainer.innerHTML = newStatus === "dang_nau" ?
                `<button class="btn btn-success btn-sm status-btn" onclick="updateStatus(${id}, 'hoan_thanh')">Hoàn thành</button>` :
                "";

        }

        // Kết nối với Pusher
        var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
            cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
            forceTLS: true
        });


        var channel = pusher.subscribe("bep-channel");

        channel.bind("trang-thai-cap-nhat", function(data) {
            console.log("🔥 Cập nhật trạng thái món:", data);

            moveDish(data.monAn.id, data.monAn.trang_thai);
        });




        // Nhận sự kiện món mới được thêm vào
        window.Echo.channel("bep-channel")
            .listen(".mon-moi-duoc-them", (data) => {
                console.log("🔥 Món mới được thêm:", data);
                addNewDish(data.monAn);
            });

        function addNewDish(mon) {
            let newDish = document.createElement("div");
            newDish.id = `dish-${mon.id}`;
            newDish.className = "list-group-item d-flex justify-content-between align-items-center";
            newDish.innerHTML = `
        <div>
            <strong>${mon.mon_an.ten}</strong> - 
            ${mon.hoa_don && mon.hoa_don.ban_ans.length > 0 
                ? "Bàn " + mon.hoa_don.ban_ans.map(ban => ban.ten_ban).join(", ") 
                : '<span class="text-danger">Chưa có bàn</span>'}
            <br> <small>Số lượng: ${mon.so_luong}</small>
        </div>
        <div class="status-buttons">
            <button class="btn btn-warning btn-sm status-btn"
                onclick="updateStatus(${mon.id}, 'dang_nau')">
                Đang nấu
            </button>
        </div>
    `;

            document.getElementById("cho-che-bien-list").appendChild(newDish);
        }
    </script>

</body>

</html>
