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
                                data-ghi-chu="{{ $mon->ghi_chu ?? '' }}"
                                data-all-ids="{{ $mon->id }}">
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
                                data-ghi-chu="{{ $mon->ghi_chu ?? '' }}"
                                data-all-ids="{{ $mon->id }}">
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
                                            Hoàn thành dự kiến: {{ date('H:i', strtotime($mon->thoi_gian_hoan_thanh_du_kien)) }}
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
        const processedEvents = new Set();

        async function updateStatus(id, status) {
            console.log(`updateStatus called: id=${id}, status=${status}`);
            const message = status === 'dang_nau' ? 'Bạn có chắc muốn bắt đầu nấu món này?' : 'Món này đã hoàn thành?';
            if (!confirm(message)) {
                console.log('Update cancelled by user');
                return;
            }

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
                console.log('Update response:', data);
                if (data.success) {
                    console.log(`Update successful for id=${id}, status=${status}`);
                } else {
                    showToast('Cập nhật thất bại: ' + data.message, 'danger');
                    console.error('Update failed:', data.message);
                }
            } catch (error) {
                console.error('Lỗi cập nhật:', error);
                showToast('Lỗi cập nhật: ' + error.message, 'danger');
            }
        }

        function moveDish(id, status, monAnData = null) {
            console.log(`moveDish called: id=${id}, status=${status}, monAnData=`, monAnData);
            const eventKey = `${id}-${status}`;
            if (processedEvents.has(eventKey)) {
                console.log(`Event already processed: ${eventKey}`);
                return;
            }
            processedEvents.add(eventKey);
            console.log(`Added to processedEvents: ${eventKey}`);

            if (status === 'dang_nau' && monAnData) {
                console.log('Processing dang_nau status');
                const monAnId = monAnData.mon_an_id || monAnData.ten;
                const ten = monAnData.mon_an?.ten || monAnData.ten || 'Không xác định';
                const maHoaDon = monAnData.hoa_don?.ma_hoa_don || monAnData.ma_hoa_don || monAnData.hoa_don_id || '';
                const soLuong = monAnData.so_luong || 1;
                const thoiGianNau = monAnData.mon_an?.thoi_gian_nau || monAnData.thoi_gian_nau || 0;
                const ghiChu = monAnData.ghi_chu || '';
                const thoiGianHoanThanhDuKien = monAnData.thoi_gian_hoan_thanh_du_kien;

                console.log(`monAnId=${monAnId}, ten=${ten}, maHoaDon=${maHoaDon}, soLuong=${soLuong}, thoiGianNau=${thoiGianNau}`);

                // Xóa món khỏi Chờ chế biến
                const dishInChoCheBien = document.getElementById(`dish-${id}`);
                if (dishInChoCheBien) {
                    console.log(`Removing dish-${id} from Chờ chế biến`);
                    dishInChoCheBien.remove();
                } else {
                    console.log(`dish-${id} not found in Chờ chế biến`);
                }

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
                newDish.setAttribute('data-all-ids', id);

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
                                data-thoi-gian-hoan-thanh-du_kien="${thoiGianHoanThanhDuKien}">
                                Hoàn thành dự kiến: ${new Date(thoiGianHoanThanhDuKien).toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' })}
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
                    console.log(`Starting countdown for new dish id=${id}, thoiGianHoanThanhDuKien=${thoiGianHoanThanhDuKien}`);
                    startCountdown(id, new Date(thoiGianHoanThanhDuKien));
                }

                showToast(`Chuyển ${ten} sang đang nấu`, 'success');
                console.log(`Added new dish: id=${id}, ten=${ten}, allIds=${id}`);
            } else if (status === 'hoan_thanh' && monAnData) {
                console.log('Processing hoan_thanh status');
                let dish = document.getElementById(`dish-${id}`);
                let ten = monAnData.mon_an?.ten || monAnData.ten || 'Không xác định';
                let maHoaDon = monAnData.hoa_don?.ma_hoa_don || monAnData.ma_hoa_don || monAnData.hoa_don_id || '';
                const monAnId = monAnData.mon_an_id || monAnData.ten;

                console.log(`Looking for dish: id=${id}, monAnId=${monAnId}, maHoaDon=${maHoaDon}, ten=${ten}`);

                if (!dish) {
                    console.log(`dish-${id} not found, searching by all-ids or mon_an_id and ma_hoa_don`);
                    dish = Array.from(dangNauList.children).find(d => {
                        const allIds = d.getAttribute('data-all-ids') ? d.getAttribute('data-all-ids').split(',') : [d.id.split('-')[1]];
                        return allIds.includes(String(id)) || (
                            d.getAttribute('data-mon-an-id') == monAnId &&
                            d.getAttribute('data-ma-hoa-don') === maHoaDon
                        );
                    });
                    if (dish) {
                        ten = dish.getAttribute('data-ten') || ten;
                        console.log(`Found dish by all-ids or mon_an_id/ma_hoa_don: id=${dish.id}, ten=${ten}, allIds=${dish.getAttribute('data-all-ids')}`);
                    } else {
                        console.log('No dish found by all-ids, mon_an_id, or ma_hoa_don');
                    }
                }

                if (dish) {
                    console.log(`Removing dish-${id} from Đang nấu`);
                    dish.remove();
                    showToast(`Món ${ten} đã hoàn thành`, 'success');
                } else {
                    console.warn(
                        `Không tìm thấy món id: ${id} hoặc món trùng mon_an_id: ${monAnId}, ma_hoa_don: ${maHoaDon} trong Đang nấu để xóa (trạng thái hoan_thanh)`
                    );
                    showToast(`Món ${ten} đã hoàn thành nhưng không tìm thấy trong giao diện`, 'warning');
                }
            } else {
                console.warn(`Invalid moveDish call: status=${status}, monAnData=`, monAnData);
            }
        }

        function createDishElement(monAn, maHoaDon) {
            console.log('createDishElement called:', monAn, maHoaDon);
            if (!monAn || typeof monAn.id !== 'number' || !monAn.ten || typeof monAn.ten !== 'string' || monAn.ten.trim() === '') {
                console.error('Dữ liệu món ăn không hợp lệ:', monAn);
                showToast('Dữ liệu món ăn không hợp lệ', 'warning');
                return null;
            }

            if (monAn.trang_thai === 'hoan_thanh') {
                console.log(`Skipping completed dish in createDishElement: id=${monAn.id}, ten=${monAn.ten}`);
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
            div.setAttribute('data-all-ids', monAn.id);

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
                    ${monAn.thoi_gian_hoan_thanh_du_kien && monAn.trang_thai === 'dang_nau' ? `
                        <br>
                        <small id="timer-${monAn.id}" style="color: red; font-size: 10px;"
                            data-thoi-gian-hoan-thanh-du_kien="${monAn.thoi_gian_hoan_thanh_du_kien}">
                            Hoàn thành dự kiến: ${new Date(monAn.thoi_gian_hoan_thanh_du_kien).toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' })}
                        </small>
                    ` : ''}
                </div>
                <div class="status-buttons">
                    <button class="btn ${monAn.trang_thai === 'dang_nau' ? 'btn-success' : 'btn-warning'} btn-sm status-btn" 
                        onclick="updateStatus(${monAn.id}, '${monAn.trang_thai === 'dang_nau' ? 'hoan_thanh' : 'dang_nau'}')">
                        ${monAn.trang_thai === 'dang_nau' ? 'Lên món' : 'Nấu'}
                    </button>
                </div>
            `;
            console.log(`Created dish element: id=dish-${monAn.id}, ten=${monAn.ten}`);
            return div;
        }

        function replaceDishesByHoaDon(monAns, maHoaDon, maHoaDonCu = []) {
            console.log('replaceDishesByHoaDon called:', monAns, maHoaDon, maHoaDonCu);
            if (!monAns || !Array.isArray(monAns) || monAns.length === 0) {
                console.warn('Dữ liệu món mới không hợp lệ hoặc rỗng:', monAns);
                showToast('Dữ liệu món mới không hợp lệ', 'warning');
                return false;
            }

            // Xóa các món hiện tại trong cả hai danh sách có ma_hoa_don tương ứng
            const lists = [choCheBienList, dangNauList];
            const maHoaDonToRemove = [maHoaDon, ...maHoaDonCu].filter(Boolean);

            lists.forEach(list => {
                const dishesToRemove = Array.from(list.children).filter(dish =>
                    maHoaDonToRemove.includes(dish.getAttribute('data-ma-hoa-don'))
                );
                console.log(`Removing ${dishesToRemove.length} dishes with ma_hoa_don in ${maHoaDonToRemove} from ${list.id}`);
                dishesToRemove.forEach(dish => {
                    console.log(`Removing dish id=${dish.id}`);
                    dish.remove();
                });
            });

            // Thêm từng món riêng biệt vào danh sách tương ứng
            monAns.forEach(monAn => {
                if (monAn.trang_thai === 'hoan_thanh') {
                    console.log(`Skipping completed dish: id=${monAn.id}, ten=${monAn.ten}`);
                    return;
                }
                const dishElement = createDishElement(monAn, maHoaDon);
                if (dishElement) {
                    const targetList = monAn.trang_thai === 'dang_nau' ? dangNauList : choCheBienList;
                    targetList.appendChild(dishElement);
                    showToast(`Cập nhật ${monAn.ten} với mã hóa đơn ${maHoaDon}`, 'success');
                    console.log(`Added dish: id=dish-${monAn.id}, ten=${monAn.ten}, list=${targetList.id}`);
                    if (monAn.trang_thai === 'dang_nau' && monAn.thoi_gian_hoan_thanh_du_kien) {
                        startCountdown(monAn.id, new Date(monAn.thoi_gian_hoan_thanh_du_kien));
                    }
                } else {
                    console.warn(`Bỏ qua món không hợp lệ:`, monAn);
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
            console.log('Pusher event .mon-moi-duoc-them received:', data);
            if (!data?.monAns || !data.monAns.length) {
                console.warn('Dữ liệu món mới không hợp lệ hoặc rỗng:', data);
                showToast('Dữ liệu món mới không hợp lệ', 'warning');
                return;
            }

            const maHoaDon = data.monAns[0].ma_hoa_don || data.monAns[0].hoa_don_id;
            replaceDishesByHoaDon(data.monAns, maHoaDon);
        });

        channel.listen('.trang-thai-cap-nhat', (data) => {
            console.log('Pusher event .trang-thai-cap-nhat received:', data);
            const monAn = data.mon || data.monAn || {};
            if (monAn.id && monAn.trang_thai) {
                console.log(`Processing trang-thai-cap-nhat: id=${monAn.id}, trang_thai=${monAn.trang_thai}`);
                moveDish(monAn.id, monAn.trang_thai, monAn);
            } else {
                console.warn('Dữ liệu món ăn không hợp lệ trong trang-thai-cap-nhat:', data);
                showToast('Dữ liệu món ăn không hợp lệ', 'warning');
            }
        });

        channel.listen('.ghep-ban', (data) => {
            console.log('Pusher event .ghep-ban received:', data);
            if (!data?.monAns || !data.maHoaDon) {
                console.warn('Dữ liệu ghép bàn không hợp lệ:', data);
                showToast('Dữ liệu ghép bàn không hợp lệ', 'warning');
                return;
            }

            replaceDishesByHoaDon(data.monAns, data.maHoaDon, data.maHoaDonCu || []);
            showToast(`Đã cập nhật món ăn cho mã hóa đơn ${data.maHoaDon} sau khi ghép bàn`, 'success');
        });

        window.Echo.channel('xoa-mon-an-channel')
            .listen('.xoa-mon-an-event', (e) => {
                console.log('Pusher event .xoa-mon-an-event received:', e);
                if (e && e.data && e.data.id) {
                    const monAnId = e.data.id;
                    const dish = document.getElementById(`dish-${monAnId}`);
                    if (dish) {
                        console.log(`Removing dish id=dish-${monAnId} due to xoa-mon-an-event`);
                        dish.remove();
                        showToast(`Món id ${monAnId} đã được xóa`, 'success');
                    } else {
                        console.log(`dish-${monAnId} not found for xoa-mon-an-event`);
                    }
                } else {
                    console.warn('Dữ liệu xóa món không hợp lệ:', e);
                    showToast('Dữ liệu xóa món không hợp lệ', 'warning');
                }
            });

        document.addEventListener('DOMContentLoaded', () => {
            console.log('DOM fully loaded, initializing countdown timers');
            const dishes = document.querySelectorAll('[id^="dish-"]');
            dishes.forEach(dish => {
                const timerElement = dish.querySelector('[id^="timer-"]');
                if (timerElement) {
                    const monAnId = dish.id.split('-')[1];
                    const thoiGianHoanThanhDuKien = new Date(timerElement.getAttribute('data-thoi-gian-hoan-thanh-du_kien'));
                    console.log(`Starting countdown for dish id=dish-${monAnId}, thoiGianHoanThanhDuKien=${thoiGianHoanThanhDuKien}`);
                    startCountdown(monAnId, thoiGianHoanThanhDuKien);
                }
            });
        });

        function startCountdown(monAnId, thoiGianHoanThanhDuKien) {
            console.log(`startCountdown called: monAnId=${monAnId}, thoiGianHoanThanhDuKien=${thoiGianHoanThanhDuKien}`);
            const timerElement = document.getElementById(`timer-${monAnId}`);
            if (!timerElement) {
                console.log(`timer-${monAnId} not found`);
                return;
            }

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
                        console.log(`Countdown completed for dish-${monAnId}, tenMon=${tenMon}`);
                        if (document.hasFocus()) {
                            dingSound.play().catch(error => console.log('Lỗi phát âm thanh:', error));
                        }
                    } else {
                        console.log(`dish-${monAnId} not found after countdown completion`);
                    }
                }
            }, 1000);
        }

        function showToast(message, type) {
            console.log(`showToast: message=${message}, type=${type}`);
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