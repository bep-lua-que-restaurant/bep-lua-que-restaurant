@extends('layouts.admin')

@section('title', 'Kiểm Tra Tồn Kho')

@section('content')
<div class="container">
    <h3 class="mb-4">📊 Kiểm Tra Tồn Kho ({{ $ngay->format('d/m/Y') }})</h3>

    <div class="text-end mt-4">
        <a href="{{ route('nguyen-lieu.index') }}" class="btn btn-secondary">← Quay lại danh sách</a>
    </div>

    <form action="{{ route('nguyen-lieu.kiemtra') }}" method="GET" class="row g-2 my-4">
        <div class="col-auto">
            <input type="date" name="ngay" class="form-control" value="{{ $ngay->toDateString() }}">
        </div>
        <div class="col-auto">
            <select name="loai_nguyen_lieu_id" class="form-select">
                <option value="">-- Tất cả loại --</option>
                @foreach ($dsLoai as $loai)
                    <option value="{{ $loai->id }}" @selected($loai->id == $loaiId)>
                        {{ $loai->ten_loai }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary">Xem báo cáo</button>
        </div>
    </form>

    <!-- Tabs biểu đồ -->
    <ul class="nav nav-tabs mt-5" id="chartTabs">
        @php $tabs = [
            ['id' => 'xuat-dung', 'label' => '📈 Xuất - Dùng nguyên liệu', 'active' => true],
            ['id' => 'dinh-muc', 'label' => '📉 So với định mức sử dụng']
        ]; @endphp

        @foreach ($tabs as $tab)
            <li class="nav-item">
                <button class="nav-link @if($tab['active'] ?? false) active @endif" id="{{ $tab['id'] }}-tab"
                    data-bs-toggle="tab" data-bs-target="#{{ $tab['id'] }}" type="button" role="tab">
                    {{ $tab['label'] }}
                </button>
            </li>
        @endforeach
    </ul>

    <div class="tab-content border border-top-0 p-3" id="chartTabsContent">
        <div class="tab-pane fade show active" id="xuat-dung">
            <canvas id="tonKhoChart" height="100"></canvas>
        </div>
        <div class="tab-pane fade" id="dinh-muc">
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
                    @php
                        $rowClass = $item['chenh_lech'] < 0 ? 'table-danger' : ($item['chenh_lech'] > 5 ? 'table-warning' : '');
                        $badge = $item['chenh_lech'] < 0 ? ['bg-danger', 'Thiếu'] :
                                 ($item['chenh_lech'] > 5 ? ['bg-warning text-dark', 'Xuất dư'] :
                                 ['bg-success', 'OK']);
                    @endphp
                    <tr class="{{ $rowClass }}">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item['nguyen_lieu'] }}</td>
                        <td>{{ $item['don_vi'] }}</td>
                        <td>{{ number_format($item['ton_kho_hien_tai'], 2) }}</td>
                        <td>{{ number_format($item['da_xuat'], 2) }}</td>
                        <td>{{ number_format($item['da_dung'], 2) }}</td>
                        <td>{{ number_format($item['chenh_lech'], 2) }}</td>
                        <td><span class="badge {{ $badge[0] }}">{{ $badge[1] }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Script + Styles -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const tonKhoChart = new Chart(document.getElementById('tonKhoChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($duLieuTonKho->pluck('nguyen_lieu')) !!},
            datasets: [
                {
                    label: 'Đã Xuất',
                    data: {!! json_encode($duLieuTonKho->pluck('da_xuat')) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.7)'
                },
                {
                    label: 'Đã Dùng',
                    data: {!! json_encode($duLieuTonKho->pluck('da_dung')) !!},
                    backgroundColor: 'rgba(255, 99, 132, 0.7)'
                },
                {
                    label: 'Chênh Lệch',
                    data: {!! json_encode($duLieuTonKho->pluck('chenh_lech')) !!},
                    backgroundColor: 'rgba(255, 206, 86, 0.7)'
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: { display: true, text: 'Tình trạng xuất - dùng nguyên liệu trong ngày' },
                legend: { position: 'top' },
                tooltip: { mode: 'index', intersect: false }
            },
            scales: {
                x: { ticks: { autoSkip: false, maxRotation: 45, minRotation: 30 }},
                y: { beginAtZero: true }
            }
        }
    });

    const dinhMucChart = new Chart(document.getElementById('dinhMucChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($duLieuDinhMuc->pluck('nguyen_lieu')) !!},
            datasets: [
                {
                    label: 'Tồn kho hiện tại',
                    data: {!! json_encode($duLieuDinhMuc->pluck('ton_kho')) !!},
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: false,
                    tension: 0.3
                },
                {
                    label: 'Định mức trung bình',
                    data: {!! json_encode($duLieuDinhMuc->pluck('dinh_muc_trung_binh')) !!},
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    fill: false,
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: { display: true, text: '📉 So sánh tồn kho với định mức sử dụng trung bình' },
                legend: { position: 'top' },
                tooltip: { mode: 'index', intersect: false }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Số lượng' }
                },
                x: {
                    ticks: { autoSkip: false, maxRotation: 45, minRotation: 30 }
                }
            }
        }
    });

    $(document).ready(() => {
        $('#tonKhoTable').DataTable({ responsive: true });
    });
</script>
@endsection
