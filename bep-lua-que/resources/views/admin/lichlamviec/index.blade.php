@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Lịch làm việc</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
                <link rel="stylesheet" href="styles.css"> <!-- Link the CSS file here -->
            </head>

            <body>

                <div class="container mt-4">
                    <h2 class="mb-3">Lịch làm việc</h2>

                    <!-- Thanh tìm kiếm -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <!-- Ô tìm kiếm -->
                        <div class="search-box">
                            <input type="text" id="searchBar" class="form-control search-input"
                                placeholder="Tìm kiếm nhân viên">
                            <span class="search-icon">
                                <i class="bi bi-search"></i>
                            </span>

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





                <style>
                    .add-schedule {
                        cursor: pointer;
                        text-align: center;
                        color: gray;
                    }

                    .add-schedule:hover {
                        background-color: #f0f0f0;
                    }
                </style>

                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

            </body>
        @endsection
