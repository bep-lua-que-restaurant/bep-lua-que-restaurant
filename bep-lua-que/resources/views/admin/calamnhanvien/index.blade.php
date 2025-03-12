@extends('layouts.admin')
@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>📅 Bảng Lịch Làm Việc</h4>
            <!-- 🔍 FORM TÌM KIẾM -->
            <form action="{{ route('ca-lam-nhan-vien.index') }}" method="GET" class="d-flex gap-2">
                <!-- Tìm theo tên nhân viên -->
                <input type="text" name="search_nhanvien" class="form-control" placeholder="🔍 Tìm nhân viên..."
                    value="{{ request('search_nhanvien') }}">

                <!-- Tìm theo ca làm -->
                <select name="search_ca" class="form-select">
                    <option value="">🔎 Chọn ca làm</option>
                    @foreach ($caLams as $caLam)
                        <option value="{{ $caLam->id }}" {{ request('search_ca') == $caLam->id ? 'selected' : '' }}>
                            {{ $caLam->ten_ca }} ({{ $caLam->gio_bat_dau }} - {{ $caLam->gio_ket_thuc }})
                        </option>
                    @endforeach
                </select>

                <!-- Tìm theo ngày làm -->
                <input type="date" name="search_ngaylam" class="form-control" value="{{ request('search_ngaylam') }}">

                <!-- Nút tìm kiếm -->
                <button type="submit" class="btn btn-primary">🔎 Lọc</button>

                <!-- Nút reset tìm kiếm -->
                <a href="{{ route('ca-lam-nhan-vien.index') }}" class="btn btn-secondary">🔄 Reset</a>
            </form>

            <a href="{{ route('ca-lam-nhan-vien.export') }}" class="btn btn-success">📤 Xuất file</a>
        </div>

    </div>

    <div class="d-flex justify-content-between mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addShiftModal">➕ Thêm Ca Làm</button>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th style="background-color: #198754;">Ca làm việc</th>
                    <th style="background-color: #198754;">Ngày làm</th>
                    <th style="background-color: #198754;">Nhân viên</th>
                    <th style="background-color: #198754;">Trạng thái</th>
                    <th style="background-color: #198754;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($caLamNhanViens) && $caLamNhanViens->count() > 0)
                    @foreach ($caLamNhanViens as $caLamNhanVien)
                        <tr>
                            <td>
                                {{ optional($caLamNhanVien->caLam)->ten_ca ?? 'Chưa có ca' }}
                                ({{ optional($caLamNhanVien->caLam)->gio_bat_dau ?? '--:--' }} -
                                {{ optional($caLamNhanVien->caLam)->gio_ket_thuc ?? '--:--' }})
                            </td>
                            <td>{{ $caLamNhanVien->ngay_lam }}</td>
                            <td>{{ optional($caLamNhanVien->nhanVien)->ho_ten ?? 'Không có nhân viên' }}</td>
                            <td>
                                <span
                                    class='badge bg-{{ trim($caLamNhanVien->trang_thai) === 'Chờ duyệt' ? 'warning' : 'success' }}'>
                                    {{ $caLamNhanVien->trang_thai }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#updateShiftModal"
                                    onclick="setUpdateShift({{ $caLamNhanVien->id }}, '{{ $caLamNhanVien->nhan_vien_id }}', '{{ $caLamNhanVien->ca_lam_id }}', '{{ $caLamNhanVien->ngay_lam }}')">✏️
                                    Cập nhật</button>
                               
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalXoaCa"
                                    onclick="setXoaCa({{ $caLamNhanVien->id }})">🗑️ Xóa
                                </button>
                              
                                @if (strcasecmp(trim($caLamNhanVien->trang_thai), 'Chờ duyệt') == 0)
                                    
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5">Không có dữ liệu lịch làm việc.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    </div>
    {{-- //them ca --}}
    <div class="modal fade" id="addShiftModal" tabindex="-1" aria-labelledby="addShiftModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addShiftModalLabel">Thêm Ca Làm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form method="POST" action="{{ route('ca-lam-nhan-vien.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="employee" class="form-label">Nhân viên</label>
                            <select class="form-select" id="employee" name="nhan_vien_id" required>
                                @foreach ($nhanViens as $nhanVien)
                                    <option value="{{ $nhanVien->id }}">{{ $nhanVien->ho_ten }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="shift" class="form-label">Ca làm</label>
                            <select class="form-select" id="shift" name="ca_lam_id" required>
                                @foreach ($caLams as $caLam)
                                    <option value="{{ $caLam->id }}">
                                        {{ $caLam->ten_ca }} ({{ $caLam->gio_bat_dau }} - {{ $caLam->gio_ket_thuc }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="workDate" class="form-label">Ngày làm</label>
                            <input type="date" class="form-control" id="workDate" name="ngay_lam" required>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">❌ Hủy</button>
                            <button type="submit" class="btn btn-primary">💾 Lưu ca</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Cập Nhật Ca Làm -->
    <div class="modal fade" id="updateShiftModal" tabindex="-1" aria-labelledby="updateShiftModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateShiftModalLabel">Cập Nhật Ca Làm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('ca-lam-nhan-vien.update', ':id') }}" id="updateShiftForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="updateShiftId" name="id">
                        <div class="mb-3">
                            <label for="updateEmployee" class="form-label">Nhân viên</label>
                            <select class="form-select" id="updateEmployee" name="nhan_vien_id" required>
                                @foreach ($nhanViens as $nhanVien)
                                    <option value="{{ $nhanVien->id }}">{{ $nhanVien->ho_ten }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="updateShift" class="form-label">Ca làm</label>
                            <select class="form-select" id="updateShift" name="ca_lam_id" required>
                                @foreach ($caLams as $caLam)
                                    <option value="{{ $caLam->id }}">{{ $caLam->ten_ca }} ({{ $caLam->gio_bat_dau }}
                                        - {{ $caLam->gio_ket_thuc }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="updateWorkDate" class="form-label">Ngày làm</label>
                            <input type="date" class="form-control" id="updateWorkDate" name="ngay_lam" required>
                        </div>
                        <button type="submit" class="btn btn-primary">💾 Cập Nhật</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <script>
        function setUpdateShift(id, nhanVienId, caLamId, ngayLam) {
            document.getElementById('updateShiftId').value = id;
            document.getElementById('updateEmployee').value = nhanVienId;
            document.getElementById('updateShift').value = caLamId;
            document.getElementById('updateWorkDate').value = ngayLam;
            document.getElementById('updateShiftForm').action = document.getElementById('updateShiftForm').action.replace(
                ':id', id);
        }

        function setUpdateShift(id, nhanVienId, caLamId, ngayLam) {
            document.getElementById('updateShiftId').value = id;
            document.getElementById('updateEmployee').value = nhanVienId;
            document.getElementById('updateShift').value = caLamId;
            document.getElementById('updateWorkDate').value = ngayLam;

            let form = document.getElementById('updateShiftForm');
            form.action = form.action.replace(':id', id);
        }

        function setDoiCa(id, nhanVienId, caLamId, ngayLam) {
            document.getElementById('doiCaId').value = id;
            let form = document.getElementById('doiCaForm');
            form.action = form.action.replace(':id', id);
        }
    </script>

    <!-- Modal Xóa Ca Làm Nhân Viên -->
    <div class="modal fade" id="modalXoaCa" tabindex="-1" aria-labelledby="modalXoaCaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalXoaCaLabel">Xóa Ca Làm Nhân Viên</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa ca làm này không?</p>
                </div>
                <div class="modal-footer">
                    <form method="POST" action="{{ route('ca-lam-nhan-vien.destroy', ':id') }}" id="xoaCaForm">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" id="xoaCaId" name="id">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">❌ Hủy</button>
                        <button type="submit" class="btn btn-danger">🗑️ Xóa</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        function setXoaCa(id) {
            console.log("Đang xóa ca với ID:", id); // Kiểm tra xem hàm có chạy không
            document.getElementById('xoaCaId').value = id;

            let form = document.getElementById('xoaCaForm');
            form.action = form.action.replace(':id', id);
        }
    </script>

{{-- Kiểm tra trùng ca làm bằng AJAX --}}
<script>
    document.getElementById("registerShiftForm").addEventListener("submit", function(event) {
        event.preventDefault(); // Ngăn chặn submit mặc định

        let nhanVienId = document.getElementById("employee").value;
        let caLamId = document.getElementById("shift").value;
        let ngayLam = document.getElementById("workDate").value;

        fetch("{{ route('ca-lam-nhan-vien.index') }}?nhan_vien_id=" + nhanVienId + "&ca_lam_id=" + caLamId + "&ngay_lam=" + ngayLam)
            .then(response => response.json())
            .then(data => {
                if (data.trung) {
                    alert("❌ Nhân viên đã đăng ký ca làm này rồi! Không thể đăng ký lại.");
                } else {
                    document.getElementById("registerShiftForm").submit(); // Submit form nếu không trùng
                }
            });
    });

    function setXoaCa(id) {
        document.getElementById('xoaCaId').value = id;
        let form = document.getElementById('xoaCaForm');
        form.action = form.action.replace(':id', id);
    }
</script>




@endsection