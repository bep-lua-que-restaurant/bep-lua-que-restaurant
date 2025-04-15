@extends('layouts.admin')

@section('title', 'Kiểm Tra Tồn Kho')

@section('content')
    <div class="container py-4">
        <h4 class="mb-4">📦 Kiểm tra tồn kho</h4>
        <p class="fst-italic text-danger small">* Nguyên liệu hiển thị màu đỏ là đã ngưng sử dụng</p>

        <ul class="nav nav-tabs mb-3" id="tonKhoTabs">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#tab-xuat-dung">Xuất - Dùng</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#tab-han-su-dung">Hạn sử dụng</a>
            </li>
        </ul>

        <div class="tab-content">
            {{-- Tab xuất dùng --}}
            <div class="tab-pane fade show active" id="tab-xuat-dung">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="ngay">Ngày:</label>
                        <input type="date" id="ngay" class="form-control" value="{{ now()->toDateString() }}">
                    </div>
                    <div class="col-md-4">
                        <label for="loai_nguyen_lieu">Loại nguyên liệu:</label>
                        <select id="loai_nguyen_lieu" class="form-select">
                            <option value="">-- Tất cả --</option>
                            @foreach ($loaiNguyenLieus as $loai)
                                <option value="{{ $loai->id }}">{{ $loai->ten_loai }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="tonKhoTable" class="table table-hover table-bordered table-striped align-middle">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>Nguyên liệu</th>
                                <th>Tồn kho</th>
                                <th>Đã xuất (Tất cả)</th>
                                <th>Đã xuất (bếp)</th>
                                <th>Đã xuất (trả hàng)</th>
                                <th>Đã xuất (hủy)</th>
                                <th>Đã dùng (theo món)</th>
                                <th>Chênh lệch (Xuất - Dùng)</th>
                                <th>Cảnh báo</th>
                            </tr>
                        </thead>
                        <tbody id="data-body" class="text-center"></tbody>
                        <tfoot class="table-light fw-bold text-center">
                            <tr>
                                <td>Tổng</td>
                                <td id="sum-ton-kho"></td>
                                <td id="sum-tong-xuat"></td>
                                <td id="sum-xuat-bep"></td>
                                <td id="sum-xuat-tra"></td>
                                <td id="sum-xuat-huy"></td>
                                <td id="sum-da-dung"></td>
                                <td id="sum-chenh-lech"></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Tab hạn sử dụng --}}
            <div class="tab-pane fade" id="tab-han-su-dung">
                <canvas id="hanSuDungChart" height="250" style="max-width: 400px; margin: 0 auto;"></canvas>

                <div class="table-responsive mt-4">
                    <table class="table table-bordered table-hover text-center align-middle">
                        <thead class="table-secondary">
                            <tr>
                                <th>Nguyên liệu</th>
                                <th>Số lượng</th>
                                <th>Đơn vị</th>
                                <th>Hạn sử dụng còn lại (ngày)</th>
                            </tr>
                        </thead>
                        <tbody id="hanSuDungTableBody">
                            {{-- Dữ liệu sẽ được đổ bằng JS --}}
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        <div class="text-end mt-4">
            <a href="{{ route('nguyen-lieu.index') }}" class="btn btn-secondary">
                ← Quay lại danh sách
            </a>
        </div>
    </div>

    {{-- Scripts --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        let dataTable;

        function formatNumber(num) {
            return num ? parseFloat(num).toLocaleString('vi-VN', {
                maximumFractionDigits: 2
            }) : '0';
        }

        function loadTonKho() {
            let ngay = $('#ngay').val();
            let loaiId = $('#loai_nguyen_lieu').val();

            $.get('/api/ton-kho/xuat-dung', {
                ngay: ngay,
                loai_nguyen_lieu_id: loaiId
            }, function(data) {
                let html = '';
                let tongTonKho = 0,
                    tongXuat = 0,
                    tongXuatBep = 0,
                    tongXuatTra = 0,
                    tongXuatHuy = 0,
                    tongDung = 0,
                    tongChenhLech = 0;

                data.forEach(item => {
                    let warning = item.chenh_lech_xuat_dung < 0 ? '⚠️' : '';
                    let rowClass = item.chenh_lech_xuat_dung < 0 ? 'table-danger' : '';
                    let style = item.da_ngung_su_dung ?
                        'color: #dc3545; font-style: italic; opacity: 0.7;' : '';

                    let tonKho = parseFloat(item.ton_kho_hien_tai) || 0;
                    let tongXuatItem = parseFloat(item.tong_da_xuat) || 0;
                    let xuatBep = parseFloat(item.xuat_bep) || 0;
                    let xuatTra = parseFloat(item.xuat_tra_hang) || 0;
                    let xuatHuy = parseFloat(item.xuat_huy) || 0;
                    let daDung = parseFloat(item.da_dung) || 0;
                    let chenhLech = parseFloat(item.chenh_lech_xuat_dung) || 0;

                    tongTonKho += tonKho;
                    tongXuat += tongXuatItem;
                    tongXuatBep += xuatBep;
                    tongXuatTra += xuatTra;
                    tongXuatHuy += xuatHuy;
                    tongDung += daDung;
                    tongChenhLech += chenhLech;

                    html += `
                    <tr class="${rowClass}">
                        <td style="${style}">${item.nguyen_lieu}</td>
                        <td style="${style}">${formatNumber(tonKho)}</td>
                        <td style="${style}">${formatNumber(tongXuatItem)}</td>
                        <td style="${style}">${formatNumber(xuatBep)}</td>
                        <td style="${style}">${formatNumber(xuatTra)}</td>
                        <td style="${style}">${formatNumber(xuatHuy)}</td>
                        <td style="${style}">${formatNumber(daDung)}</td>
                        <td style="${style}">${formatNumber(chenhLech)}</td>
                        <td style="${style}">${warning} ${item.can_nhap_them}</td>
                    </tr>`;
                });

                if (dataTable) {
                    dataTable.destroy();
                    $('#tonKhoTable tbody').remove();
                    $('#tonKhoTable').append('<tbody id="data-body" class="text-center"></tbody>');
                }

                $('#data-body').html(html);
                $('#sum-ton-kho').text(formatNumber(tongTonKho));
                $('#sum-tong-xuat').text(formatNumber(tongXuat));
                $('#sum-xuat-bep').text(formatNumber(tongXuatBep));
                $('#sum-xuat-tra').text(formatNumber(tongXuatTra));
                $('#sum-xuat-huy').text(formatNumber(tongXuatHuy));
                $('#sum-da-dung').text(formatNumber(tongDung));
                $('#sum-chenh-lech').text(formatNumber(tongChenhLech));

                dataTable = $('#tonKhoTable').DataTable({
                    paging: true,
                    searching: true,
                    ordering: true,
                    language: {
                        search: "🔍 Tìm kiếm:",
                        lengthMenu: "Hiển thị _MENU_ dòng",
                        info: "Hiển thị _START_ đến _END_ của _TOTAL_ dòng",
                        paginate: {
                            first: "Đầu",
                            last: "Cuối",
                            next: "›",
                            previous: "‹"
                        },
                        emptyTable: "Không có dữ liệu tồn kho"
                    }
                });
            });
        }

        $(document).ready(function() {
            loadTonKho();
            $('#ngay, #loai_nguyen_lieu').change(loadTonKho);
        });

        // Biểu đồ hạn sử dụng mới - Pie Chart + Bảng nguyên liệu
        let chartLoaded = false;

        $(document).ready(function() {
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                if (e.target.getAttribute('href') === '#tab-han-su-dung' && !chartLoaded) {
                    $.get("{{ route('nguyen-lieu.hansudung') }}", function(res) {
                        const pieData = {
                            conHan: 0,
                            sapHetHan: 0,
                            hetHan: 0
                        };

                        // Đếm tổng số nguyên liệu mỗi loại
                        res.data.forEach(item => {
                            pieData.conHan += item.con_han > 0 ? 1 : 0;
                            pieData.sapHetHan += item.sap_het_han > 0 ? 1 : 0;
                            pieData.hetHan += item.het_han > 0 ? 1 : 0;
                        });

                        // Pie chart
                        const ctx = document.getElementById('hanSuDungChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: ['Còn hạn', 'Sắp hết hạn', 'Hết hạn'],
                                datasets: [{
                                    data: [
                                        res.data.reduce((sum, i) => sum + i
                                            .con_han, 0),
                                        res.data.reduce((sum, i) => sum + i
                                            .sap_het_han, 0),
                                        res.data.reduce((sum, i) => sum + i
                                            .het_han, 0),
                                    ],
                                    backgroundColor: [
                                        'rgba(75, 192, 192, 0.7)', // Còn hạn
                                        'rgba(255, 206, 86, 0.7)', // Sắp hết hạn
                                        'rgba(255, 99, 132, 0.7)', // Hết hạn
                                    ],
                                    hoverOffset: 10
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'bottom'
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                let total = context.chart._metasets[0]
                                                    .total;
                                                let value = context.raw;
                                                let percent = ((value / total) * 100)
                                                    .toFixed(1);
                                                return `${context.label}: ${value} (${percent}%)`;
                                            }
                                        }
                                    },
                                    title: {
                                        display: true,
                                        text: 'Tình trạng hạn sử dụng (phần trăm)'
                                    }
                                }
                            }
                        });


                        // Hiển thị danh sách nguyên liệu bên dưới
                        let tableHtml = '';
                        res.data.forEach(item => {
                            tableHtml += `
                                            <tr>
                                                <td>${item.nguyen_lieu}</td>
                                                <td>${formatNumber(item.so_luong_ton)} </td>
                                                <td>${item.don_vi}</td>
                                                <td>${item.so_ngay_con_lai}</td>
                                            </tr>
                                                `;
                        });
                        $('#hanSuDungTableBody').html(tableHtml);


                      

                        // Lọc bảng
                        $('#filter-hsd').on('change', function() {
                            let val = $(this).val();
                            $('#table-hsd tbody tr').each(function() {
                                let status = $(this).data('status');
                                if (val === '' || status === val) {
                                    $(this).show();
                                } else {
                                    $(this).hide();
                                }
                            });
                        });

                        chartLoaded = true;
                    }).fail(function() {
                        console.error("Lỗi khi lấy dữ liệu hạn sử dụng từ server.");
                    });
                }
            });
        });
    </script>
@endsection
