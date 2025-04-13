@extends('layouts.admin')

@section('title')
    Thêm mới mã giảm
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-md-6">
                <h4 class="fw-bold">Thêm mới mã giảm</h4>
            </div>
            <div class="col-md-6 text-end">
                <ol class="breadcrumb justify-content-end">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item active">Thêm mã giảm</li>
                </ol>
            </div>
        </div>

        <div class="card shadow rounded p-4">
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
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="code" class="form-label fw-semibold">Mã Giảm Giá</label>
                        <input type="text" class="form-control" id="code" name="code" value="{{ old('code') }}"
                            required>
                        <span class="text-danger" id="error-code"></span>
                    </div>

                    <div class="col-md-6">
                        <label for="type" class="form-label fw-semibold">Loại Giảm Giá</label>
                        <select name="type" id="type" class="form-select" required>
                            {{-- <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Phần trăm</option> --}}
                            <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Số tiền</option>
                        </select>
                        <span class="text-danger" id="error-type"></span>
                    </div>

                    <div class="col-md-6">
                        <label for="value" class="form-label fw-semibold">Giá Trị Giảm</label>
                        <input type="number" step="0.01" class="form-control" id="value" name="value"
                            value="{{ old('value') }}" required>
                        <span class="text-danger" id="error-value"></span>
                    </div>

                    <div class="col-md-6">
                        <label for="min_order_value" class="form-label fw-semibold">Đơn Hàng Tối Thiểu</label>
                        <input type="number" step="0.01" class="form-control" id="min_order_value"
                            name="min_order_value" value="{{ old('min_order_value') }}">
                        <span class="text-danger" id="error-min_order_value"></span>
                    </div>

                    <div class="col-md-6">
                        <label for="start_date" class="form-label fw-semibold">Ngày Bắt Đầu</label>
                        <input type="datetime-local" class="form-control" id="start_date" name="start_date"
                            value="{{ old('start_date') }}" required>
                        <span class="text-danger" id="error-start_date"></span>
                    </div>

                    <div class="col-md-6">
                        <label for="end_date" class="form-label fw-semibold">Ngày Kết Thúc</label>
                        <input type="datetime-local" class="form-control" id="end_date" name="end_date"
                            value="{{ old('end_date') }}" required>
                        <span class="text-danger" id="error-end_date"></span>
                    </div>

                    <div class="col-md-6">
                        <label for="usage_limit" class="form-label fw-semibold">Giới Hạn Sử Dụng (0 = không giới
                            hạn)</label>
                        <input type="number" class="form-control" id="usage_limit" name="usage_limit"
                            value="{{ old('usage_limit') }}">
                        <span class="text-danger" id="error-usage_limit"></span>
                    </div>

                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-success btn-lg px-4">
                            <i class="fas fa-plus-circle me-1"></i> Tạo Mã Giảm Giá
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('discountForm').addEventListener('submit', function(e) {
            let errors = {};

            // Reset lỗi
            document.querySelectorAll('.text-danger').forEach(el => el.innerText = '');
            document.querySelectorAll('.form-control').forEach(el => el.classList.remove('is-invalid'));

            // Lấy dữ liệu
            const code = document.getElementById('code').value.trim();
            const type = document.getElementById('type').value.trim();
            const value = document.getElementById('value').value.trim();
            const minOrderValue = document.getElementById('min_order_value').value.trim();
            const startDate = document.getElementById('start_date').value.trim();
            const endDate = document.getElementById('end_date').value.trim();
            const usageLimit = document.getElementById('usage_limit').value.trim();

            // --- Validate ---
            if (code === '') {
    errors['code'] = "Bạn không được phép bỏ trống trường này.";
} else if (code.length > 20) {
    errors['code'] = "Mã giảm giá tối đa 20 ký tự.";
}

if (type === '') {
    errors['type'] = "Bạn không được phép bỏ trống trường này.";
} else if (type !== 'percentage' && type !== 'fixed') {
    errors['type'] = "Loại giảm giá không hợp lệ.";
}

if (value === '') {
    errors['value'] = "Bạn không được phép bỏ trống trường này.";
} else if (isNaN(value) || parseFloat(value) <= 0) {
    errors['value'] = "Giá trị giảm phải là số lớn hơn 0.";
} else if (type === 'percentage' && parseFloat(value) > 100) {
    errors['value'] = "Với loại phần trăm, giá trị không được vượt quá 100%.";
}

if (startDate === '') {
    errors['start_date'] = "Bạn không được phép bỏ trống trường này.";
}

if (endDate === '') {
    errors['end_date'] = "Bạn không được phép bỏ trống trường này.";
}
            // Hiển thị lỗi
            Object.keys(errors).forEach(function(key) {
                const inputEl = document.getElementById(key);
                const errorEl = document.getElementById(`error-${key}`);

                if (errorEl) {
                    errorEl.innerText = errors[key];
                }

                if (inputEl) {
                    inputEl.classList.add('is-invalid'); // Thêm border đỏ nếu có lỗi
                }
            });

            // Nếu có lỗi → ngăn submit
            if (Object.keys(errors).length > 0) {
                e.preventDefault();
            }
        });
    </script>



@endsection
