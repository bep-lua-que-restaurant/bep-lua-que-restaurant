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

        .btn-warning:hover {
            background-color: #e68900;
            border-color: #e68900;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #218838;
        }

        .status-btn {
            min-width: 100px;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: bold;
            text-transform: uppercase;
            padding: 8px 15px;
        }

        .status-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
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
                                class="list-group-item d-flex justify-content-between align-items-center"
                                data-mon-an-id="{{ $mon->mon_an_id ?? $mon->monAn->ten }}"
                                data-ma-hoa-don="{{ $mon->hoaDon ? $mon->hoaDon->ma_hoa_don : '' }}"
                                data-ten="{{ $mon->monAn->ten ?? 'Không xác định' }}"
                                data-so-luong="{{ $mon->so_luong }}"
                                data-ghi-chu="{{ $mon->ghi_chu ?? '' }}">
                                <div>
                                    <strong>{{ $mon->monAn->ten ?? 'Không xác định' }}</strong> -
                                    {{ $mon->hoaDon ? $mon->hoaDon->ma_hoa_don ?? 'Không có mã hóa đơn' : '<span class="text-danger">Không có hóa đơn</span>' }}
                                    <br><small>Số lượng: {{ $mon->so_luong }}</small>
                                    <br><small>Thời gian nấu: {{ number_format($mon->monAn->thoi_gian_nau * $mon->so_luong, 2) }} phút</small>
                                    @if ($mon->ghi_chu)
                                        <br><small style="color: #ff6347; font-size: 0.8em;" class="ghi-chu">Ghi chú:
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
                    <h5 class="text-primary">Đang xong/ Chờ cung ứng</h5>
                    <div class="list-group" id="dang-nau-list">
                        @foreach ($monAnDangNau as $mon)
                            <div id="dish-{{ $mon->id }}"
                                class="list-group-item d-flex justify-content-between align-items-center"
                                data-mon-an-id="{{ $mon->mon_an_id ?? $mon->monAn->ten }}"
                                data-ma-hoa-don="{{ $mon->hoaDon ? $mon->hoaDon->ma_hoa_don : '' }}"
                                data-ten="{{ $mon->monAn->ten ?? 'Không xác định' }}"
                                data-so-luong="{{ $mon->so_luong }}"
                                data-ghi-chu="{{ $mon->ghi_chu ?? '' }}">
                                <div>
                                    <strong>{{ $mon->monAn->ten ?? 'Không xác định' }}</strong> -
                                    {{ $mon->hoaDon ? $mon->hoaDon->ma_hoa_don ?? 'Không có mã hóa đơn' : '<span class="text-danger">Không có hóa đơn</span>' }}
                                    <br><small>Số lượng: {{ $mon->so_luong }}</small>
                                    <br><small>Thời gian nấu: {{ number_format($mon->monAn->thoi_gian_nau * $mon->so_luong, 2) }} phút</small>
                                    @if ($mon->ghi_chu)
                                        <br><small style="color: #ff6347; font-size: 0.8em;" class="ghi-chu">Ghi chú:
                                            {{ $mon->ghi_chu }}</small>
                                    @endif
                                    @if ($mon->thoi_gian_hoan_thanh_du_kien)
                                        <br>
                                        <small id="timer-{{ $mon->id }}" style="color: red; font-size: 10px;"
                                            data-thoi-gian-hoan-thanh-du-kien="{{ $mon->thoi_gian_hoan_thanh_du_kien }}">
                                            Đang tính giờ...
                                        </small>
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
        var dingSoundUrl = "{{ asset('sounds/ding.mp3') }}";
        const dingSound = new Audio(dingSoundUrl);
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
                const ten = dish.getAttribute('data-ten');
                const maHoaDon = dish.getAttribute('data-ma-hoa-don');
                const soLuong = parseInt(dish.getAttribute('data-so-luong'));
                const ghiChu = dish.getAttribute('data-ghi-chu');

                // Tìm món trùng trong dang-nau-list
                const existingDish = Array.from(dangNauList.children).find(d =>
                    d.getAttribute('data-ten') === ten &&
                    d.getAttribute('data-ma-hoa-don') === maHoaDon
                );

                if (existingDish) {
                    // Gộp món: Cộng số lượng và cập nhật ghi chú
                    const existingSoLuong = parseInt(existingDish.getAttribute('data-so-luong'));
                    const newSoLuong = existingSoLuong + soLuong;
                    const existingGhiChu = existingDish.getAttribute('data-ghi-chu');
                    const newGhiChu = existingGhiChu && ghiChu ? `${existingGhiChu}, ${ghiChu}` :
                                     existingGhiChu || ghiChu || '';

                    // Cập nhật data attributes
                    existingDish.setAttribute('data-so-luong', newSoLuong);
                    existingDish.setAttribute('data-ghi-chu', newGhiChu);

                    // Cập nhật giao diện
                    const thoiGianNau = parseFloat(dish.querySelector('small:nth-child(3)').textContent.match(/[\d.]+/)[0]) / soLuong;
                    existingDish.querySelector('div').innerHTML = `
                        <strong>${ten}</strong> - 
                        ${maHoaDon ? maHoaDon : '<span class="text-danger">Không có mã hóa đơn</span>'}
                        <br><small>Số lượng: ${newSoLuong}</small>
                        <br><small>Thời gian nấu: ${(thoiGianNau * newSoLuong).toFixed(2)} phút</small>
                        ${newGhiChu ? `<br><small style="color: #ff6347; font-size: 0.8em;" class="ghi-chu">Ghi chú: ${newGhiChu}</small>` : ''}
                    `;

                    // Xóa món mới vì đã gộp
                    dish.remove();
                    console.log(`Gộp món ${ten} (ma_hoa_don: ${maHoaDon}), số lượng mới: ${newSoLuong}`);
                    showToast(`Gộp ${ten}, số lượng: ${newSoLuong}`, 'success');
                } else {
                    // Không có món trùng, thêm món vào dang-nau-list
                    dish.querySelector('.status-buttons').innerHTML =
                        `<button class="btn btn-success btn-sm status-btn" onclick="updateStatus(${id}, 'hoan_thanh')">Lên món</button>`;
                    dangNauList.appendChild(dish);
                }
            } else if (status === 'hoan_thanh') {
                dish.remove();
            }
        }

        // Hàm tạo phần tử món ăn
        function createDishElement(monAn, maHoaDon) {
            const div = document.createElement('div');
            div.id = `dish-${monAn.id}`;
            div.className = 'list-group-item d-flex justify-content-between align-items-center';
            div.setAttribute('data-mon-an-id', monAn.mon_an_id || monAn.ten);
            div.setAttribute('data-ma-hoa-don', maHoaDon || '');
            div.setAttribute('data-ten', monAn.ten || 'Không xác định');
            div.setAttribute('data-so-luong', monAn.so_luong);
            div.setAttribute('data-ghi-chu', monAn.ghi_chu || '');

            const ghiChu = monAn.ghi_chu ? `
                <br><small style="color: #ff6347; font-size: 0.8em;" class="ghi-chu">Ghi chú: ${monAn.ghi_chu}</small>
            ` : '';

            // Tính thời gian nấu theo số lượng
            const thoiGianNauTong = (monAn.thoi_gian_nau * monAn.so_luong).toFixed(2);

            div.innerHTML = `
                <div>
                    <strong>${monAn.ten || 'Không xác định'}</strong> - 
                    ${maHoaDon ? maHoaDon : '<span class="text-danger">Không có mã hóa đơn</span>'}
                    <br><small>Số lượng: ${monAn.so_luong}</small>
                    <br><small>Thời gian nấu: ${thoiGianNauTong} phút</small>
                    ${ghiChu}
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

        // Hàm thay thế món theo mã hóa đơn
        function replaceDishesByHoaDon(monAns, maHoaDon) {
            // Xóa tất cả món có ma_hoa_don khớp trong cho-che-bien-list
            const dishesToRemove = Array.from(choCheBienList.children).filter(dish =>
                dish.getAttribute('data-ma-hoa-don') === maHoaDon
            );
            dishesToRemove.forEach(dish => {
                console.log(`Removing dish with ma_hoa_don: ${maHoaDon}, id: ${dish.id}`);
                dish.remove();
            });

            // Thêm tất cả món mới từ monAns
            monAns.forEach(monAn => {
                choCheBienList.appendChild(createDishElement(monAn, maHoaDon));
                console.log(`Added dish mon_an_id: ${monAn.mon_an_id || monAn.ten}, so_luong: ${monAn.so_luong}, ma_hoa_don: ${maHoaDon}`);
                showToast(`Cập nhật ${monAn.ten}, số lượng: ${monAn.so_luong}`, 'success');
            });

            return true;
        }

        // Pusher setup
        window.Pusher = Pusher;
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: '{{ env('PUSHER_APP_KEY') }}',
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            forceTLS: true
        });

        console.log('Pusher key:', '{{ env('PUSHER_APP_KEY') }}');
        console.log('Pusher cluster:', '{{ env('PUSHER_APP_CLUSTER') }}');
        const channel = window.Echo.channel('bep-channel');

        // Xử lý sự kiện trang-thai-cap-nhat
        channel.listen('.trang-thai-cap-nhat', (data) => {
            console.log('Received trang-thai-cap-nhat:', JSON.stringify(data, null, 2));
            const monAn = data.monAn;
            moveDish(monAn.id, monAn.trang_thai);

            if (monAn.thoi_gian_hoan_thanh_du_kien) {
                const thoiGianHoanThanhDuKien = new Date(monAn.thoi_gian_hoan_thanh_du_kien);
                const dishElement = document.getElementById(`dish-${monAn.id}`);
                let timerElement = document.getElementById(`timer-${monAn.id}`);

                if (dishElement && !timerElement) {
                    timerElement = document.createElement('small');
                    timerElement.id = `timer-${monAn.id}`;
                    timerElement.style.color = 'red';
                    timerElement.style.fontSize = '10px';
                    timerElement.setAttribute('data-thoi-gian-hoan-thanh-du-kien', monAn.thoi_gian_hoan_thanh_du_kien);
                    timerElement.textContent = 'Đang tính giờ...';
                    dishElement.querySelector('div').appendChild(document.createElement('br'));
                    dishElement.querySelector('div').appendChild(timerElement);
                    startCountdown(monAn.id, thoiGianHoanThanhDuKien);
                }
            }
        });

        // Xử lý sự kiện món mới được thêm
        channel.listen('.mon-moi-duoc-them', (data) => {
            console.log('Received mon-moi-duoc-them:', JSON.stringify(data, null, 2));
            if (!data?.monAns || !data.monAns.length) return;

            const maHoaDon = data.monAns[0].ma_hoa_don; // Lấy ma_hoa_don từ món đầu tiên
            replaceDishesByHoaDon(data.monAns, maHoaDon);
        });

        // Xử lý sự kiện xóa món
        window.Echo.channel('xoa-mon-an-channel')
            .listen('.xoa-mon-an-event', (e) => {
                console.log('Received xoa-mon-an-event:', JSON.stringify(e, null, 2));
                if (e && e.data && e.data.id) {
                    const monAnId = e.data.id;
                    const dish = document.getElementById(`dish-${monAnId}`);
                    if (dish) {
                        dish.remove();
                        console.log(`Xóa món: ${monAnId}`);
                    } else {
                        console.log(`Không tìm thấy món ăn với ID: ${monAnId}`);
                    }
                } else {
                    console.log('Dữ liệu xóa món không hợp lệ.');
                }
            });

        // Khởi tạo bộ đếm thời gian
        document.addEventListener('DOMContentLoaded', () => {
            const dishes = document.querySelectorAll('[id^="dish-"]');
            dishes.forEach(dish => {
                const timerElement = dish.querySelector('[id^="timer-"]');
                if (timerElement) {
                    const monAnId = dish.id.split('-')[1];
                    const thoiGianHoanThanhDuKien = new Date(timerElement.getAttribute('data-thoi-gian-hoan-thanh-du-kien'));
                    startCountdown(monAnId, thoiGianHoanThanhDuKien);
                }
            });
        });

        // Hàm bắt đầu bộ đếm thời gian
        function startCountdown(monAnId, thoiGianHoanThanhDuKien) {
            const timerElement = document.getElementById(`timer-${monAnId}`);
            if (!timerElement) return;

            const intervalId = setInterval(() => {
                const thoiGianHienTai = new Date();
                const thoiGianConLai = Math.floor((thoiGianHoanThanhDuKien - thoiGianHienTai) / 1000);

                if (thoiGianConLai <= 0) {
                    clearInterval(intervalId);
                    timerElement.textContent = 'Hoàn thành!';
                    const dishElement = document.getElementById(`dish-${monAnId}`);
                    const dishNameElement = dishElement.querySelector('strong');
                    const tenMon = dishNameElement.textContent;
                    dishNameElement.style.color = 'red';
                    showToast(`Món "${tenMon}" đã nấu xong, hãy lên món!`, 'success');
                    dingSound.play().catch(error => console.log('Lỗi phát âm thanh:', error));
                } else {
                    const thoiGianConLaiPhut = Math.floor(thoiGianConLai / 60);
                    const thoiGianConLaiGiay = thoiGianConLai % 60;
                    timerElement.textContent = `Thời gian còn lại: ${thoiGianConLaiPhut} phút ${thoiGianConLaiGiay} giây`;
                }
            }, 1000);
        }

        // Hàm hiển thị toast
        function showToast(message, type) {
            const toastEl = document.getElementById('toastMessage');
            toastEl.classList.remove('text-bg-success', 'text-bg-danger', 'text-bg-warning');
            toastEl.classList.add('text-bg-' + type);
            toastEl.querySelector('.toast-body').textContent = message;
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        }
    </script>

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="toastMessage" class="toast align-items-center text-bg-danger border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>
</body>

</html>