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

        #cho-che-bien-list,
        #dang-nau-list {
            min-height: 100px;
            display: block !important;
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
                                data-thoi-gian-nau="{{ $mon->monAn->thoi_gian_nau }}"
                                data-ghi-chu="{{ $mon->ghi_chu ?? '' }}">
                                <div>
                                    <strong>{{ $mon->monAn->ten ?? 'Không xác định' }}</strong> -
                                    {{ $mon->hoaDon ? $mon->hoaDon->ma_hoa_don ?? 'Không có mã hóa đơn' : '<span class="text-danger">Không có hóa đơn</span>' }}
                                    <br><small>Số lượng: {{ $mon->so_luong }}</small>
                                    <br><small>Thời gian nấu:
                                        {{ number_format($mon->monAn->thoi_gian_nau * $mon->so_luong, 2) }}
                                        phút</small>
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
                    <h5 class="text-primary">Đang nấu/ Chờ cung ứng</h5>
                    <div class="list-group" id="dang-nau-list">
                        @foreach ($monAnDangNau as $mon)
                            <div id="dish-{{ $mon->id }}"
                                class="list-group-item d-flex justify-content-between align-items-center"
                                data-mon-an-id="{{ $mon->mon_an_id ?? $mon->monAn->ten }}"
                                data-ma-hoa-don="{{ $mon->hoaDon ? $mon->hoaDon->ma_hoa_don : '' }}"
                                data-ten="{{ $mon->monAn->ten ?? 'Không xác định' }}"
                                data-so-luong="{{ $mon->so_luong }}"
                                data-thoi-gian-nau="{{ $mon->monAn->thoi_gian_nau }}"
                                data-ghi-chu="{{ $mon->ghi_chu ?? '' }}">
                                <div>
                                    <strong>{{ $mon->monAn->ten ?? 'Không xác định' }}</strong> -
                                    {{ $mon->hoaDon ? $mon->hoaDon->ma_hoa_don ?? 'Không có mã hóa đơn' : '<span class="text-danger">Không có hóa đơn</span>' }}
                                    <br><small>Số lượng: {{ $mon->so_luong }}</small>
                                    <br><small>Thời gian nấu:
                                        {{ number_format($mon->monAn->thoi_gian_nau * $mon->so_luong, 2) }}
                                        phút</small>
                                    @if ($mon->ghi_chu)
                                        <br><small style="color: #ff6347; font-size: 0.8em;" class="ghi-chu">Ghi chú:
                                            {{ $mon->ghi_chu }}</small>
                                    @endif
                                    @if ($mon->thoi_gian_hoan_thanh_du_kien)
                                        <br>
                                        <small id="timer-{{ $mon->id }}" style="color: red; font-size: 10px;"
                                            data-thoi-gian-hoan-thanh-du_kien="{{ $mon->thoi_gian_hoan_thanh_du_kien }}">
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
        const processedEvents = new Set(); // Lưu trữ các sự kiện đã xử lý

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

                    // Không gọi moveDish ở đây, để sự kiện .trang-thai-cap-nhat xử lý
                } else {
                    showToast('Cập nhật thất bại: ' + data.message, 'danger');
                }
            } catch (error) {
                console.error('Lỗi cập nhật:', error);
                showToast('Lỗi cập nhật: ' + error.message, 'danger');
            }
        }

        function moveDish(id, status, monAnData = null) {
            const eventKey = `${id}-${status}-${monAnData?.updated_at || Date.now()}`;
            if (processedEvents.has(eventKey)) {

                return;
            }
            processedEvents.add(eventKey);



            if (status === 'dang_nau' && monAnData) {
                const monAnId = monAnData.mon_an_id || monAnData.ten;
                const ten = monAnData.mon_an?.ten || monAnData.ten || 'Không xác định';
                const maHoaDon = monAnData.hoa_don?.ma_hoa_don || monAnData.ma_hoa_don || '';
                const soLuong = monAnData.so_luong || 1;
                const thoiGianNau = monAnData.mon_an?.thoi_gian_nau || monAnData.thoi_gian_nau || 0;
                const ghiChu = monAnData.ghi_chu || '';
                const thoiGianHoanThanhDuKien = monAnData.thoi_gian_hoan_thanh_du_kien;

                // Kiểm tra xem món đã ở trạng thái hoan_thanh hoặc đã bị xóa
                const dishInDangNau = document.getElementById(`dish-${id}`);
                if (dishInDangNau && monAnData.trang_thai === 'hoan_thanh') {
                    console.warn(`Món id: ${id} đã ở trạng thái hoan_thanh, không thêm lại vào Đang nấu`);
                    return;
                }

                // Xóa món khỏi Chờ chế biến
                const dish = document.getElementById(`dish-${id}`);
                if (dish) {
                    dish.remove();
                    console.log

                }

                // Tìm món trùng trong Đang nấu
                const existingDish = Array.from(dangNauList.children).find(d =>
                    d.getAttribute('data-mon-an-id') == monAnId &&
                    d.getAttribute('data-ma-hoa-don') === maHoaDon
                );

                if (existingDish) {
                    // Gộp món
                    const existingId = existingDish.id.split('-')[1];
                    const existingSoLuong = parseInt(existingDish.getAttribute('data-so-luong')) || 0;
                    const newSoLuong = existingSoLuong + soLuong;
                    const existingGhiChu = existingDish.getAttribute('data-ghi-chu');
                    const newGhiChu = existingGhiChu && ghiChu ? `${existingGhiChu}, ${ghiChu}` : existingGhiChu ||
                        ghiChu || '';

                    existingDish.id = `dish-${id}`;
                    existingDish.setAttribute('data-so-luong', newSoLuong);
                    existingDish.setAttribute('data-ghi-chu', newGhiChu);
                    existingDish.setAttribute('data-thoi-gian-nau', thoiGianNau);

                    const thoiGianNauTong = (thoiGianNau * newSoLuong).toFixed(2);
                    existingDish.querySelector('div').innerHTML = `
                        <strong>${ten}</strong> - 
                        ${maHoaDon ? maHoaDon : '<span class="text-danger">Không có mã hóa đơn</span>'}
                        <br><small>Số lượng: ${newSoLuong}</small>
                        <br><small>Thời gian nấu: ${thoiGianNauTong} phút</small>
                        ${newGhiChu ? `<br><small style="color: #ff6347; font-size: 0.8em;" class="ghi-chu">Ghi chú: ${newGhiChu}</small>` : ''}
                        ${thoiGianHoanThanhDuKien ? `
                                <br>
                                <small id="timer-${id}" style="color: red; font-size: 10px;"
                                    data-thoi-gian-hoan-thanh-du-kien="${thoiGianHoanThanhDuKien}">
                                    Đang tính giờ...
                                </small>
                            ` : ''}
                    `;
                    existingDish.querySelector('.status-buttons').innerHTML =
                        `<button class="btn btn-success btn-sm status-btn" onclick="updateStatus(${id}, 'hoan_thanh')">Lên món</button>`;

                    if (thoiGianHoanThanhDuKien) {
                        startCountdown(id, new Date(thoiGianHoanThanhDuKien));
                    }


                    showToast(`Gộp ${ten}, số lượng: ${newSoLuong}`, 'success');
                } else {
                    // Thêm món mới vào Đang nấu
                    const newDish = document.createElement('div');
                    newDish.id = `dish-${id}`;
                    newDish.className = 'list-group-item d-flex justify-content-between align-items-center';
                    newDish.setAttribute('data-mon-an-id', monAnId);
                    newDish.setAttribute('data-ma-hoa-don', maHoaDon);
                    newDish.setAttribute('data-ten', ten);
                    newDish.setAttribute('data-so-luong', soLuong);
                    newDish.setAttribute('data-thoi-gian-nau', thoiGianNau);
                    newDish.setAttribute('data-ghi-chu', ghiChu);

                    const thoiGianNauTong = (thoiGianNau * soLuong).toFixed(2);
                    newDish.innerHTML = `
                        <div>
                            <strong>${ten}</strong> - 
                            ${maHoaDon ? maHoaDon : '<span class="text-danger">Không có mã hóa đơn</span>'}
                            <br><small>Số lượng: ${soLuong}</small>
                            <br><small>Thời gian nấu: ${thoiGianNauTong} phút</small>
                            ${ghiChu ? `<br><small style="color: #ff6347; font-size: 0.8em;" class="ghi-chu">Ghi chú: ${ghiChu}</small>` : ''}
                            ${thoiGianHoanThanhDuKien ? `
                                    <br>
                                    <small id="timer-${id}" style="color: red; font-size: 10px;"
                                        data-thoi-gian-hoan-thanh-du-kien="${thoiGianHoanThanhDuKien}">
                                        Đang tính giờ...
                                    </small>
                                ` : ''}
                        </div>
                        <div class="status-buttons">
                            <button class="btn btn-success btn-sm status-btn"
                                onclick="updateStatus(${id}, 'hoan_thanh')">
                                Lên món
                            </button>
                        </div>
                    `;
                    dangNauList.appendChild(newDish);


                    if (thoiGianHoanThanhDuKien) {
                        startCountdown(id, new Date(thoiGianHoanThanhDuKien));
                    }

                    showToast(`Chuyển ${ten} sang đang nấu`, 'success');
                }
            } else if (status === 'hoan_thanh' && monAnData) {
                // Tìm món theo id
                let dish = document.getElementById(`dish-${id}`);
                let ten = dish ? dish.getAttribute('data-ten') : (monAnData.mon_an?.ten || monAnData.ten ||
                    'Không xác định');
                let maHoaDon = monAnData.hoa_don?.ma_hoa_don || monAnData.ma_hoa_don || '';

                if (!dish) {
                    // Tìm món trùng theo mon_an_id và ma_hoa_don
                    const monAnId = monAnData.mon_an_id || monAnData.ten;
                    dish = Array.from(dangNauList.children).find(d =>
                        d.getAttribute('data-mon-an-id') == monAnId &&
                        d.getAttribute('data-ma-hoa-don') === maHoaDon
                    );
                    if (dish) {
                        ten = dish.getAttribute('data-ten');

                    }
                }

                if (dish) {
                    location.reload();
                    showToast(`Món ${ten} đã hoàn thành`, 'success');
                } else {
                    console.warn(
                        `Không tìm thấy món id: ${id} hoặc món trùng mon_an_id: ${monAnData.mon_an_id}, ma_hoa_don: ${maHoaDon} trong Đang nấu để xóa (trạng thái hoan_thanh)`
                        );
                }
            }
        }

        function createDishElement(monAn, maHoaDon) {
            if (!monAn || typeof monAn.id !== 'number' || !monAn.ten || typeof monAn.ten !== 'string' || monAn.ten
            .trim() === '') {
                console.error('Dữ liệu món ăn không hợp lệ:', monAn);
                showToast('Dữ liệu món ăn không hợp lệ', 'warning');
                return null;
            }



            const div = document.createElement('div');
            div.id = `dish-${monAn.id}`;
            div.className = 'list-group-item d-flex justify-content-between align-items-center';
            div.setAttribute('data-mon-an-id', monAn.mon_an_id || monAn.ten);
            div.setAttribute('data-ma-hoa-don', maHoaDon || '');
            div.setAttribute('data-ten', monAn.ten || 'Không xác định');
            div.setAttribute('data-so-luong', monAn.so_luong || 1);
            div.setAttribute('data-thoi-gian-nau', monAn.thoi_gian_nau || 0);
            div.setAttribute('data-ghi-chu', monAn.ghi_chu || '');

            const ghiChu = monAn.ghi_chu ? `
                <br><small style="color: #ff6347; font-size: 0.8em;" class="ghi-chu">Ghi chú: ${monAn.ghi_chu}</small>
            ` : '';

            const thoiGianNauTong = (monAn.thoi_gian_nau * monAn.so_luong).toFixed(2);

            div.innerHTML = `
                <div>
                    <strong>${monAn.ten}</strong> - 
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

        function replaceDishesByHoaDon(monAns, maHoaDon) {
            if (!monAns || !Array.isArray(monAns) || monAns.length === 0) {
                console.warn('Dữ liệu món mới không hợp lệ hoặc rỗng:', monAns);
                showToast('Dữ liệu món mới không hợp lệ', 'warning');
                return false;
            }



            const dishesToRemove = Array.from(choCheBienList.children).filter(dish =>
                dish.getAttribute('data-ma-hoa-don') === maHoaDon
            );
            dishesToRemove.forEach(dish => {

                dish.remove();
            });

            monAns.forEach((monAn, index) => {
                const dishElement = createDishElement(monAn, maHoaDon);
                if (dishElement) {
                    choCheBienList.appendChild(dishElement);

                    showToast(`Thêm ${monAn.ten}`, 'success');
                } else {
                    console.warn(`Bỏ qua món không hợp lệ tại index ${index}:`, monAn);
                }
            });

            return true;
        }

        window.Pusher = Pusher;
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: '{{ env('PUSHER_APP_KEY') }}',
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            forceTLS: true
        });


        const channel = window.Echo.channel('bep-channel');

        channel.listen('.mon-moi-duoc-them', (data) => {

            if (!data?.monAns || !data.monAns.length) {
                console.warn('Dữ liệu món mới không hợp lệ hoặc rỗng:', data);
                showToast('Dữ liệu món mới không hợp lệ', 'warning');
                return;
            }

            const maHoaDon = data.monAns[0].ma_hoa_don;
            replaceDishesByHoaDon(data.monAns, maHoaDon);
        });

        channel.listen('.trang-thai-cap-nhat', (data) => {

            const monAn = data.mon || data.monAn || {};
            if (monAn.id && monAn.trang_thai) {
                moveDish(monAn.id, monAn.trang_thai, monAn);
            } else {
                console.warn('Dữ liệu món ăn không hợp lệ trong trang-thai-cap-nhat:', data);
                showToast('Dữ liệu món ăn không hợp lệ', 'warning');
            }
        });

        window.Echo.channel('xoa-mon-an-channel')
            .listen('.xoa-mon-an-event', (e) => {

                if (e && e.data && e.data.id) {
                    const monAnId = e.data.id;
                    const dish = document.getElementById(`dish-${monAnId}`);
                    if (dish) {
                        dish.remove();

                        showToast(`Món id ${monAnId} đã được xóa`, 'success');
                    } else {

                    }
                } else {

                    showToast('Dữ liệu xóa món không hợp lệ', 'warning');
                }
            });

        document.addEventListener('DOMContentLoaded', () => {

            const dishes = document.querySelectorAll('[id^="dish-"]');
            dishes.forEach(dish => {
                const timerElement = dish.querySelector('[id^="timer-"]');
                if (timerElement) {
                    const monAnId = dish.id.split('-')[1];
                    const thoiGianHoanThanhDuKien = new Date(timerElement.getAttribute(
                        'data-thoi-gian-hoan-thanh-du-kien'));
                    startCountdown(monAnId, thoiGianHoanThanhDuKien);
                }
            });
        });

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
                    if (dishElement) {
                        const dishNameElement = dishElement.querySelector('strong');
                        const tenMon = dishNameElement ? dishNameElement.textContent : 'Món';
                        dishNameElement.style.color = 'red';
                        showToast(`Món "${tenMon}" đã nấu xong, hãy lên món!`, 'success');
                        if (document.hasFocus()) {
                            dingSound.play().catch(error => console.log('Lỗi phát âm thanh:', error));
                        }
                    }
                } else {
                    const thoiGianConLaiPhut = Math.floor(thoiGianConLai / 60);
                    const thoiGianConLaiGiay = thoiGianConLai % 60;
                    timerElement.textContent =
                        `Thời gian còn lại: ${thoiGianConLaiPhut} phút ${thoiGianConLaiGiay} giây`;
                }
            }, 1000);
        }

        function showToast(message, type) {
            const toastEl = document.getElementById('toastMessage');
            toastEl.classList.remove('text-bg-success', 'text-bg-danger', 'text-bg-warning');
            toastEl.classList.add('text-bg-' + type);
            toastEl.querySelector('.toast-body').textContent = message;
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        }
    </script>

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
