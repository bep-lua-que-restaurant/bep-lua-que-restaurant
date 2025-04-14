@extends('layouts.admin')

@section('title', 'Kiểm Tra Tồn Kho')

@section('content')
    <div class="container py-4">
        <h4 class="mb-4">📦 Kiểm tra tồn kho - xuất - dùng</h4>
        <p class="fst-italic text-danger small">* Nguyên liệu hiển thị màu đỏ là đã ngưng sử dụng</p>

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

    <!-- DataTables + jQuery -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

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
                    let style = item.da_ngung_su_dung ? 'color: #dc3545; font-style: italic; opacity: 0.7;' : '';

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

                // Reset bảng trước khi vẽ lại
                if (dataTable) {
                    dataTable.destroy();
                    $('#tonKhoTable tbody').remove();
                    $('#tonKhoTable').append('<tbody id="data-body" class="text-center"></tbody>');
                }

                $('#data-body').html(html);

                // Tổng cộng
                $('#sum-ton-kho').text(formatNumber(tongTonKho));
                $('#sum-tong-xuat').text(formatNumber(tongXuat));
                $('#sum-xuat-bep').text(formatNumber(tongXuatBep));
                $('#sum-xuat-tra').text(formatNumber(tongXuatTra));
                $('#sum-xuat-huy').text(formatNumber(tongXuatHuy));
                $('#sum-da-dung').text(formatNumber(tongDung));
                $('#sum-chenh-lech').text(formatNumber(tongChenhLech));

                // Khởi tạo lại DataTable
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
    </script>
@endsection
