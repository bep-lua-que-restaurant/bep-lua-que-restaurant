<div class="deznav">
    <div class="deznav-scroll">
        <ul class="metismenu" id="menu">
            {{-- Hàng hóa --}}
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="fa fa-list"></i> <!-- Icon danh mục -->
                    <span class="nav-text">Hàng hóa</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('danh-muc-mon-an.index') }}">Danh mục</a></li>
                </ul>
                <ul aria-expanded="false">
                    <li><a href="{{ route('mon-an.index') }}">Món ăn</a></li>
                </ul>
                <ul aria-expanded="false">
                    <li><a href="{{ route('dich-vu.index') }}">Dịch vụ</a></li>
                </ul>
                <ul aria-expanded="false">
                    <li><a href="{{ route('com-bo.index') }}">Combo - đóng gói</a></li>
                </ul>
            </li>
            {{-- Hàng hóa --}}


            {{-- Phòng bàn --}}
            <li>
                <a class="ai-icon" href="{{ route('ban-an.index') }}">
                    <i class="fa fa-table"></i> <!-- Icon phòng bàn -->
                    <span class="nav-text">Phòng bàn</span>
                </a>
            </li>
            {{-- Phòng bàn --}}

            {{-- Giao dịch --}}
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
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

                    {{-- <li><a href="{{ route('phieu-nhap-kho.index') }}">Nhập hàng</a></li> --}}



                </ul>
                <ul aria-expanded="false">
                    <li><a href="#">Trả hàng nhập</a></li>
                </ul>
                <ul aria-expanded="false">
                    <li><a href="#">Xuất hàng</a></li>
                </ul>
            </li>
            {{-- Giao dịch --}}

            {{-- Đối tác --}}
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="fa fa-briefcase"></i>
                    <span class="nav-text">Đối tác</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="#">Khách hàng</a></li>
                </ul>
                <ul aria-expanded="false">
                    <li><a href="#">Tương tác</a></li>
                </ul>
                <ul aria-expanded="false">
                    <li><a href="{{ route('nha-cung-cap.index') }}">Nhà cung cấp</a></li>
                </ul>
                <ul aria-expanded="false">
                    <li><a href="#">Đối tác giao hàng</a></li>
                </ul>
            </li>
            {{-- Đối tác --}}

            {{-- Nhân viên --}}
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="fa fa-users"></i>
                    <span class="nav-text">Nhân viên</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('nhan-vien.index') }}">Nhân viên</a></li>
                </ul>
                <ul aria-expanded="false">
                    <li><a href="{{ route('ca-lam.index') }}">Lịch làm việc</a></li>
                </ul>
                <ul aria-expanded="false">
                    <li><a href="#">Chấm công</a></li>
                </ul>
                <ul aria-expanded="false">
                    <li><a href="#">Bảng tính công</a></li>
                </ul>
                <ul aria-expanded="false">
                    <li><a href="#">Thiết lập nhân viên</a></li>
                </ul>
            </li>
            {{-- Nhân viên --}}

            {{-- Nhà bếp --}}
            <li>
                <a class="ai-icon" href="#">
                    <i class="fa fa-utensils"></i>
                    <span class="nav-text">Nhà bếp</span>
                </a>
            </li>
            {{-- Nhà bếp --}}

            {{-- Lễ tân --}}
            <li>
                <a class="ai-icon" href="#">
                    <i class="fa fa-concierge-bell"></i>
                    <span class="nav-text">Lễ tân</span>
                </a>
            </li>
            {{-- Lễ tân --}}

            {{-- Thu ngân --}}
            <li>
                <a class="ai-icon" href="/thu-ngan">
                    <i class="fa fa-cash-register"></i>
                    <span class="nav-text">Thu ngân</span>
                </a>
            </li>
            {{-- Thu ngân --}}
        </ul>



    </div>
</div>
