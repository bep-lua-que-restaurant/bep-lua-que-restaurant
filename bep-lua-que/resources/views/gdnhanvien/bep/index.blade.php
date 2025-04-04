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
        body { background-color: #004080; }
        .container-custom {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            height: 80vh;
            overflow-y: auto;
        }
        .status-btn { min-width: 100px; }
        .navbar-toggler { 
            border: none; 
            background: none; 
            font-size: 30px; 
        }
        .navbar-nav { margin-left: auto; }
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
                            <div id="dish-{{ $mon->id }}" class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $mon->monAn->ten ?? 'Không xác định' }}</strong> - 
                                    {{ $mon->hoaDon && $mon->hoaDon->banAns->isNotEmpty() 
                                        ? $mon->hoaDon->banAns->pluck('ten_ban')->join(', ') 
                                        : '<span class="text-danger">Chưa có bàn</span>' }}
                                    <br><small>Số lượng: {{ $mon->so_luong }}</small>
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
                            <div id="dish-{{ $mon->id }}" class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $mon->monAn->ten ?? 'Không xác định' }}</strong> - 
                                    {{ $mon->hoaDon && $mon->hoaDon->banAns->isNotEmpty() 
                                        ? $mon->hoaDon->banAns->pluck('ten_ban')->join(', ') 
                                        : '<span class="text-danger">Chưa có bàn</span>' }}
                                    <br><small>Số lượng: {{ $mon->so_luong }}</small>
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
                    body: JSON.stringify({ trang_thai: status })
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

        function createDishElement(monAn, banAn) {
            const div = document.createElement('div');
            div.id = `dish-${monAn.id}`;
            div.className = 'list-group-item d-flex justify-content-between align-items-center';
            div.innerHTML = `
                <div>
                    <strong>${monAn.ten || 'Không xác định'}</strong> - 
                    ${banAn ? ` ${banAn}` : '<span class="text-danger">Chưa có bàn</span>'}
                    <br><small>Số lượng: ${monAn.so_luong}</small>
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
        
        channel.listen('.trang-thai-cap-nhat', (data) => {
            console.log('Cập nhật trạng thái:', data);
            moveDish(data.monAn.id, data.monAn.trang_thai);
        });

        channel.listen('.mon-moi-duoc-them', (data) => {
            console.log('Dữ liệu món mới nhận được:', data);
            if (!data?.monAns) {
                console.error('Dữ liệu không hợp lệ');
                return;
            }

            data.monAns.forEach(monAn => {
                const banAn = monAn.ban;
                if (!document.getElementById(`dish-${monAn.id}`)) {
                    console.log(`Thêm món mới: ${monAn.ten} (ID: ${monAn.id})`);
                    choCheBienList.appendChild(createDishElement(monAn, banAn));
                } else {
                    console.log(`Món ${monAn.ten} (ID: ${monAn.id}) đã tồn tại, bỏ qua`);
                }
            });
        });
    </script>
</body>
</html>