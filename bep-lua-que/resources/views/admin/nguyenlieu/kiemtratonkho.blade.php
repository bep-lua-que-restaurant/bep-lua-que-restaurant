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

        <script>
            // Lọc Tồn Kho
            $(function() {
                $('#filter-ton-kho-form').on('submit', function(e) {
                    e.preventDefault();

                    let ngay = $('#ngay-ton-kho').val();
                    let loaiId = $('#loai_nguyen_lieu_id').val();

                    $.ajax({
                        url: '/api/ton-kho/xuat-dung',
                        type: 'GET',
                        data: {
                            ngay: ngay,
                            loai_nguyen_lieu_id: loaiId
                        },
                        success: function(res) {
                            let tbody = $('#data-body-ton-kho');
                            tbody.empty();

                            if (res.length === 0) {
                                tbody.append(`<tr><td colspan="12">🚫 Không có dữ liệu</td></tr>`);
                                return;
                            }

                            $.each(res, function(index, item) {
                                let trangThai = item.da_ngung_su_dung ?
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
                });

                // Lọc Hạn Sử Dụng
                $('#filter-han-su-dung-form').on('submit', function(e) {
                    e.preventDefault();

                    let ngay = $('#ngay-hsd').val();
                    let loaiId = $('#loai_nguyen_lieu_id_hsd').val();
                    let tbody = $('#data-body-han-su-dung');
                    tbody.html(`<tr><td colspan="7">⏳ Đang tải dữ liệu...</td></tr>`);

                    $.ajax({
                        url: '{{ route('nguyen-lieu.hansudung') }}',
                        type: 'GET',
                        data: {
                            ngay: ngay,
                            loai_nguyen_lieu_id: loaiId
                        },
                        success: function(res) {
                            tbody.empty();

                            if (!res.data || res.data.length === 0) {
                                tbody.append(`<tr><td colspan="7">🚫 Không có dữ liệu</td></tr>`);
                                return;
                            }

                            $.each(res.data, function(index, item) {
                                let badgeClass = '',
                                    icon = '',
                                    status = '';
                                let soNgay = item.so_ngay_con_lai;

                                if (typeof soNgay === 'string') {
                                    badgeClass = 'danger';
                                    icon = 'bi bi-x-circle-fill text-danger';
                                    status = 'Đã hết hạn';
                                    soNgay = 'Đã hết hạn';
                                } else if (soNgay <= 7) {
                                    badgeClass = 'warning';
                                    icon = 'bi bi-exclamation-triangle-fill text-warning';
                                    status = 'Sắp hết hạn';
                                    soNgay = `${soNgay} ngày`;
                                } else {
                                    badgeClass = 'success';
                                    icon = 'bi bi-check-circle-fill text-success';
                                    status = 'Còn hạn';
                                    soNgay = `${soNgay} ngày`;
                                }

                                let chartId = `chart-${index}`;

                                tbody.append(`
    <tr>
        <td>${index + 1}</td>
        <td class="text-start" title="${item.nguyen_lieu}">${item.nguyen_lieu}</td>
        <td>${item.don_vi}</td>
        <td>${item.so_luong_ton}</td>
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
            <canvas id="${chartId}" class="chart-canvas"></canvas>
        </td>
    </tr>
`);


                                // Render biểu đồ tròn
                                setTimeout(() => {
                                    new Chart(document.getElementById(chartId), {
                                        type: 'doughnut',
                                        data: {
                                            labels: ['Còn hạn',
                                                'Sắp hết hạn', 'Hết hạn'
                                            ],
                                            datasets: [{
                                                label: 'Tình trạng',
                                                data: [item.con_han,
                                                    item
                                                    .sap_het_han,
                                                    item.het_han
                                                ],
                                                backgroundColor: [
                                                    '#198754',
                                                    '#ffc107',
                                                    '#dc3545'
                                                ],
                                                borderWidth: 1,
                                                dataDetails: item
                                                    .lo_nhap ?? []
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
                                                            const
                                                                index =
                                                                context
                                                                .dataIndex;
                                                            const lo =
                                                                context
                                                                .dataset
                                                                .dataDetails
                                                                ?.[
                                                                    index];

                                                            if (!lo) {
                                                                return `Số lượng: ${context.formattedValue}`;
                                                            }

                                                            return [
                                                                `Số lượng: ${lo.so_luong}`,
                                                                `Ngày nhập: ${lo.ngay_nhap}`,
                                                                `HSD: ${lo.han_su_dung}`
                                                            ];
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    });
                                }, 100);
                            });

                            // Kích hoạt tooltip Bootstrap
                            const tooltipTriggerList = [].slice.call(document.querySelectorAll(
                                '[data-bs-toggle="tooltip"]'));
                            tooltipTriggerList.map(t => new bootstrap.Tooltip(t));
                        },
                        error: function() {
                            alert('❌ Lỗi khi lấy dữ liệu hạn sử dụng!');
                        }
                    });
                });
            });
        </script>

        <style>
            .chart-canvas {
                width: 130px !important;
                height: 80px !important;
                max-width: 130px;
                max-height: 80px;
                display: block;
                margin: 0 auto;
            }
        </style>
    @endsection
