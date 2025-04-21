@extends('gdnhanvien.datban.layout')

@section('title')
    Danh mục Bàn Ăn
@endsection

@section('content')
    <div class="row">
        <div id="booked-list-section" class="container-fluid">
            <h2 class=" my-3">Danh sách bàn đã được đặt</h2>

            <!-- Bộ lọc tìm kiếm -->
            <div class="row d-flex p-3">
                <div class="col-9">
                    <input type="text" id="searchBanDat" class="form-control"
                        placeholder="Tìm theo họ tên hoặc số điện thoại">
                </div>
                <div class="col-3">
                    <select id="trang_thai" class="form-control">
                        <option value="">Tất cả trạng thái</option>
                        <option value="dang_xu_ly">Đang xử lý</option>
                        <option value="xac_nhan">Xác nhận</option>
                        <option value="da_huy">Đã hủy</option>
                        <option value="da_thanh_toan">Đã thanh toán </option>
                    </select>
                </div>
            </div>

            <!-- Bảng dữ liệu -->
            <div class="table-responsive">
                <table class="table table-bordered w-100" id="tableBanDat">
                    <thead class="text-center">
                        <tr>
                            <th>Thời Gian Đến</th>
                            <th>Họ Tên</th>
                            <th>Số Điện Thoại</th>
                            <th>Số Người</th>
                            <th>Danh Sách Bàn</th>
                            <th>Trạng Thái</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($banhSachDatban as $datban)
                            <tr class="text-center" data-id="{{ $datban->ma_dat_ban }}">
                                <td class="align-middle">
                                    {{ \Carbon\Carbon::parse($datban->thoi_gian_den)->format('d/m/Y H:i') }}</td>
                                <td class="align-middle">{{ $datban->ho_ten }}</td>
                                <td class="align-middle">{{ $datban->so_dien_thoai }}</td>
                                <td class="align-middle">{{ $datban->so_nguoi }}</td>

                                <td>
                                    <ul class="list-unstyled mb-0">
                                        @foreach (explode(',', $datban->danh_sach_ban) as $ban)
                                            <li><span class="badge bg-primary">{{ trim($ban) }}</span></li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="align-middle">
                                    @if ($datban->trang_thai == 'xac_nhan')
                                        <span class="badge bg-success trang_thai" data-value="{{ $datban->trang_thai }}">
                                            Đã nhận bàn</span>
                                    @elseif ($datban->trang_thai == 'dang_xu_ly')
                                        <span class="badge bg-warning trang_thai" data-value="{{ $datban->trang_thai }}">
                                            Đang xử lý</span>
                                    @elseif ($datban->trang_thai == 'da_thanh_toan')
                                        <span class="badge bg-warning trang_thai" data-value="{{ $datban->trang_thai }}">
                                            Đã thanh toán</span>
                                    @else
                                        <span class="badge bg-danger trang_thai" data-value="{{ $datban->trang_thai }}">
                                            Đã hủy</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <a href="{{ route('dat-ban.show', $datban->ma_dat_ban) }}"
                                        class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if ($datban->trang_thai === 'dang_xu_ly')
                                        <form action="{{ route('dat-ban.destroy', $datban->ma_dat_ban) }}" method="post"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hủy đặt"
                                                onclick="return confirm('Bạn có chắc chắn muốn hủy đặt bàn này không?');">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>

                                        @if (
                                            \Carbon\Carbon::parse($datban->thoi_gian_den)->isSameDay($today) &&
                                                \Carbon\Carbon::parse($datban->thoi_gian_den)->diffInMinutes(\Carbon\Carbon::now()) <= 60)
                                            <a href="{{ route('dat-ban.edit', $datban->ma_dat_ban) }}"
                                                class="btn btn-success btn-sm">
                                                <i class="fas fa-check"></i>
                                            </a>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById("searchBanDat");
            const selectStatus = document.getElementById("trang_thai");
            const tableRows = document.querySelectorAll("#tableBanDat tbody tr");

            function filterTable() {
                const searchValue = searchInput.value.toLowerCase();
                const selectedStatus = selectStatus.value.toLowerCase();

                tableRows.forEach(row => {
                    const name = row.children[1].textContent.toLowerCase();
                    const phone = row.children[2].textContent.toLowerCase();
                    const statusElement = row.querySelector(".trang_thai");
                    const status = statusElement ? statusElement.dataset.value.toLowerCase() : "";

                    const matchesSearch = [name, phone].some(text => text.includes(searchValue));
                    const matchesStatus = !selectedStatus || status === selectedStatus;

                    row.style.display = matchesSearch && matchesStatus ? "" : "none";
                });
            }

            searchInput.addEventListener("input", filterTable);
            selectStatus.addEventListener("change", filterTable);
        });
    </script>
    <script>
        setInterval(() => {
            fetch('/api/update-datban')
                .then(response => response.json())
                .then(data => console.log(data.message));
        }, 60000); // 60000ms = 1 phút
    </script>
    @vite('resources/js/danhsach.js')

    {{ $banhSachDatban->links('pagination::bootstrap-5') }}
@endsection
