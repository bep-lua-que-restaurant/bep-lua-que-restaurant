@extends('layouts.admin')

@section('title', 'Kiểm Tra Tồn Kho')

@section('content')
    <div class="container">
        <h3 class="mb-4">
            📊 Kiểm Tra Tồn Kho ({{ $ngay->format('d/m/Y') }})
        </h3>
        <div class="text-end mt-4">
            <a href="{{ route('nguyen-lieu.index') }}" class="btn btn-secondary">
                ← Quay lại danh sách
            </a>
        </div>
        <!-- Form chọn ngày và loại nguyên liệu -->
        <form action="{{ route('nguyen-lieu.kiemtra') }}" method="GET" class="mb-4 row g-2">
            <div class="col-auto">
                <input type="date" name="ngay" class="form-control" value="{{ $ngay->toDateString() }}">
            </div>
            <div class="col-auto">
                <select name="loai_nguyen_lieu_id" class="form-select">
                    <option value="">-- Tất cả loại --</option>
                    @foreach ($dsLoai as $loai)
                        <option value="{{ $loai->id }}" {{ $loai->id == $loaiId ? 'selected' : '' }}>
                            {{ $loai->ten_loai }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Xem báo cáo</button>
            </div>
        </form>


        <!-- Tabs biểu đồ -->
        <ul class="nav nav-tabs mt-5" id="chartTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="xuat-dung-tab" data-bs-toggle="tab" data-bs-target="#xuat-dung"
                    type="button" role="tab">
                    📈 Xuất - Dùng nguyên liệu
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="dinh-muc-tab" data-bs-toggle="tab" data-bs-target="#dinh-muc" type="button"
                    role="tab">
                    📉 So với định mức sử dụng
                </button>
            </li>
        </ul>

        <div class="tab-content border border-top-0 p-3" id="chartTabsContent">
            <div class="tab-pane fade show active" id="xuat-dung" role="tabpanel">
                <canvas id="tonKhoChart" height="100"></canvas>
            </div>
            <div class="tab-pane fade" id="dinh-muc" role="tabpanel">
                <canvas id="dinhMucChart" height="100"></canvas>
            </div>
        </div>


        <div class="table-responsive mt-4">
            <table id="tonKhoTable" class="table table-bordered table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nguyên Liệu</th>
                        <th>Đơn Vị</th>
                        <th>Tồn Kho Hiện Tại</th>
                        <th>Đã Xuất Hôm Nay</th>
                        <th>Đã Dùng Hôm Nay</th>
                        <th>Chênh Lệch</th>
                        <th>Gợi Ý</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($duLieuTonKho as $index => $item)
                        <tr
                            @if ($item['chenh_lech'] < 0) class="table-danger"
                            @elseif($item['chenh_lech'] > 5) class="table-warning" @endif>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item['nguyen_lieu'] }}</td>
                            <td>{{ $item['don_vi'] }}</td>
                            <td>{{ number_format($item['ton_kho_hien_tai'], 2) }}</td>
                            <td>{{ number_format($item['da_xuat'], 2) }}</td>
                            <td>{{ number_format($item['da_dung'], 2) }}</td>
                            <td>{{ number_format($item['chenh_lech'], 2) }}</td>
                            <td>
                                @if ($item['chenh_lech'] < 0)
                                    <span class="badge bg-danger">Thiếu</span>
                                @elseif($item['chenh_lech'] > 5)
                                    <span class="badge bg-warning text-dark">Xuất dư</span>
                                @else
                                    <span class="badge bg-success">OK</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Chart + DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Biểu đồ 1: Xuất - Dùng - Chênh lệch
        const labels = {!! json_encode($duLieuTonKho->pluck('nguyen_lieu')) !!};
        const daXuat = {!! json_encode($duLieuTonKho->pluck('da_xuat')) !!};
        const daDung = {!! json_encode($duLieuTonKho->pluck('da_dung')) !!};
        const chenhLech = {!! json_encode($duLieuTonKho->pluck('chenh_lech')) !!};

        const ctx = document.getElementById('tonKhoChart').getContext('2d');
        const tonKhoChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Đã Xuất',
                        data: daXuat,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Đã Dùng',
                        data: daDung,
                        backgroundColor: 'rgba(255, 99, 132, 0.7)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Chênh Lệch',
                        data: chenhLech,
                        backgroundColor: 'rgba(255, 206, 86, 0.7)',
                        borderColor: 'rgba(255, 206, 86, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    },
                    legend: {
                        position: 'top'
                    },
                    title: {
                        display: true,
                        text: 'Tình trạng xuất - dùng nguyên liệu trong ngày'
                    }
                },
                scales: {
                    x: {
                        stacked: false,
                        ticks: {
                            autoSkip: false,
                            maxRotation: 45,
                            minRotation: 30
                        }
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Biểu đồ 2: Tồn kho vs Trung bình sử dụng/ngày
        const labelsDM = {!! json_encode($duLieuDinhMuc->pluck('nguyen_lieu')) !!};
        const tonKhoDM = {!! json_encode($duLieuDinhMuc->pluck('ton_kho')) !!};
        const tbSuDungDM = {!! json_encode($duLieuDinhMuc->pluck('trung_binh_su_dung')) !!};

        const ctx2 = document.getElementById('dinhMucChart').getContext('2d');

        const dinhMucChart = new Chart(ctx2, {
            type: 'line',
            data: {
                labels: {!! json_encode($duLieuDinhMuc->pluck('nguyen_lieu')) !!},
                datasets: [{
                        label: 'Tồn kho hiện tại',
                        data: {!! json_encode($duLieuDinhMuc->pluck('ton_kho')) !!},
                        fill: false,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        tension: 0.3
                    },
                    {
                        label: 'Định mức trung bình',
                        data: {!! json_encode($duLieuDinhMuc->pluck('dinh_muc_trung_binh')) !!},
                        fill: false,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: '📉 So sánh tồn kho với định mức sử dụng trung bình'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    },
                    legend: {
                        position: 'top'
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Số lượng'
                        }
                    },
                    x: {
                        ticks: {
                            autoSkip: false,
                            maxRotation: 45,
                            minRotation: 30
                        }
                    }
                }
            }
        });
    </script>

@endsection
