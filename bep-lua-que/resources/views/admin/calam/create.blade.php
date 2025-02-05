@extends('layouts.admin')

@section('title')
    Thêm mới ca làm
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Thêm mới ca làm</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)"> Thêm mới ca làm</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('ca-lam.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Tên danh mục -->
                            <div class="form-group">
                                <label for="name">Tên ca làm</label>
                                <input type="text" id="name" name="ten_ca" class="form-control"
                                    placeholder="Nhập tên ca làm" value="{{ old('ten_ca') }}">
                                @if ($errors->has('ten_ca'))
                                    <small class="text-danger">*{{ $errors->first('ten_ca') }}</small>
                                @endif
                            </div>

                            <div class="form-row d-flex">
                                <!-- Giờ bắt đầu -->
                                <div class="form-group col-md-4">
                                    <label for="time_start">Giờ bắt đầu</label>
                                    <select id="time_start" name="gio_bat_dau" class="form-control">
                                        <!-- Các giờ bắt đầu sẽ được tạo động trong script -->
                                    </select>
                                </div>

                                <!-- Giờ kết thúc -->
                                <div class="form-group col-md-4">
                                    <label for="time_end">Giờ kết thúc</label>
                                    <select id="time_end" name="gio_ket_thuc" class="form-control">
                                        <!-- Các giờ kết thúc sẽ được tạo động trong script -->
                                    </select>
                                </div>

                                <!-- Tổng giờ làm -->
                                <div class="form-group col-md-4">
                                    <label for="working_hours">Tổng giờ làm việc</label>
                                    <input type="text" id="working_hours" class="form-control" placeholder="0 giờ"
                                        readonly>
                                </div>
                            </div>


                            <!-- Mô tả -->
                            <div class="form-group">
                                <label for="description">Mô tả</label>
                                <textarea id="description" name="mo_ta" class="form-control" placeholder="Nhập mô tả"></textarea>
                                @if ($errors->has('mo_ta'))
                                    <small class="text-danger">*{{ $errors->first('mo_ta') }}</small>
                                @endif
                            </div>

                            <!-- Nút submit -->
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Thêm
                                    mới</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const timeStart = document.getElementById("time_start");
            const timeEnd = document.getElementById("time_end");
            const workingHours = document.getElementById("working_hours");

            // Hàm tạo danh sách các giờ với khoảng cách 15 phút
            function generateTimeOptions() {
                let options = '';
                for (let h = 0; h < 24; h++) {
                    for (let m = 0; m < 60; m += 15) {
                        let hours = h < 10 ? '0' + h : h;
                        let minutes = m < 10 ? '0' + m : m;
                        let time = `${hours}:${minutes}`;

                        // Thêm option vào select
                        options += `<option value="${time}">${time}</option>`;
                    }
                }

                // Chèn các giờ vào select
                timeStart.innerHTML = options;
                timeEnd.innerHTML = options;

                // Set giờ bắt đầu mặc định là 07:00
                timeStart.value = "07:00";
            }

            // Hàm tính tổng giờ làm việc
            function calculateWorkingHours() {
                if (!timeStart.value || !timeEnd.value) {
                    workingHours.value = "";
                    return;
                }

                let start = timeStart.value.split(":").map(Number); // Chuyển "HH:MM" thành [HH, MM]
                let end = timeEnd.value.split(":").map(Number);

                let startMinutes = start[0] * 60 + start[1]; // Đổi sang phút
                let endMinutes = end[0] * 60 + end[1];

                let diff = endMinutes - startMinutes;
                if (diff < 0) diff += 24 * 60; // Xử lý trường hợp ca làm qua đêm

                let hours = Math.floor(diff / 60);
                let minutes = diff % 60;

                workingHours.value = `${hours} giờ ${minutes} phút`;
            }

            // Gọi hàm để tạo các giờ cho dropdown
            generateTimeOptions();

            // Gán sự kiện khi chọn giờ bắt đầu/kết thúc
            timeStart.addEventListener("change", calculateWorkingHours);
            timeEnd.addEventListener("change", calculateWorkingHours);
        });
    </script>
@endsection
