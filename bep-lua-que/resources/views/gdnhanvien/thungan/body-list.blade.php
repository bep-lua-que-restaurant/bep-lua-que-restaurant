{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" /> --}}
<link href="{{ asset('admin/css/swiper-bundle.min.css') }}" rel="stylesheet" />
<div class="swiper mySwiper">
    <div class="swiper-wrapper">
        @foreach ($data->chunk(12) as $chunk)
            <div class="swiper-slide">
                <div class="row">
                    @foreach ($chunk as $banAn)
                        <div class="col-md-2 col-4 mb-2">
                            <div class="ban cardd card text-center p-1 position-relative" data-id="{{ $banAn->id }}"
                                id="ban-{{ $banAn->id }}" onclick="setActive(this)">

                                <div class="card-body p-2">
                                    <h5><i class="fas fa-utensils" style="font-size: 24px;"></i></h5>
                                    <h6 class="card-title" style="font-size: 12px;">{{ $banAn->ten_ban }}</h6>

                                    @if ($banAn->trang_thai == 'trong')
                                        <p class="badge badge-success custom-badge">Có sẵn</p>
                                    @elseif ($banAn->trang_thai == 'co_khach')
                                        <p class="badge badge-warning custom-badge">Có khách</p>
                                    @elseif ($banAn->trang_thai == 'da_dat_truoc')
                                        <p class="badge badge-success custom-badge">Có sẵn</p>
                                        <span data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Có đơn đặt trước cho bàn này"
                                            class="new-order-icon position-absolute top-0 end-0 p-1"
                                            data-id="{{ $banAn->id }}" onclick="showOrders(this)">
                                            <i class="fas fa-bell text-danger"></i>
                                        </span>
                                    @else
                                        <p class="badge badge-secondary custom-badge">Không xác định</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="ordersModal" tabindex="-1" aria-labelledby="ordersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Thay modal-lg bằng modal-xl nếu muốn to hơn nữa -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ordersModalLabel">Danh sách đơn đặt trước: <p id="ten-ban-an" ></p> </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tên khách</th>
                            <th>Thời gian đến</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody id="ordersList">
                        <tr><td colspan="4">Đang tải...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- Nút điều hướng TÙY CHỈNH -->
<div class="text-centerr mt-3">
    <button id="prevBtn" class="btn btn-primary btn-sm px-4">⬅ </button>
    <button id="nextBtn" class="btn btn-primary btn-sm px-4"> ➡</button>
</div>
<script src="{{ asset('admin/js/listBanAn.js') }}"></script>
