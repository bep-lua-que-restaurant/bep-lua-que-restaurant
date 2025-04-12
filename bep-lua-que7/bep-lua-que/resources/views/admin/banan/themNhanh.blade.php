@extends('layouts.admin')

@section('title')
    Thêm nhanh bàn ăn
@endsection

@section('content')
<div class="container">
    <h2>Thêm nhanh bàn ăn</h2>
    <form action="{{ route('ban-an.store-quick') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="so_luong">Số lượng bàn:</label>
            <input type="number" class="form-control" id="so_luong" name="so_luong" min="1" required>
        </div>
        <div class="form-group">
            <label for="prefix">Cách đặt tên (tiền tố):</label>
            <input type="text" class="form-control" id="prefix" name="prefix" value="Bàn " placeholder="Ví dụ: Bàn " required>
            <small class="form-text text-muted">Tên bàn sẽ là: [Tiền tố] + Số thứ tự (ví dụ: Bàn 1, Bàn 2...)</small>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Thêm nhanh</button>
        <a href="{{ route('ban-an.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection