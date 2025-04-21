{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" /> --}}
<link href="{{ asset('admin/css/swiper-bundle.min.css') }}" rel="stylesheet" />
<style>
    .table-dat-ban {
        font-size: 14px;
    }

    .ban {
        position: relative;
    }

    .info-icon {
        position: absolute;
        top: 5px;
        right: 5px;
        font-size: 16px;
        color: #6c757d;
        /* Màu xám đậm thay cho xanh mặc định */
        cursor: pointer;
    }

    .info-icon:hover {
        color: #5a6268;
        /* Màu xám đậm hơn khi hover */
    }
</style>

<div class="swiper mySwiper">
    <div class="swiper-wrapper">
        @foreach ($data->chunk(18) as $chunk)
            <div class="swiper-slide">
                <div class="row">
                    @foreach ($chunk as $banAn)
                        <div class="col-md-2 col-4 mb-2">
                            <div class="ban cardd card text-center p-1 position-relative" data-id="{{ $banAn->id }}"
                                id="ban-{{ $banAn->id }}" onclick="setActive(this)">

                                <div class="card-body p-2">
                                    <h5><i class="fas fa-utensils" style="font-size: 24px;"></i></h5>
                                    <h6 class="card-title" style="font-size: 12px;">{{ $banAn->ten_ban }}</h6>

                                    @if ($banAn->trang_thai == 'trong')
                                        <p class="badge badge-success custom-badge">Có sẵn</p>
                                    @elseif ($banAn->trang_thai == 'co_khach')
                                        <p class="badge badge-warning custom-badge">Có khách</p>
                                    @elseif ($banAn->trang_thai == 'da_dat_truoc')
                                        <p class="badge badge-success custom-badge">Có sẵn</p>
                                    @else
                                        <p class="badge badge-secondary custom-badge">Không xác định</p>
                                    @endif

                                    <!-- Icon thông tin đặt bàn -->
                                    <i class="fas fa-info-circle info-icon" data-ban-id="{{ $banAn->id }}"
                                        onclick="showDatBanInfo(event, {{ $banAn->id }})"></i>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

</div>
<div class="text-center mt-2">
    <span id="pageIndicator">1 / 1</span>
</div>

<!-- Nút điều hướng TÙY CHỈNH -->
<div class="text-centerr mt-3">
    <button id="prevBtn" class="btn btn-primary btn-sm px-4">⬅ </button>
    <button id="nextBtn" class="btn btn-primary btn-sm px-4"> ➡</button>
</div>

<!-- Modal hiển thị thông tin đặt bàn -->
<div class="modal fade" id="datBanModal" tabindex="-1" role="dialog" aria-labelledby="datBanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="datBanModalLabel">Danh sách đặt bàn</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="datBanInfo">
                <!-- Nội dung sẽ được điền bằng AJAX -->
            </div>
        </div>
    </div>
</div>
{{-- <script src="{{ asset('admin/js/listBanAn.js') }}"></script> --}}
<script src="{{ asset('admin/js/copylistban.js') }}"></script>

<script>
// Biến lưu trữ danh sách đặt bàn, trạng thái sắp xếp và banId hiện tại
let datBanList = [];
    let sortDirection = {};
    let currentBanId = null; // Lưu banId để sử dụng trong applyFilters

    // Hàm hiển thị danh sách đặt bàn trong bảng
    function showDatBanInfo(event, banId) {
        event.stopPropagation();
        currentBanId = banId; // Lưu banId vào biến toàn cục

        // Tạo giao diện bộ lọc và bảng
        const modalBody = document.getElementById('datBanInfo');
        // Đặt giá trị mặc định cho filterDate là ngày hiện tại (Asia/Ho_Chi_Minh)
        const today = new Date().toLocaleDateString('sv-SE', { timeZone: 'Asia/Ho_Chi_Minh' }); // YYYY-MM-DD
        modalBody.innerHTML = `
        <div class="filter-container mb-3">
            <input type="text" id="filterName" class="form-control d-inline-block w-auto me-2" placeholder="Lọc theo tên">
            <input type="text" id="filterPhone" class="form-control d-inline-block w-auto me-2" placeholder="Lọc theo SĐT">
            <select id="filterStatus" class="form-select d-inline-block w-auto me-2">
                <option value="">Tất cả trạng thái</option>
                <option value="dang_xu_ly">Đang xử lý</option>
                <option value="xac_nhan">Xác nhận</option>
                <option value="da_huy">Đã hủy</option>
                <option value="da_thanh_toan">Đã thanh toán</option>
            </select>
            <input type="date" id="filterDate" class="form-control d-inline-block w-auto me-2" value="${today}">
            <button class="btn btn-sm btn-primary" onclick="applyFilters()">Lọc</button>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="sortable" onclick="sortTable('ma_dat_ban')">Mã đặt bàn</th>
                        <th class="sortable" onclick="sortTable('ten_khach_hang')">Tên khách hàng</th>
                        <th class="sortable" onclick="sortTable('so_nguoi')">Số người</th>
                        <th class="sortable" onclick="sortTable('thoi_gian_dat')">Thời gian đặt</th>
                        <th class="sortable" onclick="sortTable('thoi_gian_den')">Thời gian đến</th>
                        <th class="sortable" onclick="sortTable('trang_thai')">Trạng thái</th>
                        <th class="sortable" onclick="sortTable('so_dien_thoai')">Số điện thoại</th>
                        <th>Mô tả</th>
                    </tr>
                </thead>
                <tbody id="datBanTableBody"></tbody>
            </table>
        </div>
    `;

        // Thêm CSS cho cột sortable
        const style = document.createElement('style');
        style.innerHTML = `
        .sortable { cursor: pointer; }
        .sortable:hover { background-color: #f1f1f1; }
        .filter-container input, .filter-container select { margin-bottom: 10px; }
    `;
        document.head.appendChild(style);

        // Lấy danh sách đặt bàn với ngày hiện tại
        fetch(`/list-dat-ban/${banId}?date=${today}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                datBanList = data.dat_ban_list || [];
                renderTable(datBanList);
                $('#datBanModal').modal('show');
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('datBanTableBody').innerHTML =
                    '<tr><td colspan="8">Đã xảy ra lỗi khi lấy danh sách.</td></tr>';
                $('#datBanModal').modal('show');
            });
    }

    // Hàm hiển thị dữ liệu trong bảng
    function renderTable(data) {
        const tableBody = document.getElementById('datBanTableBody');
        tableBody.innerHTML = '';
        if (data.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="8">Không có dữ liệu.</td></tr>';
            return;
        }
        data.forEach(datBan => {
            const statusText = {
                'dang_xu_ly': 'Đang xử lý',
                'xac_nhan': 'Xác nhận',
                'da_huy': 'Đã hủy',
                'da_thanh_toan': 'Đã thanh toán'
            }[datBan.trang_thai] || datBan.trang_thai;

            tableBody.innerHTML += `
            <tr>
                <td>${datBan.ma_dat_ban}</td>
                <td>${datBan.ten_khach_hang || 'Không có'}</td>
                <td>${datBan.so_nguoi}</td>
                <td>${new Date(datBan.thoi_gian_dat).toLocaleString('vi-VN', { timeZone: 'Asia/Ho_Chi_Minh' })}</td>
                <td>${new Date(datBan.thoi_gian_den).toLocaleString('vi-VN', { timeZone: 'Asia/Ho_Chi_Minh' })}</td>
                <td>${statusText}</td>
                <td>${datBan.so_dien_thoai}</td>
                <td>${datBan.mo_ta || 'Không có'}</td>
            </tr>
        `;
        });
    }

    // Hàm áp dụng bộ lọc
    function applyFilters() {
        const filterName = document.getElementById('filterName').value.toLowerCase();
        const filterPhone = document.getElementById('filterPhone').value;
        const filterStatus = document.getElementById('filterStatus').value;
        const filterDate = document.getElementById('filterDate').value;

        // Gửi fetch mới với ngày được chọn, sử dụng currentBanId
        const fetchUrl = filterDate ? `/list-dat-ban/${currentBanId}?date=${filterDate}` : `/list-dat-ban/${currentBanId}`;
        fetch(fetchUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                datBanList = data.dat_ban_list || [];
                const filteredData = datBanList.filter(datBan => {
                    const matchesName = !filterName ||
                        (datBan.ten_khach_hang?.toLowerCase().includes(filterName));
                    const matchesPhone = !filterPhone ||
                        datBan.so_dien_thoai.includes(filterPhone);
                    const matchesStatus = !filterStatus ||
                        datBan.trang_thai === filterStatus;
                    return matchesName && matchesPhone && matchesStatus;
                });
                renderTable(filteredData);
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('datBanTableBody').innerHTML =
                    '<tr><td colspan="8">Đã xảy ra lỗi khi lấy danh sách.</td></tr>';
            });
    }

    // Hàm sắp xếp bảng
    function sortTable(column) {
        sortDirection[column] = !sortDirection[column];
        const direction = sortDirection[column] ? 1 : -1;

        datBanList.sort((a, b) => {
            let valA = a[column] || '';
            let valB = b[column] || '';

            if (column === 'so_nguoi') {
                valA = parseInt(valA) || 0;
                valB = parseInt(valB) || 0;
            } else if (column === 'thoi_gian_dat' || column === 'thoi_gian_den') {
                valA = new Date(valA).getTime();
                valB = new Date(valB).getTime();
            } else {
                valA = valA.toString().toLowerCase();
                valB = valB.toString().toLowerCase();
            }

            if (valA < valB) return -direction;
            if (valA > valB) return direction;
            return 0;
        });

        renderTable(datBanList);
    }
</script>
