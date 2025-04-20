@extends('layouts.admin')

@section('title', 'Kiểm Tra Tồn Kho và Hạn Sử Dụng')

@section('content')
    <div class="container mt-4">
        <h3 class="mb-4 text-primary">📦 Thống kê tồn kho và hạn sử dụng</h3>
        <div class="text-end mt-4">
            <a href="{{ route('nguyen-lieu.index') }}" class="btn btn-secondary">
                ← Quay lại danh sách
            </a>
        </div>
        <!-- Tab navigation -->
        <ul class="nav nav-tabs" id="tab-hsd" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="ton-kho-tab" data-bs-toggle="tab" href="#ton-kho" role="tab"
                    aria-controls="ton-kho" aria-selected="true">Tồn Kho</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="han-su-dung-tab" data-bs-toggle="tab" href="#han-su-dung" role="tab"
                    aria-controls="han-su-dung" aria-selected="false">Hạn Sử Dụng</a>
            </li>
        </ul>

        <!-- Tab content -->
        <div class="tab-content" id="tab-hsd-content">

            <!-- Tồn Kho Tab -->
            <div class="tab-pane fade show active" id="ton-kho" role="tabpanel" aria-labelledby="ton-kho-tab">
                <!-- Bộ lọc Tồn Kho -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <form id="filter-ton-kho-form" class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label for="ngay-ton-kho" class="form-label">Chọn ngày</label>
                                <input type="date" id="ngay-ton-kho" name="ngay" class="form-control"
                                    value="{{ \Carbon\Carbon::today()->toDateString() }}">
                            </div>

                            <div class="col-md-4">
                                <label for="loai_nguyen_lieu_id" class="form-label">Loại nguyên liệu</label>
                                <select id="loai_nguyen_lieu_id" name="loai_nguyen_lieu_id" class="form-select">
                                    <option value="">-- Tất cả --</option>
                                    @foreach ($loaiNguyenLieus as $loai)
                                        <option value="{{ $loai->id }}">{{ $loai->ten_loai }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-funnel"></i> Lọc dữ liệu
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Bảng dữ liệu Tồn Kho -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle text-center" id="table-ton-kho">
                                <thead class="table-primary">
                                    <tr>
                                        <th>STT</th>
                                        <th>Nguyên liệu</th>
                                        <th>Đơn vị</th>
                                        <th>Tồn kho</th>
                                        <th>Nhập từ Bếp</th>
                                        <th>Nhập từ NCC</th>
                                        <th>Tổng Nhập</th>
                                        <th>Xuất Bếp</th>
                                        <th>Xuất Trả Hàng</th>
                                        <th>Xuất Hủy</th>
                                        <th>Tổng Xuất</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody id="data-body-ton-kho">
                                    <tr>
                                        <td colspan="12">🔄 Vui lòng chọn bộ lọc để hiển thị dữ liệu...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hạn Sử Dụng Tab -->
            <div class="tab-pane fade" id="han-su-dung" role="tabpanel" aria-labelledby="han-su-dung-tab">
                <!-- Bộ lọc -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <form id="filter-han-su-dung-form" class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label for="ngay-hsd" class="form-label">Chọn ngày</label>
                                <input type="date" id="ngay-hsd" name="ngay" class="form-control"
                                    value="{{ \Carbon\Carbon::today()->toDateString() }}">
                            </div>

                            <div class="col-md-4">
                                <label for="loai_nguyen_lieu_id_hsd" class="form-label">Loại nguyên liệu</label>
                                <select id="loai_nguyen_lieu_id_hsd" name="loai_nguyen_lieu_id" class="form-select">
                                    <option value="">-- Tất cả --</option>
                                    @foreach ($loaiNguyenLieus as $loai)
                                        <option value="{{ $loai->id }}">{{ $loai->ten_loai }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-funnel"></i> Lọc dữ liệu
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Bảng hiển thị -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle text-center table-sm">
                                <thead class="table-primary">
                                    <tr>
                                        <th>STT</th>
                                        <th>Nguyên liệu</th>
                                        <th>Đơn vị</th>
                                        <th>Tồn kho</th>
                                        <th>Hạn sử dụng</th>
                                        <th>Tình trạng</th>
                                        <th>Biểu đồ</th>
                                    </tr>
                                </thead>
                                <tbody id="data-body-han-su-dung">
                                    <tr>
                                        <td colspan="7">🔄 Vui lòng chọn bộ lọc để hiển thị dữ liệu...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- jQuery + Bootstrap Icons -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/locale/vi.min.js"></script>
        <script>
            moment.locale('vi');
        </script>

        <script>
            $(function() {
                function loadTonKho() {
                    const ngay = $('#ngay-ton-kho').val();
                    const loaiId = $('#loai_nguyen_lieu_id').val();

                    $.ajax({
                        url: '/api/ton-kho/xuat-dung',
                        type: 'GET',
                        data: {
                            ngay: ngay,
                            loai_nguyen_lieu_id: loaiId
                        },
                        success: function(res) {
                            const tbody = $('#data-body-ton-kho');
                            tbody.empty();

                            if (res.length === 0) {
                                tbody.append(`<tr><td colspan="12">🚫 Không có dữ liệu</td></tr>`);
                                return;
                            }

                            $.each(res, function(index, item) {
                                const trangThai = item.da_ngung_su_dung ?
                                    '<span class="badge bg-danger">Ngưng SD</span>' :
                                    '<span class="badge bg-success">Đang SD</span>';

                                tbody.append(`
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${item.nguyen_lieu}</td>
                                        <td>${item.don_vi}</td>
                                        <td>${item.ton_kho_hien_tai}</td>
                                        <td>${item.nhap_tu_bep}</td>
                                        <td>${item.nhap_tu_ncc}</td>
                                        <td class="text-primary fw-bold">${item.tong_nhap}</td>
                                        <td>${item.xuat_bep}</td>
                                        <td>${item.xuat_tra_hang}</td>
                                        <td>${item.xuat_huy}</td>
                                        <td class="text-danger fw-bold">${item.tong_xuat}</td>
                                        <td>${trangThai}</td>
                                    </tr>
                                `);
                            });
                        },
                        error: function() {
                            alert('❌ Lỗi khi lấy dữ liệu tồn kho!');
                        }
                    });
                }

                function loadHanSuDung() {
                    const ngay = $('#ngay-hsd').val();
                    const loaiId = $('#loai_nguyen_lieu_id_hsd').val();
                    const tbody = $('#data-body-han-su-dung');

                    tbody.html(`<tr><td colspan="7">⏳ Đang tải dữ liệu...</td></tr>`);

                    let params = {};
                    if (ngay) params.ngay = ngay;
                    if (loaiId) params.loai_nguyen_lieu_id = loaiId;
                    console.log('Gọi AJAX...');
                    $.ajax({
                        url: '{{ route('nguyen-lieu.hansudung') }}',
                        type: 'GET',
                        data: params,
                        success: function(res) {
                            console.log(res);

                            tbody.empty();

                            if (!res.data || res.data.length === 0) {
                                tbody.append(`<tr><td colspan="7">🚫 Không có dữ liệu</td></tr>`);
                                return;
                            }

                            $.each(res.data, function(index, item) {
                                const conHanLo = [],
                                    sapHetLo = [],
                                    hetHanLo = [];

                                item.lo_nhap.forEach(lo => {
                                    const han = moment(lo.han_su_dung, 'DD/MM/YYYY').endOf(
                                        'day');
                                    const now = moment().startOf('day');
                                    const next7 = moment().add(7, 'days').endOf('day');
                                    const sl = parseFloat(lo.so_luong) || 0;

                                    if (han.isBefore(now)) {
                                        hetHanLo.push({
                                            ...lo,
                                            so_luong: sl
                                        });
                                    } else if (han.isSameOrBefore(next7)) {
                                        sapHetLo.push({
                                            ...lo,
                                            so_luong: sl
                                        });
                                    } else {
                                        conHanLo.push({
                                            ...lo,
                                            so_luong: sl
                                        });
                                    }
                                });

                                const conHan = conHanLo.reduce((sum, lo) => sum + lo.so_luong, 0);
                                const sapHetHan = sapHetLo.reduce((sum, lo) => sum + lo.so_luong,
                                    0);
                                const hetHan = hetHanLo.reduce((sum, lo) => sum + lo.so_luong, 0);
                                const ton = conHan + sapHetHan + hetHan;

                                const tiLeSapHetHan = ton > 0 ? ((sapHetHan / ton) * 100).toFixed(
                                    0) : 0;
                                const tiLeHetHan = ton > 0 ? ((hetHan / ton) * 100).toFixed(0) : 0;
                                const tiLeConHan = ton > 0 ? ((conHan / ton) * 100).toFixed(0) : 0;

                                let badgeClass = 'success';
                                let status = 'Còn nhiều hạn';
                                let icon = 'bi bi-check-circle';
                                let soNgay = '-';

                                if (hetHan > 0 && hetHan === ton) {
                                    badgeClass = 'danger';
                                    status = 'Hết hạn';
                                    icon = 'bi bi-x-circle';
                                } else if (sapHetHan > 0) {
                                    badgeClass = 'warning';
                                    status = 'Sắp hết hạn';
                                    icon = 'bi bi-exclamation-circle';
                                }

                                const chartId = `chart-${index}`;
                                const rowClass = hetHan === ton ? 'table-danger' :
                                    sapHetHan > 0 ? 'table-warning' : 'table-success';

                                tbody.append(`
            <tr class="${rowClass}">
                <td>${index + 1}</td>
                <td class="text-start" title="${item.nguyen_lieu}">${item.nguyen_lieu}</td>
                <td>${item.don_vi}</td>
                <td>${ton}</td>
                <td>
                    <span class="badge bg-${badgeClass}" data-bs-toggle="tooltip" title="${status}">
                        ${soNgay}
                    </span>
                </td>
                <td>
                    <span class="d-inline-flex align-items-center gap-1 text-${badgeClass}">
                        <i class="${icon}" style="font-size: 1.2rem;"></i>
                        <span class="fw-semibold">${status}</span>
                    </span>
                </td>
                <td>
                    <div class="d-flex flex-column align-items-center">
                        <canvas id="${chartId}" class="chart-canvas mb-1" ></canvas>
                        <div style="font-size: 0.75rem;">
                            <span class="text-success">${tiLeConHan}%</span> - 
                            <span class="text-warning">${tiLeSapHetHan}%</span> - 
                            <span class="text-danger">${tiLeHetHan}%</span>
                        </div>
                    </div>
                </td>
            </tr>
                                `);

                                setTimeout(() => {
                                    const ctx = document.getElementById(chartId);
                                    if (ctx) {
                                        new Chart(ctx, {
                                            type: 'doughnut',
                                            data: {
                                                labels: ['Còn hạn', 'Sắp hết hạn',
                                                    'Hết hạn'
                                                ],
                                                datasets: [{
                                                    data: [conHan,
                                                        sapHetHan,
                                                        hetHan
                                                    ],
                                                    backgroundColor: [
                                                        '#198754',
                                                        '#ffc107',
                                                        '#dc3545'
                                                    ],
                                                    borderWidth: 1,
                                                    dataDetails: [conHanLo,
                                                        sapHetLo,
                                                        hetHanLo
                                                    ]
                                                }]
                                            },
                                            options: {
                                                cutout: '70%',
                                                plugins: {
                                                    legend: {
                                                        display: false
                                                    },
                                                    tooltip: {
                                                        callbacks: {
                                                            label: function(
                                                            context) {
                                                                const index =
                                                                    context
                                                                    .dataIndex;
                                                                const loList =
                                                                    context
                                                                    .dataset
                                                                    .dataDetails
                                                                    ?.[index];
                                                                const value =
                                                                    context
                                                                    .dataset
                                                                    .data[
                                                                    index];
                                                                const label =
                                                                    context
                                                                    .label;

                                                                // Tính phần trăm (nếu có biến `ton`)
                                                                const percent =
                                                                    value > 0 &&
                                                                    typeof ton !==
                                                                    'undefined' ?
                                                                    ((value /
                                                                            ton
                                                                            ) *
                                                                        100)
                                                                    .toFixed(
                                                                    0) + '%' :
                                                                    '0%';

                                                                let labelStr =
                                                                    `${label}: ${value} (${percent})`;

                                                                if (!loList ||
                                                                    loList
                                                                    .length ===
                                                                    0)
                                                            return labelStr;

                                                                // Dòng lô hàng chi tiết (mỗi dòng riêng)
                                                                const
                                                                    loDetails =
                                                                    loList.map(
                                                                        lo =>
                                                                        `• SL: ${lo.so_luong} | Nhập: ${lo.ngay_nhap} `
                                                                    );

                                                                return [
                                                                        labelStr]
                                                                    .concat(
                                                                        loDetails
                                                                        );
                                                            }
                                                        },
                                                        // Bạn có thể thêm style để dễ đọc hơn
                                                        bodyFont: {
                                                            size: 12
                                                        },
                                                        titleFont: {
                                                            size: 13,
                                                            weight: 'bold'
                                                        },
                                                        displayColors: false, // Ẩn màu ô vuông nếu không cần
                                                        padding: 10
                                                    }

                                                }
                                            }
                                        });
                                    }
                                }, 100);
                            });

                            // Kích hoạt tooltip sau khi dữ liệu đã render
                            setTimeout(() => {
                                const tooltipTriggerList = [].slice.call(document.querySelectorAll(
                                    '[data-bs-toggle="tooltip"]'));
                                tooltipTriggerList.map(t => new bootstrap.Tooltip(t));
                            }, 200);
                        },
                        error: function() {
                            tbody.html(`<tr><td colspan="7">❌ Lỗi khi lấy dữ liệu hạn sử dụng!</td></tr>`);
                        }
                    });
                }

                // Load mặc định
                loadTonKho();

                $('#filter-ton-kho-form').on('submit', function(e) {
                    e.preventDefault();
                    loadTonKho();
                });

                $('#filter-han-su-dung-form').on('submit', function(e) {
                    e.preventDefault();
                    loadHanSuDung();
                });

                $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                    const target = $(e.target).attr('href');
                    if (target === '#han-su-dung') {
                        setTimeout(loadHanSuDung, 300);
                    }
                    console.log(`Tab ${$(e.target).text()} được chọn`);

                });
            });
        </script>






        <style>
            .chart-canvas {
                max-width: 200px;
                /* Hoặc cao hơn nếu cần */
                white-space: normal !important;
                word-wrap: break-word;
                /* z-index: 9999; */
                /* Đảm bảo hiển thị trên */
            }
        </style>
    @endsection
