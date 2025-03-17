<div class="deznav">
    <div class="deznav-scroll">
        <ul class="metismenu" id="menu">
            {{-- Thống kê --}}
            <li>
                <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="fas fa-chart-bar"></i>
                    <span class="nav-text">Thống kê</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('thongke.thongkedoanhso') }}">Thống kê doanh số</a></li>
                    <li><a href="{{ route('thongke.thongkehoadon') }}">Thống kê số lượng hóa đơn</a></li>
                    <li><a href="{{ route('thongke.topdoanhthu') }}">Thống kê top doanh thu</a></li>

                    <li><a href="{{ route('thongke.thongkesoluongkhach') }}">Thống kê số lượng khách hàng</a></li>
                    <li><a href="{{ route('thongke.thongkemonan') }}">Thống kê món ăn</a></li>
                    <li><a href="#">Thống kê chưa nghĩ ra</a></li>
                </ul>
            </li>
            {{-- Hàng hóa --}}
            <li>
                <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="fa fa-credit-card"></i>
                    <span class="nav-text">Hàng hóa</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('danh-muc-mon-an.index') }}">Danh mục</a></li>
                    <li><a href="{{ route('mon-an.index') }}">Món ăn</a></li>
                    <li><a href="{{ route('dich-vu.index') }}">Dịch vụ</a></li>
                    <li><a href="{{ route('com-bo.index') }}">Combo - đóng gói</a></li>
                </ul>
                <ul aria-expanded="false">
                    <li><a href="{{ route('ma-giam-gia.index') }}">Mã giảm giá</a></li>
                </ul>
            </li>

            {{-- Phòng bàn --}}
            <li>
                <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="fa fa-list"></i>
                    <span class="nav-text">Phòng\Bàn</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('phong-an.index') }}">Phòng Ăn</a></li>
                    <li><a href="{{ route('ban-an.index') }}">Bàn Ăn</a></li>
                </ul>
            </li>
            {{-- kho --}}
            <li>
                <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="fa fa-list"></i>
                    <span class="nav-text">Kho</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('loai-nguyen-lieu.index') }}">Loại nguyên liệu</a></li>

                    <li><a href="{{ route('nguyen-lieu.index') }}">Nguyên liệu</a></li>
                </ul>
            </li>
            {{-- Giao dịch --}}
            <li>
                <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="fa fa-credit-card"></i>
                    <span class="nav-text">Giao dịch</span>
                </a>

                <ul aria-expanded="false">
                    <li><a href="{{ route('hoa-don.index') }}">Hóa đơn</a></li>
                </ul>
                <ul aria-expanded="false">
                    <li><a href="#">Trả hàng</a></li>
                </ul>

                <ul aria-expanded="false">
                    <li><a href="{{ route('phieu-nhap-kho.index') }}">Nhập hàng</a></li>
                    {{-- <li><a href="#">Trả hàng nhập</a></li> --}}
                    {{-- <li><a href="#">Xuất hàng</a></li> --}}
                </ul>
            </li>

            {{-- Đối tác --}}
            <li>
                <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="fa fa-briefcase"></i>
                    <span class="nav-text">Đối tác</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="#">Khách hàng</a></li>
                    <li><a href="#">Tương tác</a></li>
                    <li><a href="{{ route('nha-cung-cap.index') }}">Nhà cung cấp</a></li>
                    <li><a href="#">Đối tác giao hàng</a></li>
                </ul>
            </li>

            {{-- Nhân viên --}}
            <li>
                <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="fa fa-users"></i>
                    <span class="nav-text">Nhân viên</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('nhan-vien.index') }}">Nhân viên</a></li>

                </ul>
                <ul aria-expanded="false">
                    <li><a href="{{ route('ca-lam.index') }}">Ca làm</a></li>
                </ul>
                <ul aria-expanded="false">
                    <li><a href="{{ route('ca-lam-nhan-vien.index') }}">Quản lí ca </a></li>
                </ul>
                <ul aria-expanded="false">

                    <li><a href="{{ route('cham-cong.index') }}">Chấm công</a></li>


                    <li><a href="{{ route('luong.index') }}">Bảng tính lương</a></li>
                    <li><a href="#">Thiết lập nhân viên</a></li>
                </ul>
            </li>

            {{-- Nhà bếp --}}
            <li>
                <a class="ai-icon" href="{{ route('bep.dashboard') }}">
                    <i class="fa fa-utensils"></i>
                    <span class="nav-text">Nhà bếp</span>
                </a>
            </li>

            {{-- Lễ tân --}}
            <li>
                <a class="ai-icon" href="{{ route('dat-ban.index') }}">
                    <i class="fa fa-concierge-bell"></i>
                    <span class="nav-text">Lễ tân</span>
                </a>
            </li>

            {{-- Thu ngân --}}
            <li>
                <a class="ai-icon" href="{{ route('thungan.getBanAn') }}">
                    <i class="fa fa-cash-register"></i>
                    <span class="nav-text">Thu ngân</span>
                </a>
            </li>
        </ul>
    </div>
</div>

<style>
    [data-primary="color_3"] .deznav .metismenu>li.mm-active>a i {
        font-weight: normal;
        /* Đảm bảo icon không bị quá mỏng */
        background: none !important;
        /* Xóa nền icon */
        color: black !important;
        /* Đổi màu icon thành đen (hoặc màu khác tùy ý) */
    }
</style>
