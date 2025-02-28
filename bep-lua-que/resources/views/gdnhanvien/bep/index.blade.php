<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giao diện bếp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.2/echo.iife.min.js"></script>


    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">


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
        .navbar-toggler {
            border: none;
            background: none;
            font-size: 30px;
        }

        .navbar-toggler-icon {
            color: #fff;
        }

        /* Căn menu bên phải */
        .navbar-nav {
            margin-left: auto;
        }

        /* Dropdown menu khi nhấn vào icon 3 gạch */
        .dropdown-menu {
            position: absolute;
            top: 50px; /* Điều chỉnh cho phù hợp */
            right: 0;
        }

    </style>
</head>

<body>
    <div class="container mt-4">
        <nav class="navbar navbar-light fixed-top pb-5">
            <div class="dropdown ms-auto">
                <!-- Icon 3 gạch -->
                <button class="btn navbar-toggler" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bars"></i>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="{{route('thungan.getBanAn')}}"><i class="fas fa-cash-register"></i></a></li> <!-- Icon thu ngân -->
                    <li><a class="dropdown-item" href=""><i class="fas fa-concierge-bell"></i></a></li> <!-- Icon lễ tân -->
                    <li><a class="dropdown-item" href="/"><i class="fas fa-tachometer-alt"></i></a></li> <!-- Icon dashboard -->
                </ul>
            </div>
        </nav>
        
        <div class="row mt-5" style="margin-top: 600px">
            <!-- Cột Trái: Chờ chế biến -->
            <div class="col-md-6">
                <div class="container-custom">
                    <h5 class="text-primary">Chờ chế biến</h5>

                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs" id="tabMenu">
                        <li class="nav-item">
                            <a class="nav-link active" id="tab-1" href="javascript:void(0)"
                                onclick="switchTab('tab-1')">Món ưu tiên</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-2" href="javascript:void(0)"
                                onclick="switchTab('tab-2')">Theo món</a>
                        </li>
                    </ul>

                    <!-- Content of Tabs -->
                    <div class="tab-content">
                        <!-- Tab 1 - Món ưu tiên -->
                        <div class="list-group tab-pane show active" id="tab-1-content">
                            <div id="cho-che-bien-list">
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

                        <!-- Tab 2 - Theo món -->
                        <div class="list-group tab-pane" id="tab-2-content">
                            <div id="mon-theo-món-list">
                                @foreach ($monAnTheoMon as $mon)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $mon->monAn ? $mon->monAn->ten : 'Không xác định' }}</strong> -
                                            <span class="text-success">Tổng số lượng: {{ $mon->total_so_luong }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
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

            // Kiểm tra xem ID có hợp lệ không
            if (!id) {
                console.error("ID món ăn không hợp lệ");
                return;
            }

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
        window.Pusher = Pusher; // Đảm bảo Pusher đã được định nghĩa

        window.Echo = new Echo({
            broadcaster: "pusher",
            key: "{{ env('PUSHER_APP_KEY') }}",
            cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
            forceTLS: true,
            encrypted: true
        });



        // Kiểm tra xem Echo có được khởi tạo chưa
        console.log(window.Echo);
        console.log(window.Echo.connector); // Kiểm tra lại



        var channel = window.Echo.channel("bep-channel");

        channel.listen(".trang-thai-cap-nhat", function(data) {
            console.log("🔥 Cập nhật trạng thái món:", data);
            moveDish(data.monAn.id, data.monAn.trang_thai);
        });










        // Nhận sự kiện món mới được thêm vào
        window.Echo.channel("bep-channel")
            .listen(".mon-moi-duoc-them", (data) => {
                console.log("🔥 Món mới nhận được từ Pusher:", data);
                if (data && data.monAn) {
                    console.log("📌 Món ăn nhận được:", data.monAn);
                    const monAn = data.monAn;
                    const banAn = monAn.ban;

                    // Kiểm tra ID món ăn
                    if (monAn.id) {
                        addNewDish(monAn, banAn);
                    } else {
                        console.error("ID món ăn không hợp lệ:", monAn);
                    }
                } else {
                    console.error("Dữ liệu món ăn không hợp lệ");
                }
            });







        // Hàm thêm món mới vào danh sách "Chờ chế biến"
        function addNewDish(monAn, banAn) {
            if (!monAn.id) {
                console.error("ID món ăn không hợp lệ khi thêm mới món");
                return;
            }

            let newDish = document.createElement("div");
            newDish.id = `dish-${monAn.id}`; // Đảm bảo ID món ăn được gán đúng
            newDish.className = "list-group-item d-flex justify-content-between align-items-center";
            newDish.innerHTML = `
        <div>
            <strong>${monAn.ten}</strong> - 
            ${banAn ? "Bàn: " + banAn : '<span class="text-danger">Chưa có bàn</span>'}
            <br> <small>Số lượng: ${monAn.so_luong}</small>
        </div>
        <div class="status-buttons">
            <button class="btn btn-warning btn-sm status-btn"
                onclick="updateStatus(${monAn.id}, 'dang_nau')">
                Đang nấu
            </button>
        </div>
    `;

            // Thêm món mới vào danh sách "Chờ chế biến"
            document.getElementById("cho-che-bien-list").appendChild(newDish);
        }
    </script>

</body>

</html>
<script>
    // Hàm chuyển đổi giữa các tab
    function switchTab(tabId) {
        // Ẩn tất cả nội dung của các tab
        let tabs = document.querySelectorAll('.tab-pane');
        tabs.forEach(tab => {
            tab.classList.remove('show', 'active');
        });

        // Ẩn tất cả các tab menu
        let tabLinks = document.querySelectorAll('.nav-link');
        tabLinks.forEach(tabLink => {
            tabLink.classList.remove('active');
        });

        // Hiển thị tab mới
        document.getElementById(tabId + '-content').classList.add('show', 'active');
        document.getElementById(tabId).classList.add('active');
    }
</script>
