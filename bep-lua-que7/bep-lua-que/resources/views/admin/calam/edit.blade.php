@extends('layouts.admin')

@section('title')
    Sửa ca làm
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4> Sửa ca làm</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)"> Sửa ca làm</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('ca-lam.update', $caLam) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <!-- Tên danh mục -->
                            <div class="form-group">
                                <label for="name">Tên ca </label>
                                <input type="text" id="name" name="ten_ca" class="form-control"
                                    value="{{ $caLam->ten_ca }}">
                                @if ($errors->has('ten_ca'))
                                    <small class="text-danger">*{{ $errors->first('ten_ca') }}</small>
                                @endif
                            </div>

                            <div class="form-row d-flex">
                                @php
                                    $gio_bat_dau = date('H:i', strtotime($caLam->gio_bat_dau));
                                    $gio_ket_thuc = date('H:i', strtotime($caLam->gio_ket_thuc));
                                @endphp

                                <!-- Giờ bắt đầu -->
                                <div class="form-group col-md-4">
                                    <label for="time_start">Giờ bắt đầu</label>
                                    <select id="time_start" name="gio_bat_dau" class="form-control">
                                        <option value="{{ $gio_bat_dau }}" selected>{{ $gio_bat_dau }}</option>
                                    </select>
                                </div>

                                <!-- Giờ kết thúc -->
                                <div class="form-group col-md-4">
                                    <label for="time_end">Giờ kết thúc</label>
                                    <select id="time_end" name="gio_ket_thuc" class="form-control">
                                        <option value="{{ $gio_ket_thuc }}" selected>{{ $gio_ket_thuc }}</option>
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
                                <textarea id="description" name="mo_ta" class="form-control" placeholder="Nhập mô tả">{{ $caLam->mo_ta }}</textarea>
                                @if ($errors->has('mo_ta'))
                                    <small class="text-danger">*{{ $errors->first('mo_ta') }}</small>
                                @endif
                            </div>

                            <!-- Nút submit -->
                            <div class="form-group text-right">
                                <a href="{{ route('ca-lam.index') }}" class="btn btn-primary btn-sm"> <i
                                        class="fa fa-arrow-left"></i> Quay lại
                                </a>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Lưu
                                </button>

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


            // Chuyển đổi định dạng 'HH:MM:SS' -> 'HH:MM' trong Laravel rồi truyền sang JS
            const savedStart = "{{ $gio_bat_dau }}";
            const savedEnd = "{{ $gio_ket_thuc }}";


            // Hàm tạo danh sách các giờ với khoảng cách 15 phút
            function generateTimeOptions(selectedValue) {
                let options = '';
                for (let h = 0; h < 24; h++) {
                    for (let m = 0; m < 60; m += 15) {
                        let hours = h < 10 ? '0' + h : h;
                        let minutes = m < 10 ? '0' + m : m;
                        let time = `${hours}:${minutes}`;

                        // Nếu trùng với giá trị từ database, thêm thuộc tính selected
                        let selected = time === selectedValue ? "selected" : "";
                        options += `<option value="${time}" ${selected}>${time}</option>`;
                    }
                }
                return options;
            }

            // Chèn giờ vào select
            timeStart.innerHTML = generateTimeOptions(savedStart);
            timeEnd.innerHTML = generateTimeOptions(savedEnd);

            // Hàm tính tổng giờ làm việc
            function calculateWorkingHours() {
                if (!timeStart.value || !timeEnd.value) {
                    workingHours.value = "";
                    return;
                }

                let start = timeStart.value.split(":").map(Number);
                let end = timeEnd.value.split(":").map(Number);

                let startMinutes = start[0] * 60 + start[1];
                let endMinutes = end[0] * 60 + end[1];

                let diff = endMinutes - startMinutes;
                if (diff < 0) diff += 24 * 60; // Xử lý ca làm qua đêm

                let hours = Math.floor(diff / 60);
                let minutes = diff % 60;

                workingHours.value = `${hours} giờ ${minutes} phút`;
            }

            // Tính tổng giờ làm ban đầu
            calculateWorkingHours();

            // Gán sự kiện khi chọn giờ bắt đầu/kết thúc
            timeStart.addEventListener("change", calculateWorkingHours);
            timeEnd.addEventListener("change", calculateWorkingHours);
        });
    </script>
@endsection
