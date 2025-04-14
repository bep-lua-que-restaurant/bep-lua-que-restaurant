@extends('layouts.admin')


@section('title')
    Thêm mới mã giảm
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Thêm mới mã giảm </h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Thêm mới mã giảm </a></li>
                </ol>
            </div>
        </div>

        <div class="card-body">
            <!-- Hiển thị thông báo lỗi từ server -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="discountForm" action="{{ route('ma-giam-gia.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="code">Mã Giảm Giá</label>
                    <input type="text" class="form-control" id="code" name="code" value="{{ old('code') }}"
                        required>
                    <span class="text-danger" id="error-code"></span>
                </div>

                <div class="form-group">
                    <label for="type">Loại Giảm Giá</label>
                    <select name="type" id="type" class="form-control" required>
                        <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Phần trăm</option>
                        <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Số tiền</option>
                    </select>
                    <span class="text-danger" id="error-type"></span>
                </div>

                <div class="form-group">
                    <label for="value">Giá Trị Giảm</label>
                    <input type="number" step="0.01" class="form-control" id="value" name="value"
                        value="{{ old('value') }}" required>
                    <span class="text-danger" id="error-value"></span>
                </div>

                <div class="form-group">
                    <label for="min_order_value">Đơn Hàng Tối Thiểu</label>
                    <input type="number" step="0.01" class="form-control" id="min_order_value" name="min_order_value"
                        value="{{ old('min_order_value') }}">
                    <span class="text-danger" id="error-min_order_value"></span>
                </div>

                <div class="form-group">
                    <label for="start_date">Ngày Bắt Đầu</label>
                    <input type="datetime-local" class="form-control" id="start_date" name="start_date"
                        value="{{ old('start_date') }}" required>
                    <span class="text-danger" id="error-start_date"></span>
                </div>

                <div class="form-group">
                    <label for="end_date">Ngày Kết Thúc</label>
                    <input type="datetime-local" class="form-control" id="end_date" name="end_date"
                        value="{{ old('end_date') }}" required>
                    <span class="text-danger" id="error-end_date"></span>
                </div>

                <div class="form-group">
                    <label for="usage_limit">Giới Hạn Sử Dụng (0: Không giới hạn)</label>
                    <input type="number" class="form-control" id="usage_limit" name="usage_limit"
                        value="{{ old('usage_limit') }}">
                    <span class="text-danger" id="error-usage_limit"></span>
                </div>

                <button type="submit" class="btn btn-custom">Tạo Mã Giảm Giá</button>
            </form>
        </div>
    </div>
    </div>
    <!-- Script validate phía client -->
    <script>
        document.getElementById('discountForm').addEventListener('submit', function(e) {
            let errors = {};

            const code = document.getElementById('code').value.trim();
            const type = document.getElementById('type').value;
            const value = parseFloat(document.getElementById('value').value);
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const usageLimit = document.getElementById('usage_limit').value.trim();

            if (code === '') errors['code'] = "Mã giảm giá không được để trống.";
            if (type !== 'percentage' && type !== 'fixed') errors['type'] = "Loại giảm giá không hợp lệ.";
            if (isNaN(value) || value <= 0) errors['value'] = "Giá trị giảm phải là số lớn hơn 0.";
            if (startDate === '' || endDate === '') errors['start_date'] =
                "Cần nhập đầy đủ ngày bắt đầu và ngày kết thúc.";
            else if (new Date(startDate) >= new Date(endDate)) errors['start_date'] =
                "Ngày bắt đầu phải nhỏ hơn ngày kết thúc.";
            if (usageLimit !== '' && (isNaN(usageLimit) || parseInt(usageLimit) < 0)) errors['usage_limit'] =
                "Giới hạn sử dụng phải là số không âm.";

            Object.keys(errors).forEach(key => {
                document.getElementById(`error-${key}`).innerText = errors[key];
            });

            if (Object.keys(errors).length > 0) e.preventDefault();
        });

        document.getElementById('discountForm').addEventListener('submit', function(e) {
            let errors = {};

            // Lấy giá trị từ các trường nhập
            const code = document.getElementById('code').value.trim();
            const type = document.getElementById('type').value;
            const value = parseFloat(document.getElementById('value').value);
            const minOrderValue = document.getElementById('min_order_value').value.trim();
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const usageLimit = document.getElementById('usage_limit').value.trim();

            // Xóa thông báo lỗi cũ
            document.querySelectorAll('.text-danger').forEach(el => el.innerText = '');

            // ✅ Validate Mã Giảm Giá
            if (code === '') {
                errors['code'] = "Mã giảm giá không được để trống.";
            } else if (code.length > 20) {
                errors['code'] = "Mã giảm giá tối đa 20 ký tự.";
            }

            // ✅ Validate Loại Giảm Giá
            if (type !== 'percentage' && type !== 'fixed') {
                errors['type'] = "Loại giảm giá không hợp lệ.";
            }

            // ✅ Validate Giá Trị Giảm
            if (isNaN(value) || value <= 0) {
                errors['value'] = "Giá trị giảm phải là số lớn hơn 0.";
            }

            // ✅ Validate Đơn Hàng Tối Thiểu
            if (minOrderValue !== '' && (isNaN(minOrderValue) || parseFloat(minOrderValue) < 0)) {
                errors['min_order_value'] = "Đơn hàng tối thiểu phải là số không âm.";
            }

            // ✅ Validate Ngày Bắt Đầu & Ngày Kết Thúc
            if (startDate === '' || endDate === '') {
                errors['start_date'] = "Cần nhập đầy đủ ngày bắt đầu và ngày kết thúc.";
            } else if (new Date(startDate) >= new Date(endDate)) {
                errors['start_date'] = "Ngày bắt đầu phải nhỏ hơn ngày kết thúc.";
            }

            // ✅ Validate Giới Hạn Sử Dụng
            if (usageLimit !== '' && (isNaN(usageLimit) || parseInt(usageLimit) < 0)) {
                errors['usage_limit'] = "Giới hạn sử dụng phải là số không âm.";
            }

            // Hiển thị lỗi dưới mỗi trường
            Object.keys(errors).forEach(key => {
                document.getElementById(`error-${key}`).innerText = errors[key];
            });

            // Nếu có lỗi, chặn form submit
            if (Object.keys(errors).length > 0) {
                e.preventDefault();
            }
        });
    </script>
@endsection

 
 @section('title')
     Thêm mới mã giảm
 @endsection
 
 @section('content')
     <div class="container-fluid">
         <div class="row page-titles mx-0">
             <div class="col-sm-6 p-md-0">
                 <div class="welcome-text">
                     <h4>Thêm mới mã giảm </h4>
                 </div>
             </div>
             <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                 <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                     <li class="breadcrumb-item active"><a href="javascript:void(0)">Thêm mới mã giảm </a></li>
                 </ol>
             </div>
         </div>
 
         <div class="card-body">
             <!-- Hiển thị thông báo lỗi từ server -->
             @if ($errors->any())
                 <div class="alert alert-danger">
                     <ul class="mb-0">
                         @foreach ($errors->all() as $error)
                             <li>{{ $error }}</li>
                         @endforeach
                     </ul>
                 </div>
             @endif
 
             <form id="discountForm" action="{{ route('ma-giam-gia.store') }}" method="POST">
                 @csrf
 
                 <div class="form-group">
                     <label for="code">Mã Giảm Giá</label>
                     <input type="text" class="form-control" id="code" name="code" value="{{ old('code') }}"
                         required>
                     <span class="text-danger" id="error-code"></span>
                 </div>
 
                 <div class="form-group">
                     <label for="type">Loại Giảm Giá</label>
                     <select name="type" id="type" class="form-control" required>
                         <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Phần trăm</option>
                         <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Số tiền</option>
                     </select>
                     <span class="text-danger" id="error-type"></span>
                 </div>
 
                 <div class="form-group">
                     <label for="value">Giá Trị Giảm</label>
                     <input type="number" step="0.01" class="form-control" id="value" name="value"
                         value="{{ old('value') }}" required>
                     <span class="text-danger" id="error-value"></span>
                 </div>
 
                 <div class="form-group">
                     <label for="min_order_value">Đơn Hàng Tối Thiểu</label>
                     <input type="number" step="0.01" class="form-control" id="min_order_value" name="min_order_value"
                         value="{{ old('min_order_value') }}">
                     <span class="text-danger" id="error-min_order_value"></span>
                 </div>
 
                 <div class="form-group">
                     <label for="start_date">Ngày Bắt Đầu</label>
                     <input type="datetime-local" class="form-control" id="start_date" name="start_date"
                         value="{{ old('start_date') }}" required>
                     <span class="text-danger" id="error-start_date"></span>
                 </div>
 
                 <div class="form-group">
                     <label for="end_date">Ngày Kết Thúc</label>
                     <input type="datetime-local" class="form-control" id="end_date" name="end_date"
                         value="{{ old('end_date') }}" required>
                     <span class="text-danger" id="error-end_date"></span>
                 </div>
 
                 <div class="form-group">
                     <label for="usage_limit">Giới Hạn Sử Dụng (0: Không giới hạn)</label>
                     <input type="number" class="form-control" id="usage_limit" name="usage_limit"
                         value="{{ old('usage_limit') }}">
                     <span class="text-danger" id="error-usage_limit"></span>
                 </div>
 
                 <button type="submit" class="btn btn-custom">Tạo Mã Giảm Giá</button>
             </form>
         </div>
     </div>
     </div>
     <!-- Script validate phía client -->
     <script>
         document.getElementById('discountForm').addEventListener('submit', function(e) {
             let errors = {};
 
             const code = document.getElementById('code').value.trim();
             const type = document.getElementById('type').value;
             const value = parseFloat(document.getElementById('value').value);
             const startDate = document.getElementById('start_date').value;
             const endDate = document.getElementById('end_date').value;
             const usageLimit = document.getElementById('usage_limit').value.trim();
 
             if (code === '') errors['code'] = "Mã giảm giá không được để trống.";
             if (type !== 'percentage' && type !== 'fixed') errors['type'] = "Loại giảm giá không hợp lệ.";
             if (isNaN(value) || value <= 0) errors['value'] = "Giá trị giảm phải là số lớn hơn 0.";
             if (startDate === '' || endDate === '') errors['start_date'] =
                 "Cần nhập đầy đủ ngày bắt đầu và ngày kết thúc.";
             else if (new Date(startDate) >= new Date(endDate)) errors['start_date'] =
                 "Ngày bắt đầu phải nhỏ hơn ngày kết thúc.";
             if (usageLimit !== '' && (isNaN(usageLimit) || parseInt(usageLimit) < 0)) errors['usage_limit'] =
                 "Giới hạn sử dụng phải là số không âm.";
 
             Object.keys(errors).forEach(key => {
                 document.getElementById(`error-${key}`).innerText = errors[key];
             });
 
             if (Object.keys(errors).length > 0) e.preventDefault();
         });
 
         document.getElementById('discountForm').addEventListener('submit', function(e) {
             let errors = {};
 
             // Lấy giá trị từ các trường nhập
             const code = document.getElementById('code').value.trim();
             const type = document.getElementById('type').value;
             const value = parseFloat(document.getElementById('value').value);
             const minOrderValue = document.getElementById('min_order_value').value.trim();
             const startDate = document.getElementById('start_date').value;
             const endDate = document.getElementById('end_date').value;
             const usageLimit = document.getElementById('usage_limit').value.trim();
 
             // Xóa thông báo lỗi cũ
             document.querySelectorAll('.text-danger').forEach(el => el.innerText = '');
 
             // ✅ Validate Mã Giảm Giá
             if (code === '') {
                 errors['code'] = "Mã giảm giá không được để trống.";
             } else if (code.length > 20) {
                 errors['code'] = "Mã giảm giá tối đa 20 ký tự.";
             }
 
             // ✅ Validate Loại Giảm Giá
             if (type !== 'percentage' && type !== 'fixed') {
                 errors['type'] = "Loại giảm giá không hợp lệ.";
             }
 
             // ✅ Validate Giá Trị Giảm
             if (isNaN(value) || value <= 0) {
                 errors['value'] = "Giá trị giảm phải là số lớn hơn 0.";
             }
 
             // ✅ Validate Đơn Hàng Tối Thiểu
             if (minOrderValue !== '' && (isNaN(minOrderValue) || parseFloat(minOrderValue) < 0)) {
                 errors['min_order_value'] = "Đơn hàng tối thiểu phải là số không âm.";
             }
 
             // ✅ Validate Ngày Bắt Đầu & Ngày Kết Thúc
             if (startDate === '' || endDate === '') {
                 errors['start_date'] = "Cần nhập đầy đủ ngày bắt đầu và ngày kết thúc.";
             } else if (new Date(startDate) >= new Date(endDate)) {
                 errors['start_date'] = "Ngày bắt đầu phải nhỏ hơn ngày kết thúc.";
             }
 
             // ✅ Validate Giới Hạn Sử Dụng
             if (usageLimit !== '' && (isNaN(usageLimit) || parseInt(usageLimit) < 0)) {
                 errors['usage_limit'] = "Giới hạn sử dụng phải là số không âm.";
             }
 
             // Hiển thị lỗi dưới mỗi trường
             Object.keys(errors).forEach(key => {
                 document.getElementById(`error-${key}`).innerText = errors[key];
             });
 
             // Nếu có lỗi, chặn form submit
             if (Object.keys(errors).length > 0) {
                 e.preventDefault();
             }
         });
     </script>

 @endsection

 @endsection

