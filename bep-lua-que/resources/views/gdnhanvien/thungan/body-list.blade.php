{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" /> --}}
<link href="{{ asset('admin/css/swiper-bundle.min.css') }}" rel="stylesheet" />
<style>
    .table-dat-ban {
        font-size: 14px;
    }
</style>

<div class="swiper mySwiper">
    <div class="swiper-wrapper">
        @foreach ($data->chunk(18) as $chunk)
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
<div class="text-center mt-2">
    <span id="pageIndicator">1 / 1</span>
</div>

<!-- Nút điều hướng TÙY CHỈNH -->
<div class="text-centerr mt-3">
    <button id="prevBtn" class="btn btn-primary btn-sm px-4">⬅ </button>
    <button id="nextBtn" class="btn btn-primary btn-sm px-4"> ➡</button>
</div>
<script src="{{ asset('admin/js/listBanAn.js') }}"></script>
