@extends('layouts.admin')

@section('title')
    Quản lí lịch làm việc
@endsection

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Lịch làm việc</h2>

    <!-- Thanh tìm kiếm -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Ô tìm kiếm -->
        <div class="search-box">
            <input type="text" id="searchBar" class="form-control search-input" placeholder="Tìm kiếm nhân viên">
            <span class="search-icon">
                <i class="bi bi-search"></i>
            </span>
            <button class="dropdown-toggle-icon" id="toggleSearchDropdown">
                <i class="bi bi-caret-up-fill"></i>
            </button>

            <!-- Dropdown tìm kiếm -->
            <div class="search-dropdown card p-3 position-absolute shadow d-none" id="searchDropdown">
                <div class="mb-2">
                    <label class="form-label">Nhân viên</label>
                    <input type="text" class="form-control" placeholder="Chọn nhân viên">
                </div>
                <div class="mb-2">
                    <label class="form-label">Ca làm việc</label>
                    <select class="form-select">
                        <option value="">Chọn ca làm việc</option>
                        <option>Ca sáng</option>
                        <option>Ca chiều</option>
                        <option>Ca tối</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label class="form-label">Chi nhánh</label>
                    <input type="text" class="form-control mt-1" placeholder="Chọn chi nhánh">
                </div>
                <div class="mb-2">
                    <label class="form-label">Phòng ban</label>
                    <input type="text" class="form-control" placeholder="Chọn phòng ban">
                </div>
                <button class="btn btn-primary w-100 mt-2">🔍 Tìm kiếm</button>
            </div>
        </div>

        <div class="d-flex align-items-center">
            <!-- <button class="btn btn-outline-primary me-2" id="prevWeek">← Tuần trước</button> -->
            <input type="week" id="weekPicker" class="form-control w-auto">
            <!-- <button class="btn btn-outline-primary ms-2" id="nextWeek">Tuần sau →</button> -->
        </div>
        <button class="btn btn-outline-primary ms-2" id="load">Tuần này</button>
        <button class="btn btn-success">📂 Xuất file</button>
    </div>



    <table class="table table-bordered schedule-table">
        <thead class="table-light">
            <tr>
                <th>Ca làm việc</th>
                <th>Thứ 2</th>
                <th>Thứ 3</th>
                <th>Thứ 4</th>
                <th>Thứ 5</th>
                <th>Thứ 6</th>
                <th>Thứ 7</th>
                <th>Chủ nhật</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Ca sáng</strong> <br> 08:00 - 12:00</td>
                <td class="add-schedule" data-shift="Ca sáng"></td>
                <td class="add-schedule" data-shift="Ca sáng"></td>
                <td class="add-schedule" data-shift="Ca sáng"></td>
                <td class="add-schedule" data-shift="Ca sáng"></td>
                <td class="add-schedule" data-shift="Ca sáng"></td>
                <td class="add-schedule" data-shift="Ca sáng"></td>
                <td class="add-schedule" data-shift="Ca sáng"></td>
            </tr>
            <tr>
                <td><strong>Ca chiều</strong> <br> 13:00 - 17:00</td>
                <td class="add-schedule" data-shift="Ca chiều"></td>
                <td class="add-schedule" data-shift="Ca chiều"></td>
                <td class="add-schedule" data-shift="Ca chiều"></td>
                <td class="add-schedule" data-shift="Ca chiều"></td>
                <td class="add-schedule" data-shift="Ca chiều"></td>
                <td class="add-schedule" data-shift="Ca chiều"></td>
                <td class="add-schedule" data-shift="Ca chiều"></td>
            </tr>
            <tr>
                <td><strong>Ca tối</strong> <br> 18:00 - 22:00</td>
                <td class="add-schedule" data-shift="Ca tối"></td>
                <td class="add-schedule" data-shift="Ca tối"></td>
                <td class="add-schedule" data-shift="Ca tối"></td>
                <td class="add-schedule" data-shift="Ca tối"></td>
                <td class="add-schedule" data-shift="Ca tối"></td>
                <td class="add-schedule" data-shift="Ca tối"></td>
                <td class="add-schedule" data-shift="Ca tối"></td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Modal thêm lịch làm việc -->
<div class="modal fade" id="addScheduleModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Thêm lịch làm việc</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong id="shiftTitle"></strong></p>
                <p>Thứ <span id="selectedDay"></span></p>
                <hr>
                <label class="form-label">Chọn nhân viên</label>
                <input type="text" id="searchEmployee" class="form-control" placeholder="Tìm kiếm nhân viên">
                <div id="employeeList" class="mt-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="employee1">
                        <label class="form-check-label" for="employee1">Nhân viên 1</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="employee2">
                        <label class="form-check-label" for="employee2">Nhân viên 2</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="employee3">
                        <label class="form-check-label" for="employee3">Nhân viên 3</label>
                    </div>
                        <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="repeatWeekly">
                    <label class="form-check-label" for="repeatWeekly">Lặp lại hàng tuần</label>
                </div>
                    <!-- Phần mở rộng khi chọn lặp lại hàng tuần -->
                    <div id="repeatOptions" class="d-none mt-3">
                        <label class="form-label">Chọn ngày lặp lại:</label>
                        <div class="d-flex flex-wrap gap-2">
                            <button class="btn btn-outline-primary repeat-day" data-day="2">Thứ 2</button>
                            <button class="btn btn-primary repeat-day" data-day="3">Thứ 3</button>
                            <button class="btn btn-outline-primary repeat-day" data-day="4">Thứ 4</button>
                            <button class="btn btn-outline-primary repeat-day" data-day="5">Thứ 5</button>
                            <button class="btn btn-outline-primary repeat-day" data-day="6">Thứ 6</button>
                            <button class="btn btn-outline-primary repeat-day" data-day="7">Thứ 7</button>
                            <button class="btn btn-outline-primary repeat-day" data-day="8">Chủ nhật</button>
                            <a href="#" class="text-primary ms-2" id="selectAllDays">Chọn tất cả</a>
                        </div>

                        <div class="mt-3">
                            <label class="form-label">Kết thúc</label>
                            <input type="date" class="form-control" id="repeatEndDate">
                        </div>

                        <div class="mt-2 form-check">
                            <input class="form-check-input" type="checkbox" id="holidayWork">
                            <label class="form-check-label" for="holidayWork">Làm việc cả ngày lễ tết</label>
                        </div>
                    </div>
                </div>
                <hr>
            
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Xong</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll(".add-schedule").forEach((cell, index) => {
        let dayNames = ["Thứ 2", "Thứ 3", "Thứ 4", "Thứ 5", "Thứ 6", "Thứ 7", "Chủ nhật"];
        cell.innerHTML = "<span class='text-muted'></span>";
        cell.classList.add("text-center", "cursor-pointer");

        cell.addEventListener("click", function () {
            let dayIndex = index % 7;
            let shiftName = this.getAttribute("data-shift");
            document.getElementById("selectedDay").textContent = dayNames[dayIndex];
            document.getElementById("shiftTitle").textContent = shiftName;

            let modal = new bootstrap.Modal(document.getElementById("addScheduleModal"));
            modal.show();
        });
    });
    document.addEventListener("DOMContentLoaded", function () {
        let repeatWeeklyToggle = document.getElementById("repeatWeekly");
        let repeatOptions = document.getElementById("repeatOptions");
        let repeatDays = document.querySelectorAll(".repeat-day");
        let selectAllDays = document.getElementById("selectAllDays");

        // Hiển thị hoặc ẩn phần tùy chọn lặp lại
        repeatWeeklyToggle.addEventListener("change", function () {
            repeatOptions.classList.toggle("d-none", !this.checked);
        });

        // Chọn/bỏ chọn ngày làm việc lặp lại
        repeatDays.forEach(button => {
            button.addEventListener("click", function () {
                this.classList.toggle("active");
            });
        });

        // Chọn tất cả hoặc bỏ chọn tất cả các ngày
        selectAllDays.addEventListener("click", function (e) {
            e.preventDefault();
            let allSelected = [...repeatDays].every(btn => btn.classList.contains("active"));

            repeatDays.forEach(button => {
                button.classList.toggle("active", !allSelected);
            });

            this.textContent = allSelected ? "Chọn tất cả" : "Bỏ chọn tất cả";
        });
    });


</script>
<script>
    // Hiển thị dropdown tìm kiếm khi nhấn vào nút mũi tên
    document.getElementById("toggleSearchDropdown").addEventListener("click", function () {
        let dropdown = document.getElementById("searchDropdown");
        dropdown.classList.toggle("d-none");
    });

    // Ẩn dropdown khi click ra ngoài
    document.addEventListener("click", function (event) {
        let dropdown = document.getElementById("searchDropdown");
        let searchBar = document.getElementById("searchBar");
        let toggleButton = document.getElementById("toggleSearchDropdown");

        if (!dropdown.contains(event.target) && event.target !== searchBar && event.target !== toggleButton) {
            dropdown.classList.add("d-none");
        }
    });

    // Cập nhật ngày theo tuần
    document.getElementById("weekPicker").valueAsDate = new Date();
</script>
@endsection
