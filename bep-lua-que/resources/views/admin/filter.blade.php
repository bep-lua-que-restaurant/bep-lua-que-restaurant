<div class="col-lg-12 my-4">
    <div class="d-flex justify-content-between align-items-center">
        <!-- Ô tìm kiếm + Nút tìm kiếm (Sát lề trái) -->
        <div class="input-group  ">
            <input type="text" id="{{ $searchInputId }}" class="form-control border-0" placeholder="Tìm kiếm ...">
        </div>

        <div>
            <select id="statusFilter" class="btn btn-primary btn-sm">
                <option value="">Lựa chọn hiển thị</option>
                <option value="Đang kinh doanh">Đang kinh doanh</option>
                <option value="Ngừng kinh doanh">Ngừng kinh doanh</option>
                <option value="Tất cả">Tất cả</option>
            </select>
        </div>
    </div>
</div>
