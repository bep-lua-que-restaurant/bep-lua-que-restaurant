<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giao diện bếp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

        .navbar-nav {
            margin-left: auto;
        }

        .dropdown-menu {
            position: absolute;
            top: 50px;
            right: 0;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <nav class="navbar navbar-light fixed-top pb-5">
            <div class="dropdown ms-auto">
                <button class="btn navbar-toggler" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-bars"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href=""><i class="fas fa-concierge-bell"></i></a></li>
                    <li><a class="dropdown-item" href="/"><i class="fas fa-tachometer-alt"></i></a></li>
                </ul>
            </div>
        </nav>

        <div class="row mt-5">
            <div class="col-md-6">
                <div class="container-custom">
                    <h5 class="text-primary">Chờ chế biến</h5>
                    <div class="list-group" id="cho-che-bien-list">
                        @foreach ($monAnChoCheBien as $mon)
                            <div id="dish-{{ $mon->id }}"
                                class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $mon->monAn->ten ?? 'Không xác định' }}</strong> -
                                    {{ $mon->hoaDon && $mon->hoaDon->banAns->isNotEmpty()
                                        ? $mon->hoaDon->banAns->pluck('ten_ban')->join(', ')
                                        : '<span class="text-danger">Chưa có bàn</span>' }}
                                    <br><small>Số lượng: {{ $mon->so_luong }}</small>
                                    @if ($mon->ghi_chu)
                                        <br><small style="color: #ff6347; font-size: 0.8em;">Ghi chú:
                                            {{ $mon->ghi_chu }}</small>
                                    @endif
                                </div>
                                <div class="status-buttons">
                                    <button class="btn btn-warning btn-sm status-btn"
                                        onclick="updateStatus({{ $mon->id }}, 'dang_nau')">
                                        Nấu
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="container-custom">
                    <h5 class="text-primary">Đang nấu</h5>
                    <div class="list-group" id="dang-nau-list">
                        @foreach ($monAnDangNau as $mon)
                            <div id="dish-{{ $mon->id }}"
                                class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $mon->monAn->ten ?? 'Không xác định' }}</strong> -
                                    {{ $mon->hoaDon && $mon->hoaDon->banAns->isNotEmpty()
                                        ? $mon->hoaDon->banAns->pluck('ten_ban')->join(', ')
                                        : '<span class="text-danger">Chưa có bàn</span>' }}
                                    <br><small>Số lượng: {{ $mon->so_luong }}</small>
                                    @if ($mon->ghi_chu)
                                        <br><small style="color: #ff6347; font-size: 0.8em;">Ghi chú:
                                            {{ $mon->ghi_chu }}</small>
                                    @endif
                                </div>
                                <div class="status-buttons">
                                    <button class="btn btn-success btn-sm status-btn"
                                        onclick="updateStatus({{ $mon->id }}, 'hoan_thanh')">
                                        Lên món
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.2/echo.iife.min.js"></script>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const choCheBienList = document.getElementById('cho-che-bien-list');
        const dangNauList = document.getElementById('dang-nau-list');

        async function updateStatus(id, status) {
            const message = status === 'dang_nau' ? 'Bạn có chắc muốn bắt đầu nấu món này?' : 'Món này đã hoàn thành?';
            if (!confirm(message)) return;

            try {
                const response = await fetch(`/bep/update/${id}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        trang_thai: status
                    })
                });

                const data = await response.json();
                if (data.success) {
                    moveDish(id, status);
                } else {
                    alert('Cập nhật thất bại: ' + data.message);
                }
            } catch (error) {
                console.error('Lỗi cập nhật:', error);
            }
        }

        function moveDish(id, status) {
            const dish = document.getElementById(`dish-${id}`);
            if (!dish) return;

            if (status === 'dang_nau') {
                dish.querySelector('.status-buttons').innerHTML =
                    `<button class="btn btn-success btn-sm status-btn" onclick="updateStatus(${id}, 'hoan_thanh')">Lên món</button>`;
                dangNauList.appendChild(dish);
            } else if (status === 'hoan_thanh') {
                dish.remove();
            }
        }

        // Hàm tạo phần tử món ăn (giữ nguyên HTML của bạn và thêm ghi chú với màu chữ nổi bật)
        function createDishElement(monAn, banAn) {
            const div = document.createElement('div');
            div.id = `dish-${monAn.id}`;
            div.className = 'list-group-item d-flex justify-content-between align-items-center';

            // Kiểm tra xem có ghi chú không, nếu có thì hiển thị và thay đổi màu chữ
            const ghiChu = monAn.ghi_chu ? `
        <br><small style="color: #ff6347; font-size: 0.8em;">Ghi chú: ${monAn.ghi_chu}</small>
    ` : '';

            div.innerHTML = `
        <div>
            <strong>${monAn.ten || 'Không xác định'}</strong> - 
            ${banAn ? ` ${banAn}` : '<span class="text-danger">Chưa có bàn</span>'}
            <br><small>Số lượng: ${monAn.so_luong}</small>
            ${ghiChu} <!-- Thêm ghi chú ở đây -->
        </div>
        <div class="status-buttons">
            <button class="btn btn-warning btn-sm status-btn" 
                onclick="updateStatus(${monAn.id}, 'dang_nau')">
                Nấu
            </button>
        </div>
    `;
            return div;
        }



        // Pusher setup
        window.Pusher = Pusher;
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: '{{ env('PUSHER_APP_KEY') }}',
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            forceTLS: true
        });

        const channel = window.Echo.channel('bep-channel');

        // Lắng nghe sự kiện cập nhật trạng thái món ăn
        channel.listen('.trang-thai-cap-nhat', (data) => {
            // console.log('Cập nhật trạng thái:', data);
            moveDish(data.monAn.id, data.monAn.trang_thai);
        });

        // Lắng nghe sự kiện món mới được thêm
        channel.listen('.mon-moi-duoc-them', (data) => {
            // console.log(data);
            if (!data?.monAns) {
                // console.error('Dữ liệu không hợp lệ');
                return;
            }

            // Duyệt qua danh sách món ăn mới và hiển thị chúng
            data.monAns.forEach(monAn => {
                const banAn = monAn.ban;

                // Kiểm tra nếu món ăn chưa có trong danh sách
                if (!document.getElementById(`dish-${monAn.id}`)) {
                    // console.log(`Thêm món mới: ${monAn.ten} (ID: ${monAn.id})`);
                    // Thêm món ăn mới vào danh sách nếu chưa có
                    choCheBienList.appendChild(createDishElement(monAn, banAn));
                } else {
                    // console.log(`Món ${monAn.ten} (ID: ${monAn.id}) đã tồn tại, bỏ qua`);
                }
            });
        });

        window.Echo.channel('xoa-mon-an-channel')
            .listen('.xoa-mon-an-event', (e) => {
                // console.log('Dữ liệu sự kiện:', e);  // Log toàn bộ sự kiện để kiểm tra cấu trúc dữ liệu

                // Kiểm tra xem e.data và e.data.id có tồn tại không trước khi truy cập id
                if (e && e.data && e.data.id) {
                    const monAnId = e.data.id;
                    const dish = document.getElementById(`dish-${monAnId}`);

                    if (dish) {
                        dish.remove();
                        console.log(`Xóa món: ${e.data.id} (ID: ${monAnId})`);

                        // Xóa món ăn khỏi danh sách tương ứng
                        if (choCheBienList.contains(dish)) {
                            choCheBienList.removeChild(dish);
                        } else if (dangNauList.contains(dish)) {
                            dangNauList.removeChild(dish);
                        }
                    } else {
                        console.log(`Không tìm thấy món ăn với ID: ${monAnId}`);
                    }
                } else {
                    console.log('Dữ liệu không hợp lệ hoặc thiếu thông tin về món ăn.');
                }
            });
    </script>
</body>

</html>
