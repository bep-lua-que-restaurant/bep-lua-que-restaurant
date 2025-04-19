@extends('layouts.admin')

@section('content')
    <div class="p-6">
        <div class="overflow-auto border rounded shadow">

            <form action="{{ route('cham-cong.store') }}" method="POST">
                @csrf
                <!-- Hidden input để xác định đang ở chế độ cập nhật -->
                <input type="hidden" name="is_edit_mode" id="is_edit_mode" value="0">
                <div class="card-header d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0" style="font-size: 1.5rem; margin-top: 10px; margin-left: 10px;">
                        <i class="fas fa-calendar-check me-2"></i> Hệ Thống Chấm Công
                    </h2>

                </div>
                <div class="d-flex align-items-center mb-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-calendar-alt text-primary"></i>
                        </span>
                        <input type="month" class="form-control border-start-0" id="monthFilter" style="width: auto;">
                    </div>
                    <button type="button" class="btn btn-light ms-2" onclick="filterByMonth()">
                        <i class="fas fa-filter"></i> Lọc
                    </button>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <button class="btn btn-outline-primary" type="button" onclick="prevMonth()">
                        <i class="fas fa-chevron-left me-1"></i> Tháng trước
                    </button>

                    <div class="month-title text-center" id="monthTitle" style="font-size: 1.25rem; font-weight: bold;">
                        <i class="fas fa-calendar-alt me-2"></i>
                    </div>

                    <button class="btn btn-outline-primary" type="button" onclick="nextMonth()">
                        Tháng sau <i class="fas fa-chevron-right ms-1"></i>
                    </button>
                </div>

                <div class="d-flex">
                    <button type="button" class="btn btn-info ms-auto" onclick="toggleQuickCheckin()">
                        <i class="fas fa-bolt"></i> Chấm công nhanh
                    </button>
                </div>


                {{-- Form chấm công nhanh --}}
                <div id="quickCheckinForm" class="border p-3 rounded shadow-sm mt-4 d-none bg-light">
                    <h5 class="mb-3 text-primary"><i class="fas fa-stopwatch me-2"></i> Chấm Công Nhanh</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="dateSelect" class="form-label">📅 Ngày chấm công</label>
                            <input type="date" id="dateSelect" name="ngay_cham_cong_nhanh" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label for="shiftSelect" class="form-label">🕐 Ca làm</label>
                            <select id="shiftSelect" name="ca_lam_id_nhanh" class="form-control">
                                <option value="">-- Chọn ca làm --</option>
                                @foreach ($caLams as $caLam)
                                    <option value="{{ $caLam->id }}">{{ $caLam->ten_ca }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">👥 Chọn nhân viên</label>
                        <input type="text" id="searchEmployees" class="form-control mb-3" placeholder="Tìm nhân viên...">

                        <div id="employeeList" class="border rounded p-3" style="max-height: 100px; overflow-y: auto;">
                            <div class="row g-3">
                                @foreach ($nhanViensAll as $nhanVien)
                                    <div class="col-4 d-flex align-items-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nhan_vien_ids_nhanh[]"
                                                value="{{ $nhanVien->id }}" id="nhanVien{{ $nhanVien->id }}">
                                            <label class="form-check-label" for="nhanVien{{ $nhanVien->id }}">
                                                {{ $nhanVien->ho_ten }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>


                </div>

                {{-- Include bảng chấm công --}}
                @include('admin.chamcong.listchamcong')
                <div class="mt-3">
                    {!! $nhanViens->links() !!}
                </div>
                <div class="d-flex gap-2 mt-3 ml-2">
                    <button type="button" id="btnEdit" class="btn btn-warning px-4 fw-bold shadow-sm">
                        Sửa
                    </button>
                    <button type="button" id="btnCancelEdit" class="btn btn-secondary d-none px-4 fw-bold shadow-sm">
                        Hủy
                    </button>
                    <button type="submit" class="btn btn-success px-4 fw-bold shadow-sm ">
                        Lưu
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- JS --}}
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/locale/vi.js"></script>
    <script>
        let currentMonth = moment();
        moment.locale('vi');
        let page = 1;

        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }


        function updateMonthTitle() {
            const monthTitle = capitalizeFirstLetter(currentMonth.format('MMMM')) + ' - ' + currentMonth.format('YYYY');
            $('#monthTitle').text(monthTitle);
            loadChamCongData(currentMonth.format('YYYY-MM'), page);
        }


        function loadChamCongData(month, pageNumber = 1) {
            page = pageNumber; // Cập nhật lại biến toàn cục luôn
            $.ajax({
                url: "{{ route('cham-cong.index') }}",
                method: 'GET',
                data: {
                    selected_month: month,
                    page: page
                },
                success: function(response) {
                    $('#chamcongTable').html($(response.html).find('#chamcongTable').html());
                    $('.pagination-links').html($(response.html).find('.pagination-links').html());
                    // Cập nhật lại danh sách nhân viên trong phần "Chấm Công Nhanh"
                    updateNhanVienList(response.nhanViens);
                    // Gọi lại updateMonthTitle để cập nhật tiêu đề tháng
                    updateMonthTitle();
                }
            });
        }



        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            if (!url) return;

            const params = new URLSearchParams(url.split('?')[1]);
            const pageNumber = params.get('page');

            if (pageNumber) {
                page = pageNumber;
                updateMonthTitle();
            }
        });


        function prevMonth() {
            currentMonth.subtract(1, 'month');
            page = 1;
            updateMonthTitle();
        }

        function nextMonth() {
            currentMonth.add(1, 'month');
            page = 1;
            updateMonthTitle();
        }

        function filterByMonth() {
            const selected = $('#monthFilter').val(); // yyyy-mm
            if (selected) {
                currentMonth = moment(selected, 'YYYY-MM'); // Cập nhật lại biến tháng hiện tại.
                page = 1;
                updateMonthTitle(); // Cập nhật tiêu đề + load dữ liệu
            }
        }
        // Tạo biến để lưu trạng thái ban đầu
        let originalCheckboxStates = {};

        document.getElementById('btnEdit').addEventListener('click', function() {
            document.getElementById('is_edit_mode').value = '1';

            const checkboxes = document.querySelectorAll('#chamcongTable input[type="checkbox"]');
            checkboxes.forEach((cb, index) => {
                cb.classList.add('checkbox-edit-mode');
                // Lưu trạng thái ban đầu theo id hoặc index
                originalCheckboxStates[cb.id || index] = cb.checked;
            });

            // Ẩn nút Sửa, hiện nút Hủy
            document.getElementById('btnEdit').classList.add('d-none');
            document.getElementById('btnCancelEdit').classList.remove('d-none');
        });

        document.getElementById('btnCancelEdit').addEventListener('click', function() {
            document.getElementById('is_edit_mode').value = '0';

            const checkboxes = document.querySelectorAll('#chamcongTable input[type="checkbox"]');
            checkboxes.forEach((cb, index) => {
                cb.classList.remove('checkbox-edit-mode');
                // Khôi phục trạng thái ban đầu
                const key = cb.id || index;
                if (originalCheckboxStates.hasOwnProperty(key)) {
                    cb.checked = originalCheckboxStates[key];
                }
            });

            // Ẩn nút Hủy, hiện nút Sửa
            document.getElementById('btnCancelEdit').classList.add('d-none');
            document.getElementById('btnEdit').classList.remove('d-none');
        });

        $(document).ready(function() {
            updateMonthTitle();
        });

        function toggleQuickCheckin() {
            const form = document.getElementById('quickCheckinForm');
            form.classList.toggle('d-none');
        }

        // Tìm kiếm nhân viên trong danh sách
        document.getElementById('searchEmployees').addEventListener('input', function() {
            const keyword = this.value.toLowerCase();
            document.querySelectorAll('#employeeList .form-check-label').forEach(label => {
                const container = label.closest('.col-4');
                const name = label.textContent.toLowerCase();
                container.style.display = name.includes(keyword) ? 'flex' : 'none';
            });
        });
    </script>
@endsection
<style>
    .checkbox-edit-mode {
        outline: 2px solid red !important;
        box-shadow: 0 0 4px red;
        border-radius: 4px;
    }
</style>
