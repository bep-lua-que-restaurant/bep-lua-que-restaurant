<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from demo.egenslab.com/html/restho/preview/ by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 12 Jan 2025 11:29:22 GMT -->
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restho - Resturent HTML Template</title>

    <link rel="icon" href="{{ asset('client') }}/images/icon/logo-icon.svg" type="image/gif" sizes="20x20">


    <!-- css file link -->
    <link rel="stylesheet" href="{{ asset('client') }}/css/all.css">

    <!-- bootstrap 5 -->
    <link rel="stylesheet" href="{{ asset('client') }}/css/bootstrap.min.css">

    <!-- box-icon -->
    <link rel="stylesheet" href="{{ asset('client') }}/css/boxicons.min.css">

    <!-- bootstrap icon -->
    <link rel="stylesheet" href="{{ asset('client') }}/css/bootstrap-icons.css">

    <!-- jquery ui -->
    <link rel="stylesheet" href="{{ asset('client') }}/css/jquery-ui.css">

    <!-- swiper-slide -->
    <link rel="stylesheet" href="{{ asset('client') }}/css/swiper-bundle.css">

    <!-- nice-select -->
    <link rel="stylesheet" href="{{ asset('client') }}/css/nice-select.css">

    <!-- magnefic popup css -->
    <link rel="stylesheet" href="{{ asset('client') }}/css/magnific-popup.css">

    <!-- animate css -->
    <link rel="stylesheet" href="{{ asset('client') }}/css/jquery.fancybox.min.css">

    <!-- odometer css -->
    <link rel="stylesheet" href="{{ asset('client') }}/css/odometer.css">

    <!-- style css -->
    <link rel="stylesheet" href="{{ asset('client') }}/css/style.css">

</head>

<body class="tt-magic-cursor">
    <div class="preloader">
        <div class="counter">0</div>
    </div>

    <!-- ========== Top Bar Start============= -->
    <div class="top-bar">
        <div class="container-lg container-fluid ">
            <div class="row">
                <div class="col-lg-5 col-md-5 d-flex align-items-center justify-content-md-start justify-content-center">
                    <div class="open-time">
                        <p><span>Opening Hour:</span> 9.00 am to 10.00 pm</p>
                    </div>
                </div>
                <div class="col-lg-7 col-md-7 d-flex justify-content-end">
                    <div class="contact-info">
                        <ul>
                            <li><a href="https://demo.egenslab.com/cdn-cgi/l/email-protection#70191e161f301508111d001c155e131f1d"><i class="bi bi-envelope"></i> <span class="__cf_email__" data-cfemail="8be2e5ede4cbeef3eae6fbe7eea5e8e4e6">[email&#160;protected]</span></a></li>
                            <li><a><i class="bi bi-geo-alt"></i>Road-01, Block-B, West London City</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ========== Top Bar End============= -->

    <!-- ========== header============= -->
    <header class="header-area style-1 bg-color2">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="header-logo">
                <a href="index-2.html"><img alt="image" class="img-fluid" src="{{ asset('client') }}/images/header1-logo.svg"></a>
            </div>
            <div class="main-menu">
                <div class="mobile-logo-area d-lg-none d-flex justify-content-between align-items-center">
                    <div class="mobile-logo-wrap">
                        <a href="index-2.html"><img alt="image" src="{{ asset('client') }}/images/header1-logo.svg"></a>
                    </div>
                    <div class="menu-close-btn">
                        <i class="bi bi-x-lg text-white"></i>
                    </div>
                </div>
                <ul class="menu-list">
                    <li class="menu-item-has-children active">
                        <a href="#" class="drop-down">Home</a><i class="bi bi-plus dropdown-icon"></i>
                        <ul class="sub-menu">
                            <li><a class="active" href="index-2.html">Home One</a></li>
                            <li><a href="index2.html">Home Two</a></li>
                            <li><a href="index3.html">Home Three</a></li>
                        </ul>
                    </li>
                    <li><a href="about.html">About</a></li>
                    <li class="menu-item-has-children">
                        <a href="#">Menu</a><i class="bi bi-plus dropdown-icon"></i>
                        <ul class="sub-menu">
                            <li><a href="menu1.html">Menu List-01</a></li>
                            <li><a href="menu2.html">Menu List-02</a></li>
                            <li><a href="3col-menu.html">3 Columns Menu</a></li>
                        </ul>
                    </li>
                    <li class="menu-item-has-children">
                        <a href="#" class="drop-down">Pages</a><i class="bi bi-plus dropdown-icon"></i>
                        <ul class="sub-menu">
                            <li><a href="category.html">Food Category</a></li>
                            <li><a href="reservation.html">Reservation</a></li>
                            <li><a href="2col-gallery.html">Gallery</a><i class="d-lg-flex d-none bi bi-chevron-right dropdown-icon"></i>
                                <i class="d-lg-none d-flex bi bi-chevron-down dropdown-icon"></i>
                                <ul class="sub-menu">
                                    <li><a href="2col-gallery.html">2 Columns Gallery </a></li>
                                    <li><a href="3col-gallery.html">3 Columns Gallery </a></li>
                                    <li><a href="mesonary-gallery.html">Mesonary Gallery</a></li>
                                </ul>
                            </li>
                            <li><a href="chef-expertis.html">Chef</a><i class="d-lg-flex d-none bi bi-chevron-right dropdown-icon"></i>
                                <i class="d-lg-none d-flex bi bi-chevron-down dropdown-icon"></i>
                                <ul class="sub-menu">
                                    <li><a href="chef-expertis.html">Chef Experties</a></li>
                                    <li><a href="chef-details.html">Chef Details</a></li>
                                </ul>
                            </li>
                            <li><a href="shop.html">Shop</a><i class="d-lg-flex d-none bi bi-chevron-right dropdown-icon"></i>
                                <i class="d-lg-none d-flex bi bi-chevron-down dropdown-icon"></i>
                                <ul class="sub-menu">
                                    <li><a href="shop.html">Shop</a></li>
                                    <li><a href="shop-details.html">Shop Details</a></li>
                                    <li><a href="cart.html">Cart</a></li>
                                    <li><a href="check-out.html">Checkout</a></li>
                                </ul>
                            </li>
                            <li><a href="faq.html">Faq</a></li>
                            <li><a href="error.html">Error</a></li>
                        </ul>
                    </li>
                    <li class="menu-item-has-children">
                        <a href="#">Blog</a><i class="bi bi-plus dropdown-icon"></i>
                        <ul class="sub-menu">
                            <li><a href="blog-grid.html">Blog Grid</a></li>
                            <li><a href="blog-standard.html">Blog Standard</a></li>
                            <li><a href="blog-details.html">Blog Details</a></li>
                        </ul>
                    </li>
                    <li><a href="contact.html">Contact</a></li>
                </ul>
                <div class="hotline d-lg-none d-flex">
                    <div class="hotline-icon">
                        <svg width="26" height="26" viewBox="0 0 26 26" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20.5488 16.106C20.0165 15.5518 19.3745 15.2554 18.694 15.2554C18.0191 15.2554 17.3716 15.5463 16.8173 16.1005L15.0833 17.8291C14.9406 17.7522 14.7979 17.6809 14.6608 17.6096C14.4632 17.5108 14.2766 17.4175 14.1175 17.3187C12.4932 16.2871 11.0171 14.9426 9.6013 13.2031C8.91536 12.3361 8.45441 11.6063 8.11968 10.8655C8.56965 10.4539 8.9867 10.0259 9.39277 9.61431C9.54642 9.46066 9.70007 9.30152 9.85372 9.14787C11.0061 7.9955 11.0061 6.50291 9.85372 5.35054L8.35564 3.85246C8.18553 3.68234 8.00993 3.50674 7.8453 3.33115C7.51606 2.99092 7.17034 2.63972 6.81366 2.31047C6.28137 1.78368 5.64483 1.50381 4.97535 1.50381C4.30588 1.50381 3.65836 1.78368 3.10961 2.31047C3.10412 2.31596 3.10412 2.31596 3.09864 2.32145L1.23289 4.20365C0.530497 4.90605 0.129911 5.7621 0.0421114 6.75533C-0.089588 8.35768 0.382335 9.85027 0.744508 10.827C1.63348 13.2251 2.96145 15.4475 4.94243 17.8291C7.34594 20.699 10.2378 22.9653 13.5413 24.5622C14.8034 25.1603 16.4881 25.8682 18.3703 25.9889C18.4855 25.9944 18.6062 25.9999 18.716 25.9999C19.9836 25.9999 21.0482 25.5445 21.8823 24.639C21.8878 24.628 21.8987 24.6226 21.9042 24.6116C22.1896 24.2659 22.5188 23.9531 22.8645 23.6184C23.1005 23.3934 23.3419 23.1574 23.5779 22.9105C24.1212 22.3453 24.4065 21.6868 24.4065 21.0118C24.4065 20.3314 24.1157 19.6783 23.5614 19.1296L20.5488 16.106ZM22.5133 21.8843C22.5078 21.8843 22.5078 21.8898 22.5133 21.8843C22.2993 22.1148 22.0798 22.3233 21.8439 22.5538C21.4872 22.894 21.125 23.2507 20.7848 23.6513C20.2305 24.2439 19.5775 24.5238 18.7215 24.5238C18.6392 24.5238 18.5514 24.5238 18.4691 24.5183C16.8393 24.414 15.3247 23.7775 14.1888 23.2342C11.0829 21.7307 8.35564 19.596 6.08931 16.8907C4.21808 14.6354 2.96694 12.5501 2.13833 10.3112C1.62799 8.94484 1.44142 7.88026 1.52373 6.87606C1.57861 6.23402 1.82554 5.70174 2.281 5.24628L4.15223 3.37504C4.42112 3.12262 4.70647 2.98543 4.98633 2.98543C5.33204 2.98543 5.6119 3.19396 5.7875 3.36956C5.79299 3.37504 5.79847 3.38053 5.80396 3.38602C6.1387 3.69881 6.45697 4.02257 6.79171 4.36828C6.96182 4.54388 7.13742 4.71948 7.31302 4.90056L8.8111 6.39865C9.39277 6.98032 9.39277 7.51809 8.8111 8.09976C8.65196 8.2589 8.49831 8.41804 8.33918 8.57169C7.87823 9.04361 7.43923 9.48261 6.96182 9.91063C6.95085 9.92161 6.93987 9.92709 6.93438 9.93807C6.46246 10.41 6.55026 10.8709 6.64903 11.1837C6.65452 11.2002 6.66001 11.2167 6.6655 11.2331C7.05511 12.177 7.60385 13.0659 8.43795 14.125L8.44344 14.1305C9.95798 15.9962 11.5548 17.4504 13.3163 18.5644C13.5413 18.7071 13.7718 18.8223 13.9913 18.932C14.1888 19.0308 14.3754 19.1241 14.5345 19.2229C14.5565 19.2339 14.5784 19.2503 14.6004 19.2613C14.787 19.3546 14.9626 19.3985 15.1436 19.3985C15.5991 19.3985 15.8845 19.1131 15.9777 19.0198L17.8545 17.1431C18.041 16.9566 18.3374 16.7316 18.6831 16.7316C19.0233 16.7316 19.3032 16.9456 19.4733 17.1322C19.4788 17.1376 19.4788 17.1376 19.4842 17.1431L22.5078 20.1667C23.0731 20.7265 23.0731 21.3026 22.5133 21.8843Z"></path>
                            <path d="M14.0512 6.18495C15.4889 6.4264 16.7949 7.10685 17.8375 8.14947C18.8802 9.19209 19.5551 10.4981 19.8021 11.9358C19.8624 12.298 20.1752 12.5504 20.5319 12.5504C20.5758 12.5504 20.6142 12.5449 20.6581 12.5395C21.0642 12.4736 21.3331 12.0895 21.2672 11.6834C20.9709 9.94387 20.1478 8.35799 18.8911 7.10136C17.6345 5.84473 16.0486 5.0216 14.3091 4.72528C13.903 4.65943 13.5244 4.92832 13.4531 5.3289C13.3817 5.72949 13.6451 6.1191 14.0512 6.18495Z"></path>
                            <path d="M25.9707 11.4691C25.4823 8.60468 24.1324 5.99813 22.0581 3.92387C19.9838 1.8496 17.3773 0.49968 14.5128 0.011294C14.1122 -0.0600432 13.7336 0.214331 13.6623 0.614917C13.5964 1.02099 13.8653 1.39963 14.2714 1.47096C16.8285 1.90447 19.1607 3.11721 21.0155 4.96649C22.8702 6.82125 24.0775 9.15343 24.511 11.7106C24.5714 12.0728 24.8841 12.3252 25.2408 12.3252C25.2847 12.3252 25.3231 12.3197 25.367 12.3142C25.7676 12.2539 26.042 11.8697 25.9707 11.4691Z"></path>
                        </svg>
                    </div>
                    <div class="hotline-info">
                        <span>Call Now</span>
                        <h6><a href="tel:+998-8776345">+998-8776345</a></h6>
                    </div>
                </div>
            </div>

            <div class="nav-right d-flex jsutify-content-end align-items-center">
                <div class="hotline d-xxl-flex d-none">
                    <div class="hotline-icon">
                        <svg width="26" height="26" viewBox="0 0 26 26" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20.5488 16.106C20.0165 15.5518 19.3745 15.2554 18.694 15.2554C18.0191 15.2554 17.3716 15.5463 16.8173 16.1005L15.0833 17.8291C14.9406 17.7522 14.7979 17.6809 14.6608 17.6096C14.4632 17.5108 14.2766 17.4175 14.1175 17.3187C12.4932 16.2871 11.0171 14.9426 9.6013 13.2031C8.91536 12.3361 8.45441 11.6063 8.11968 10.8655C8.56965 10.4539 8.9867 10.0259 9.39277 9.61431C9.54642 9.46066 9.70007 9.30152 9.85372 9.14787C11.0061 7.9955 11.0061 6.50291 9.85372 5.35054L8.35564 3.85246C8.18553 3.68234 8.00993 3.50674 7.8453 3.33115C7.51606 2.99092 7.17034 2.63972 6.81366 2.31047C6.28137 1.78368 5.64483 1.50381 4.97535 1.50381C4.30588 1.50381 3.65836 1.78368 3.10961 2.31047C3.10412 2.31596 3.10412 2.31596 3.09864 2.32145L1.23289 4.20365C0.530497 4.90605 0.129911 5.7621 0.0421114 6.75533C-0.089588 8.35768 0.382335 9.85027 0.744508 10.827C1.63348 13.2251 2.96145 15.4475 4.94243 17.8291C7.34594 20.699 10.2378 22.9653 13.5413 24.5622C14.8034 25.1603 16.4881 25.8682 18.3703 25.9889C18.4855 25.9944 18.6062 25.9999 18.716 25.9999C19.9836 25.9999 21.0482 25.5445 21.8823 24.639C21.8878 24.628 21.8987 24.6226 21.9042 24.6116C22.1896 24.2659 22.5188 23.9531 22.8645 23.6184C23.1005 23.3934 23.3419 23.1574 23.5779 22.9105C24.1212 22.3453 24.4065 21.6868 24.4065 21.0118C24.4065 20.3314 24.1157 19.6783 23.5614 19.1296L20.5488 16.106ZM22.5133 21.8843C22.5078 21.8843 22.5078 21.8898 22.5133 21.8843C22.2993 22.1148 22.0798 22.3233 21.8439 22.5538C21.4872 22.894 21.125 23.2507 20.7848 23.6513C20.2305 24.2439 19.5775 24.5238 18.7215 24.5238C18.6392 24.5238 18.5514 24.5238 18.4691 24.5183C16.8393 24.414 15.3247 23.7775 14.1888 23.2342C11.0829 21.7307 8.35564 19.596 6.08931 16.8907C4.21808 14.6354 2.96694 12.5501 2.13833 10.3112C1.62799 8.94484 1.44142 7.88026 1.52373 6.87606C1.57861 6.23402 1.82554 5.70174 2.281 5.24628L4.15223 3.37504C4.42112 3.12262 4.70647 2.98543 4.98633 2.98543C5.33204 2.98543 5.6119 3.19396 5.7875 3.36956C5.79299 3.37504 5.79847 3.38053 5.80396 3.38602C6.1387 3.69881 6.45697 4.02257 6.79171 4.36828C6.96182 4.54388 7.13742 4.71948 7.31302 4.90056L8.8111 6.39865C9.39277 6.98032 9.39277 7.51809 8.8111 8.09976C8.65196 8.2589 8.49831 8.41804 8.33918 8.57169C7.87823 9.04361 7.43923 9.48261 6.96182 9.91063C6.95085 9.92161 6.93987 9.92709 6.93438 9.93807C6.46246 10.41 6.55026 10.8709 6.64903 11.1837C6.65452 11.2002 6.66001 11.2167 6.6655 11.2331C7.05511 12.177 7.60385 13.0659 8.43795 14.125L8.44344 14.1305C9.95798 15.9962 11.5548 17.4504 13.3163 18.5644C13.5413 18.7071 13.7718 18.8223 13.9913 18.932C14.1888 19.0308 14.3754 19.1241 14.5345 19.2229C14.5565 19.2339 14.5784 19.2503 14.6004 19.2613C14.787 19.3546 14.9626 19.3985 15.1436 19.3985C15.5991 19.3985 15.8845 19.1131 15.9777 19.0198L17.8545 17.1431C18.041 16.9566 18.3374 16.7316 18.6831 16.7316C19.0233 16.7316 19.3032 16.9456 19.4733 17.1322C19.4788 17.1376 19.4788 17.1376 19.4842 17.1431L22.5078 20.1667C23.0731 20.7265 23.0731 21.3026 22.5133 21.8843Z"></path>
                            <path d="M14.0512 6.18495C15.4889 6.4264 16.7949 7.10685 17.8375 8.14947C18.8802 9.19209 19.5551 10.4981 19.8021 11.9358C19.8624 12.298 20.1752 12.5504 20.5319 12.5504C20.5758 12.5504 20.6142 12.5449 20.6581 12.5395C21.0642 12.4736 21.3331 12.0895 21.2672 11.6834C20.9709 9.94387 20.1478 8.35799 18.8911 7.10136C17.6345 5.84473 16.0486 5.0216 14.3091 4.72528C13.903 4.65943 13.5244 4.92832 13.4531 5.3289C13.3817 5.72949 13.6451 6.1191 14.0512 6.18495Z"></path>
                            <path d="M25.9707 11.4691C25.4823 8.60468 24.1324 5.99813 22.0581 3.92387C19.9838 1.8496 17.3773 0.49968 14.5128 0.011294C14.1122 -0.0600432 13.7336 0.214331 13.6623 0.614917C13.5964 1.02099 13.8653 1.39963 14.2714 1.47096C16.8285 1.90447 19.1607 3.11721 21.0155 4.96649C22.8702 6.82125 24.0775 9.15343 24.511 11.7106C24.5714 12.0728 24.8841 12.3252 25.2408 12.3252C25.2847 12.3252 25.3231 12.3197 25.367 12.3142C25.7676 12.2539 26.042 11.8697 25.9707 11.4691Z"></path>
                        </svg>
                    </div>
                    <div class="hotline-info">
                        <span>Call Now</span>
                        <h6><a href="tel:+998-8776345">+998-8776345</a></h6>
                    </div>
                </div>
                <a href="contact.html" class="primary-btn btn-md">Connect Now</a>
                
                <div class="sidebar-button mobile-menu-btn ">
                    <i class="bi bi-list"></i>
                </div>
            </div>
        </div>
    </header>
    <!-- ========== header end============= -->

    <!-- ========== banner start============= -->
    <div class="banner-section1">
        <div class="banner-vector">
            <img class="vector-top" src="{{ asset('client') }}/images/icon/shape2.svg" alt="">
            <img class="vector-btm" src="{{ asset('client') }}/images/icon/shape1.svg" alt="">
        </div>
        <div class="swiper banner1-slider">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="banner-wrapper d-flex align-items-center justify-content-between">

                        <div class="social-area">
                            <ul class="m-0 p-0 d-flex align-items-center">
                                <li><a href="https://www.facebook.com/">Facebook</a></li>
                                <li><a href="https://twitter.com/">Twitter</a></li>
                                <li><a href="https://www.instagram.com/">Instagram</a></li>
                                <li><a href="https://www.skype.com/">Skype</a></li>
                            </ul>
                        </div>
                        <div class="banner-left-img">
                            <img src="{{ asset('client') }}/images/icon/union-left.svg" alt="union-left">
                            <div class="food-img">
                                <img class="img-fluid" src="{{ asset('client') }}/images/bg/banner-img-1.png" alt="banner-img-1">
                            </div>
                        </div>
                        <div class="banner-content">
                            <span><img class="left-vec"  src="{{ asset('client') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec">Welcome To Restho<img class="right-vec" src="{{ asset('client') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec"></span>
                            <h1>Find Your Best 
                                Healthy & Tasty Food.</h1>
                            <p>It is a long established fact that a reader will be distracted by the readable content of a page.</p>
                            <a class="primary-btn2" href="about.html"><i class="bi bi-arrow-up-right-circle"></i>Discover More</a>
                        </div>
                        <div class="banner-right-img">
                            <img src="{{ asset('client') }}/images/icon/union-right.svg" alt="union-right">
                            <div class="food-img">
                                <img class="img-fluid" src="{{ asset('client') }}/images/bg/banner-img-2.png" alt="banner-img-2">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="banner-wrapper d-flex align-items-center justify-content-between">
                       
                        <div class="social-area">
                            <ul class="m-0 p-0 d-flex align-items-center">
                                <li><a href="https://www.facebook.com/">Facebook</a></li>
                                <li><a href="https://twitter.com/">Twitter</a></li>
                                <li><a href="https://www.instagram.com/">Instagram</a></li>
                                <li><a href="https://www.skype.com/">Skype</a></li>
                            </ul>
                        </div>
                        <div class="banner-left-img">
                            <img src="{{ asset('client') }}/images/icon/union-left.svg" alt="union-left">
                            <div class="food-img">
                                <img class="img-fluid" src="{{ asset('client') }}/images/bg/banner-img-3.png" alt="banner-img-3">
                            </div>
                        </div>
                        <div class="banner-content">
                            <span><img class="left-vec"  src="{{ asset('client') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec">Welcome To Restho<img class="right-vec" src="{{ asset('client') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec"></span>
                            <h1>Find Your Best 
                                Healthy & Tasty Food.</h1>
                            <p>It is a long established fact that a reader will be distracted by the readable content of a page.</p>
                            <a class="primary-btn2" href="about.html"><i class="bi bi-arrow-up-right-circle"></i>Discover More</a>
                        </div>
                        <div class="banner-right-img">
                            <img src="{{ asset('client') }}/images/icon/union-right.svg" alt="union-right">
                            <div class="food-img">
                                <img class="img-fluid" src="{{ asset('client') }}/images/bg/banner-img-4.png" alt="banner-img-4">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="banner-wrapper d-flex align-items-center justify-content-between">
                        <div class="social-area">
                            <ul class="m-0 p-0 d-flex align-items-center">
                                <li><a href="https://www.facebook.com/">Facebook</a></li>
                                <li><a href="https://twitter.com/">Twitter</a></li>
                                <li><a href="https://www.instagram.com/">Instagram</a></li>
                                <li><a href="https://www.skype.com/">Skype</a></li>
                            </ul>
                        </div>
                        <div class="banner-left-img">
                            <img src="{{ asset('client') }}/images/icon/union-left.svg" alt="union-left">
                            <div class="food-img">
                                <img class="img-fluid" src="{{ asset('client') }}/images/bg/banner-img-5.png" alt="banner-img-5">
                            </div>
                        </div>
                        <div class="banner-content">
                            <span><img class="left-vec"  src="{{ asset('client') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec">Welcome To Restho<img class="right-vec" src="{{ asset('client') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec"></span>
                            <h1>Find Your Best 
                                Healthy & Tasty Food.</h1>
                            <p>It is a long established fact that a reader will be distracted by the readable content of a page.</p>
                            <a class="primary-btn2" href="about.html"><i class="bi bi-arrow-up-right-circle"></i>Discover More</a>
                        </div>
                        <div class="banner-right-img">
                            <img src="{{ asset('client') }}/images/icon/union-right.svg" alt="union-right">
                            <div class="food-img">
                                <img class="img-fluid" src="{{ asset('client') }}/images/bg/banner-img-6.png" alt="banner-img-5">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-btn d-flex justify-content-between align-items-center">
                <div class="prev-btn-1"><i class="bi bi-chevron-left"></i></div>
                <div class="next-btn-1"><i class="bi bi-chevron-right"></i></div>
            </div>
        </div>

    </div>
    <!-- ========== banner end============= -->

    <!-- ========== Home One About Start============= -->
    <div class="home1-introduction-area pt-120 mb-120">
        <div class="container-lg container-fluid">
            <div class="row mb-40">
                <div class="col-lg-12">
                    <div class="section-title">
                        <span><img class="left-vec"  src="{{ asset('client') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec">Introduction of Restho<img class="right-vec" src="{{ asset('client') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec"></span>
                        <h2>We Are Experienced Restaurant.</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row gy-5">
                <div class="col-lg-4">
                    <div class="into-left-img magnetic-wrap">
                        <img class="img-fluid magnetic-item" src="{{ asset('client') }}/images/bg/h1-intro-left-img.png" alt="h1-intro-left-img">
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="our-mission">
                        <div class="icon">
                            <img src="{{ asset('client') }}/images/icon/mission.svg" alt="">
                            <h4>Our Mission</h4>
                        </div>
                        <div class="description">
                            <p>It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                        </div>
                    </div>
                    <div class="intro-right">
                        <div class="features-author">
                            <div class="intro-features">
                                <ul>
                                    <li><i class="bi bi-check-circle"></i>Delicious Food.</li>
                                    <li><i class="bi bi-check-circle"></i>Cost Effective.</li>
                                    <li><i class="bi bi-check-circle"></i>Clean Environment.</li>
                                </ul>
                                <ul>
                                    <li><i class="bi bi-check-circle"></i>Expert Chef.</li>
                                    <li><i class="bi bi-check-circle"></i>Letraset Sheets.</li>
                                    <li><i class="bi bi-check-circle"></i>Quality Food. </li>
                                </ul>
                            </div>
                            <div class="author-area">
                                <div class="author-content">
                                    <p>“Welcome our restaurant! Our Restaurant is the best as like delicious food, nutrition food etc in world-wide.” </p>
                                </div>
                                <div class="author-img-name">
                                    <div class="author-img">
                                        <img src="{{ asset('client') }}/images/bg/h1-intro-author.png" alt="">
                                    </div>
                                    <div class="author-name">
                                        <h4>Mr. Hamilton</h4>
                                        <span>CEO &Founder</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="intro-right-img magnetic-wrap">
                            <img class="img-fluid magnetic-item" src="{{ asset('client') }}/images/bg/h1-intro-right-img.png" alt="h1-intro-right-img">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ========== Home One About end============= -->

    <!-- ========== New Items Start============= -->
    <div class="new-items1 mb-120">
        <div class="container">
            <div class="row d-flex justify-content-center mb-40">
                <div class="col-lg-8">
                    <div class="section-title text-center">
                        <span><img class="left-vec"  src="{{ asset('client') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec">Our New Item<img class="right-vec" src="{{ asset('client') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec"></span>
                        <h2>Restho New Item List</h2>
                    </div>
                </div>
            </div>
            <div class="row mb-70 g-4 justify-content-center">
                <div class="col-lg-4 col-md-6 col-sm-10">
                    <div class="new-items-wrap1 d-flex align-items-center justify-content-center">
                        <div class="items-content text-center">
                            <span>Spcial Offer</span>
                            <h3><a href="shop-details.html">Our New Item has offer</a></h3>
                            <div class="descount-area text-center">
                                <h3>After Discount</h3>
                                <span>20%</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-10 order-lg-2 order-3">
                    <div class="swiper new-item-big-slider">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="new-items-wrap2">
                                    <div class="items-img">
                                        <img class="img-fluid" src="{{ asset('client') }}/images/bg/new-items1.png" alt="new-items1">
                                        <div class="price">
                                            <span>Price - $8</span>
                                        </div>
                                    </div>
                                    <div class="content">
                                        <h3><a href="shop-details.html">Chicken Fried</a></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="new-items-wrap2">
                                    <div class="items-img">
                                        <img class="img-fluid" src="{{ asset('client') }}/images/bg/new-items2.png" alt="new-items1">
                                        <div class="price">
                                            <span>Price - $8</span>
                                        </div>
                                    </div>
                                    <div class="content">
                                        <h3><a href="shop-details.html">Vagitable Fried</a></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="new-items-wrap2">
                                    <div class="items-img">
                                        <img class="img-fluid" src="{{ asset('client') }}/images/bg/new-items3.png" alt="new-items1">
                                        <div class="price">
                                            <span>Price - $8</span>
                                        </div>
                                    </div>
                                    <div class="content">
                                        <h3><a href="shop-details.html">Prawn Curry</a></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="new-items-wrap2">
                                    <div class="items-img">
                                        <img class="img-fluid" src="{{ asset('client') }}/images/bg/new-items4.png" alt="new-items1">
                                        <div class="price">
                                            <span>Price - $8</span>
                                        </div>
                                    </div>
                                    <div class="content">
                                        <h3><a href="shop-details.html">Chicken Kebab</a></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="new-items-wrap2">
                                    <div class="items-img">
                                        <img class="img-fluid" src="{{ asset('client') }}/images/bg/new-items5.png" alt="new-items1">
                                        <div class="price">
                                            <span>Price - $8</span>
                                        </div>
                                    </div>
                                    <div class="content">
                                        <h3><a href="shop-details.html">Full Chicken</a></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="new-items-wrap2">
                                    <div class="items-img">
                                        <img class="img-fluid" src="{{ asset('client') }}/images/bg/new-items6.png" alt="new-items1">
                                        <div class="price">
                                            <span>Price - $8</span>
                                        </div>
                                    </div>
                                    <div class="content">
                                        <h3><a href="shop-details.html">Momo Package</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-10 order-lg-3 order-2">
                    <div class="new-items-wrap3">
                        <div class="items-img">
                            <img class="img-fluid" src="{{ asset('client') }}/images/bg/reserve1.png" alt="reserve1">
                        </div>
                        <div class="overlay d-flex align-items-center justify-content-center">
                            <div class="items-content text-center">
                                <span><img class="left-vec"  src="{{ asset('client') }}/images/icon/shape-white1.svg" alt="sub-title-vec">Reserve<img class="right-vec" src="{{ asset('client') }}/images/icon/shape-white1.svg" alt="sub-title-vec"></span>
                                <h3><a href="reservation.html">For Your Private Event</a></h3>
                                <a class="primary-btn btn-sm" href="reservation.html">Book Table</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row position-relative">
                <div class="swiper new-item-sm-slider">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="new-items-sm-img">
                                <img src="{{ asset('client') }}/images/bg/new-item-sm1.png" alt="new-item-sm1">
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="new-items-sm-img">
                                <img src="{{ asset('client') }}/images/bg/new-item-sm2.png" alt="new-item-sm2">
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="new-items-sm-img">
                                <img src="{{ asset('client') }}/images/bg/new-item-sm3.png" alt="new-item-sm3">
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="new-items-sm-img">
                                <img src="{{ asset('client') }}/images/bg/new-item-sm4.png" alt="new-item-sm4">
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="new-items-sm-img">
                                <img src="{{ asset('client') }}/images/bg/new-item-sm5.png" alt="new-item-sm5">
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="new-items-sm-img">
                                <img src="{{ asset('client') }}/images/bg/new-item-sm6.png" alt="new-item-sm6">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-btn d-flex justify-content-between align-items-center">
                    <div class="prev-btn-2"><i class="bi bi-arrow-left"></i></div>
                    <div class="next-btn-2"><i class="bi bi-arrow-right"></i></div>
                </div>
            </div>
        </div>
    </div>
    <!-- ========== New Items end============= -->

    <!-- ========== Populer Items Start============= -->
    <div class="popular-items1 mb-120">
        <div class="container">
            <div class="row d-flex justify-content-center mb-40">
                <div class="col-lg-8">
                    <div class="section-title text-center">
                        <span><img class="left-vec"  src="{{ asset('client') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec">Our Popular Item<img class="right-vec" src="{{ asset('client') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec"></span>
                        <h2>Restho Popular Item List</h2>
                    </div>
                </div>
            </div>
            <div class="row mb-70">
                <div class="swiper popular-item-slider">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="popular-item-warp">
                                <div class="item-img">
                                    <img class="img-fluid" src="{{ asset('client') }}/images/bg/populer1.png" alt="populer1">
                                    <div class="price-tag">
                                        <svg width="70" height="71" viewBox="0 0 70 71" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M69.5379 34.0184C69.2812 33.6483 69.1038 33.2214 69.0113 32.7669C68.7841 31.6455 68.5514 30.5259 68.3112 29.408C68.1986 28.8839 68.1745 28.303 67.9176 27.8559C67.7312 27.5279 67.5667 27.1907 67.4116 26.848C67.4522 26.8095 67.4615 26.7747 67.3672 26.7434C66.9182 25.7263 66.558 24.6727 65.9724 23.7179C65.3239 22.6605 64.8123 21.5263 64.1268 20.4855C64.2451 20.3957 64.4004 20.3187 64.5904 20.2473C64.6311 20.2325 64.6514 20.1667 64.6773 20.1264C64.4408 20.0897 64.2063 20.0494 63.9698 20.02C63.8996 20.0127 63.8348 19.9963 63.7739 19.9761C63.5098 19.6132 63.2381 19.2577 62.9629 18.906C62.9519 18.8711 62.9408 18.8382 62.9297 18.807C62.8318 18.5102 63.5319 18.5339 63.8533 18.3709C63.9826 18.3031 64.1822 18.3306 64.2264 18.0411C63.5078 17.8524 62.5472 18.402 62.0724 17.6654C61.6659 17.0314 61.0138 16.5514 60.8218 15.7817C61.3316 15.4134 61.9911 15.4537 62.6025 15.2025C62.3531 15.023 61.9837 15.089 61.9984 14.7682C62.0151 14.3467 62.5859 14.4329 62.717 14.1306C61.834 13.8227 60.9527 13.8704 60.0698 14.0793C59.8425 14.1344 59.7114 14.2405 59.39 14.0574C58.1911 13.3756 57.5666 12.2744 56.9385 11.1547C56.7593 10.8359 56.9755 10.6967 57.247 10.6783C58.0006 10.6306 58.7064 10.2715 59.4749 10.3375C59.824 10.3669 60.0807 10.244 60.3246 9.9912C60.0714 9.82999 59.8443 9.85932 59.6631 9.95827C58.8779 10.387 58.0449 10.2679 57.2154 10.2075C57.0177 10.1929 56.7092 10.2809 56.7038 9.98202C56.6963 9.74201 56.9938 9.69973 57.1933 9.63388C57.5775 9.5074 58.0394 9.67238 58.3904 9.34259C58.0929 9.17401 57.7347 9.4196 57.3761 9.08981C59.1368 8.46315 60.9083 8.21756 62.6854 7.75211C62.3843 7.57795 62.1386 7.6366 61.9115 7.67511C60.3228 7.94822 58.7377 8.23214 57.1509 8.5236C56.8035 8.58783 56.2993 8.73069 56.2273 8.37157C56.1515 8.01048 56.6873 8.08028 56.9827 7.9923C57.8566 7.73574 58.7192 7.44247 59.5875 7.16216C59.5708 7.09991 59.5543 7.03568 59.5358 6.97163C58.6472 7.12186 57.755 7.25572 56.8701 7.4297C56.3824 7.52685 55.8429 7.51948 55.4865 7.97575C55.1762 8.37517 54.8713 8.29637 54.5555 7.9556C53.6448 6.96965 52.5123 6.37412 51.2026 6.07005C50.8257 5.98387 50.7279 5.70356 51.0622 5.4416C51.5998 5.01645 52.1651 4.62801 52.7174 4.22481C52.3277 4.22121 52.0173 4.50692 51.6404 4.4722C50.9292 4.40617 50.4304 4.61703 50.2716 5.37557C50.2382 5.53497 49.9556 5.68341 49.9298 5.54415C49.806 4.86604 49.2611 5.34984 48.9489 5.16848C48.633 4.98532 47.9866 5.10803 48.0105 4.63898C48.029 4.25791 48.6109 4.17713 49.0117 4.18451C49.4976 4.19368 49.6841 4.00495 49.6269 3.41301C48.7697 4.04525 47.8295 4.1442 46.9224 4.08195C46.3313 4.04165 45.5775 4.09293 45.2265 3.32521C45.0898 3.02655 44.9291 3.15105 44.8329 3.44252C44.7499 3.69171 44.4727 3.75036 44.227 3.73939C44.0089 3.73021 43.911 3.54885 43.8519 3.36372C43.6246 2.62695 43.4935 2.57207 42.6604 2.86336C42.6622 2.99722 42.8044 3.0082 42.8545 3.07963C43.0355 3.33619 43.5675 3.38567 43.4623 3.74299C43.3458 4.13521 42.9118 3.79804 42.6236 3.87864C42.3778 3.94827 42.0988 3.8934 41.8514 3.8934C41.7923 3.27034 42.2042 2.88369 42.4592 2.42184C42.6551 2.06812 42.9802 1.85186 43.3144 1.80238C43.695 1.74553 43.8058 2.15053 43.8575 2.50784C44.301 2.2 43.8999 1.93246 43.9369 1.65755C44.0238 1.01992 43.6986 0.787102 43.1131 1.03449C42.91 1.12067 42.9856 0.994189 42.945 0.933736C42.5995 0.426185 40.8593 0.176998 40.4639 0.688147C40.0649 1.20307 39.5643 1.1921 39.0599 1.2691C38.6516 1.33136 38.3044 1.40296 38.3227 1.91051C38.3302 2.13595 38.3338 2.37057 38.0641 2.42904C37.8203 2.48212 37.5301 2.43084 37.4378 2.18903C37.2383 1.66133 36.9833 1.93804 36.684 2.06075C36.3441 2.2018 36.2295 2.49687 36.0227 2.83224C35.7954 1.97097 35.1673 2.46574 34.7019 2.40889C34.2124 2.34844 34.4394 2.87794 34.2401 3.10517C33.6933 2.1651 32.7697 2.29158 31.942 2.26405C31.6391 2.25488 31.2641 2.39234 30.9797 2.49129C30.2258 2.75523 30.9575 3.18218 31.0036 3.5816C30.5897 3.5852 30.2794 3.02997 29.8232 3.35994C29.245 3.77591 28.761 3.5359 28.3767 3.08682C28.107 2.7716 28.0016 2.77538 27.828 3.14727C27.4568 3.94252 26.8065 3.88944 26.1193 3.63845C25.9789 3.58718 25.82 3.39106 25.7277 3.42399C24.8317 3.74838 23.9117 4.01592 23.0748 4.48317C22.8828 4.5895 22.8625 4.78363 22.8494 4.97237C22.8365 5.21616 22.8532 5.44879 22.5169 5.51842C22.1935 5.58445 22.0384 5.4891 21.9037 5.18683C21.8317 5.02742 21.5507 4.60048 21.4252 5.21058C21.3828 5.41209 21.2275 5.34246 21.1038 5.35164C20.3464 5.40669 19.7164 5.71453 19.0827 6.14328C18.1183 6.79387 16.9822 7.18051 16.0918 7.97035C15.9033 8.13714 15.6004 8.17384 15.3565 8.28377C15.0868 8.40468 14.7783 8.48906 14.5677 8.67959C13.0806 10.0338 11.4862 11.2927 10.4573 13.0721C10.328 13.2994 10.2005 13.5613 9.99731 13.7044C9.43395 14.102 8.94804 14.6279 8.8501 15.2473C8.77446 15.7256 8.65058 15.8758 8.2258 15.8758C8.27386 16.1342 8.68758 16.0206 8.60451 16.3285C8.58783 16.3889 8.46775 16.4879 8.44744 16.4769C7.84889 16.1324 8.08904 16.6547 8.0816 16.847C8.07416 17.0908 8.04097 17.3051 7.93759 17.5379C7.43136 18.6813 6.91226 19.8266 6.92513 21.1148C6.92894 21.4319 6.4911 21.6645 6.69243 21.9596C7.06552 22.5112 6.76806 22.7915 6.39134 23.1562C6.00899 23.5263 5.54702 23.6125 4.92635 23.6767C5.59328 24.034 5.5619 24.5984 5.31994 24.9009C4.80265 25.5459 5.35313 26.649 4.33524 27.032C4.31311 27.0412 4.29642 27.1585 4.31855 27.1749C4.83022 27.5781 4.25761 27.6459 4.10054 27.754C3.74395 27.9996 3.64618 28.3111 3.6645 28.7107C3.683 29.0753 3.77895 29.4492 3.67738 29.7002C3.10296 29.9366 3.04002 29.0167 2.46179 29.31C2.03138 29.528 1.53821 29.7002 1.31094 30.1547C1.04486 30.6862 1.02654 31.1919 1.7136 31.3274C1.75242 31.8607 1.28682 31.1917 1.21663 31.6426C1.06137 32.6395 0.867474 33.6107 0.509071 34.5873C0.176424 35.4963 0.0989759 36.5646 0.0675975 37.6017C-0.00259577 39.8996 0.5497 42.0876 0.994983 44.3013C1.20556 45.3495 2.00363 46.1869 2.03881 47.2937C2.04262 47.4531 2.18664 47.5942 2.37509 47.4164C2.47666 47.3211 2.61342 47.1818 2.67255 47.3907C2.82218 47.9038 3.53536 48.241 3.04945 48.9796C3.29141 48.8605 3.44286 48.7194 3.53899 48.7505C3.75519 48.8202 3.70531 49.0474 3.70531 49.2325C3.70531 49.3865 3.42454 49.5001 3.58343 49.6449C4.22623 50.2404 4.38149 51.2942 5.31813 51.5799C5.75035 51.71 5.23868 52.2469 5.65059 52.1956C6.26383 52.1224 6.28614 52.6373 6.29158 52.9011C6.31552 53.9345 7.11377 54.5009 7.5625 55.2778C7.87283 55.8129 8.54901 55.9521 8.72458 56.5898C8.91304 57.2715 9.17712 57.8744 9.82011 58.3636C10.6606 59.0012 11.274 59.9267 12.2605 60.4085C14.5143 61.5098 16.6388 62.798 18.4546 64.5481C18.5544 64.6435 18.6782 64.8468 18.8573 64.7404C19.3856 64.4252 19.491 64.7331 19.5501 65.1638C19.5723 65.3178 19.6147 65.4754 19.7885 65.554C21.0519 66.124 22.1937 67.0072 23.6402 67.108C24.0927 67.1391 24.5398 67.0384 24.9019 67.4378C24.9887 67.5331 25.2253 67.6614 25.4135 67.4799C25.746 67.1573 26.0342 67.3791 26.3612 67.5238C27.1611 67.8737 27.9962 68.1377 28.8477 67.7253C29.1396 67.586 29.4131 67.6593 29.4684 67.837C29.6476 68.3996 30.1335 68.3665 30.538 68.5754C31.9863 69.3231 33.1852 68.9327 34.3694 67.9559C34.4286 68.1521 34.4951 68.3206 34.362 68.491C34.0018 68.9509 34 68.9509 34.5967 69.224C34.7371 69.2881 34.798 69.4 34.8073 69.5374C34.8294 69.8526 35.0345 69.8399 35.2858 69.9241C35.9231 70.133 36.59 69.5283 37.194 70.034C37.2254 70.0597 37.3455 70.0029 37.4102 69.9626C38.0975 69.5247 38.5261 68.7422 39.4183 68.5644C39.8431 68.48 39.3943 68.1191 39.5476 67.8021C39.8099 68.568 40.4508 67.6335 40.7742 68.176C41.1381 67.7636 41.6369 67.4723 41.8458 66.8437C42.267 68.11 42.6439 68.4214 43.477 68.2071C46.1316 67.5272 48.6384 66.5376 50.8313 64.8426C51.8289 64.0729 52.8818 63.3749 53.9385 62.6876C54.5777 62.2716 55.0119 62.4861 55.1523 63.2025C55.2798 63.8549 55.9356 64.192 56.5044 63.8475C56.8665 63.6276 57.2064 63.3417 57.4835 63.0247C57.6738 62.8067 58.0728 62.5611 57.8345 62.2129C57.5758 61.8337 57.1509 61.9729 56.8073 62.1323C56.1553 62.4366 55.5364 62.1928 54.7327 62.1507C55.2758 61.8117 55.6933 61.6138 56.0352 61.3261C57.5316 60.0708 59.1277 58.8999 60.3137 57.3386C61.182 56.197 62.2073 55.8433 63.5466 56.1751C63.9086 56.263 64.0305 56.1585 64.1377 55.7792C64.3447 55.0516 63.977 54.4379 63.8495 53.7836C63.7922 53.4903 63.6721 53.3035 63.8976 52.9993C64.6401 51.9988 65.3348 50.9651 65.913 49.8585C66.2049 49.3032 66.5558 48.8414 67.197 48.607C67.5554 48.4769 67.9249 48.0114 67.766 47.709C67.5037 47.2124 67.5942 46.822 67.7143 46.3493C67.7549 46.1917 67.7513 46.0231 67.7421 45.8546C68.226 44.9676 68.4828 44.0165 68.7415 43.0637C68.941 42.3271 69.2919 41.5775 69.0924 40.7895C68.9705 40.3057 69.1182 39.9099 69.2513 39.4737C69.4434 38.8453 69.8 38.2258 69.6115 37.5387C69.4379 36.8973 69.5727 36.2908 69.739 35.6879C69.9019 35.096 69.9112 34.5591 69.5379 34.0184ZM10.7147 27.5644C10.4856 27.7972 10.3673 27.4563 10.2714 27.2841C10.1901 27.1412 10.1217 26.9561 9.92766 27.0844C9.75227 27.1999 9.90372 27.3152 10.0053 27.3978C10.2104 27.5646 10.2066 27.788 10.1161 27.9823C9.9536 28.3251 9.76878 27.8962 9.5767 27.9696C9.42162 28.03 9.26273 28.019 9.10185 27.9879C9.31243 27.2603 9.5397 26.5385 9.79653 25.8237C9.80578 25.8311 9.81322 25.8383 9.82428 25.8474C9.88522 25.7704 9.89266 25.6715 9.88885 25.5689C9.95161 25.4003 10.0182 25.2317 10.0847 25.0632C10.1254 25.0558 10.1716 25.0504 10.2288 25.0522C10.4966 25.0578 10.5908 25.2557 10.5872 25.4975C10.5743 26.1956 11.4112 26.8607 10.7147 27.5644ZM10.5208 16.3661C10.5393 16.1021 10.6483 15.9172 10.953 15.908C11.3132 15.897 11.2412 16.1334 11.2042 16.335C11.1489 16.63 11.2836 16.8792 11.3649 17.1705C10.9363 17.0167 10.4763 16.9233 10.5208 16.3661ZM11.8858 25.7411C11.8488 25.8309 11.8138 25.9207 11.7786 26.0104C11.6825 25.8033 11.6863 25.5486 11.8064 25.2389C11.921 24.9439 11.8693 24.8248 11.7066 24.6305C11.5514 24.4453 11.52 24.1375 11.7195 24.0607C12.4307 23.7857 11.8969 23.3827 11.8414 22.9173C12.244 23.282 12.6302 23.1884 12.9423 22.9759C13.2434 22.7688 13.7034 22.5967 13.5095 22.0578C13.4892 22.0027 13.5355 21.8727 13.5833 21.8525C14.8247 21.3412 13.6278 21.0938 13.4283 20.6651C14.06 20.61 14.1191 20.1759 14.0507 19.6939C13.9621 19.0764 14.4719 19.0397 14.8174 19.0139C15.2293 18.9828 15.0964 19.384 15.0761 19.6242C15.0483 19.9724 15.1038 20.3095 15.1684 20.6467C13.8146 22.1641 12.6563 23.8188 11.8858 25.7411ZM13.105 53.8203C13.0274 53.7506 12.959 53.6774 12.8888 53.6022C12.9683 53.6774 13.0439 53.7542 13.1197 53.8331C13.1143 53.8277 13.1089 53.8241 13.105 53.8203ZM15.3663 22.4023C15.0855 22.8788 14.7124 23.359 14.3206 23.8023C14.559 23.3295 14.8138 22.864 15.0891 22.4077C15.176 22.2649 15.2629 22.1201 15.346 21.9752C15.3699 22.0632 15.4198 22.1328 15.5234 22.1512C15.4697 22.2338 15.4162 22.3144 15.3663 22.4023ZM16.6871 10.0808C16.8367 10.002 16.9403 10.09 17.0103 10.2145C17.2098 10.57 17.6163 10.4894 18.0614 10.7037C17.6346 10.8814 17.5682 11.4532 17.0324 11.2645C16.6537 11.1326 16.6998 10.7679 16.5928 10.4838C16.5282 10.3098 16.5117 10.1742 16.6871 10.0808ZM16.4803 30.6504C16.3214 29.8441 16.1571 29.0433 16.4841 28.2114C16.5986 27.9219 16.7796 27.6781 16.9497 27.4289C17.068 27.5809 17.1973 27.7404 17.356 27.8614C17.2027 28.3287 17.0605 28.7997 16.8942 29.2596C16.7279 29.7159 16.5966 30.1813 16.4803 30.6504ZM17.6237 58.0937C17.5276 58.0075 17.4409 57.9159 17.3558 57.8206C17.7178 58.0881 18.0855 58.3521 18.4588 58.6066C18.1613 58.4748 17.8731 58.3209 17.6237 58.0937ZM20.9268 9.63352C20.7255 9.7197 20.5241 9.80948 20.3264 9.90286C20.186 9.83503 20.1418 9.71592 20.1768 9.54554C20.2192 9.3219 20.1768 9.01964 20.5205 8.99769C20.8586 8.97754 20.9047 9.26163 20.9288 9.49246C20.9324 9.54194 20.9324 9.58782 20.9268 9.63352ZM25.7337 62.4825C25.9165 62.5467 26.0884 62.6253 26.2343 62.7426C26.3803 62.8599 26.5317 62.9553 26.6868 63.0377C26.6092 63.1019 26.5335 63.1935 26.4633 63.3218C26.4595 63.3272 26.4577 63.3292 26.4541 63.3346C26.2713 63.2502 26.0903 63.1642 25.9072 63.0798C25.8574 63.0377 25.8093 62.9974 25.7649 62.9661C25.6486 62.7885 25.6615 62.6365 25.7337 62.4825ZM25.7576 63.0102C25.5452 62.913 25.3346 62.814 25.1259 62.7151C25.3364 62.7811 25.5434 62.8837 25.754 62.9661C25.754 62.9826 25.7558 62.9956 25.7576 63.0102ZM25.68 60.223C25.4491 60.333 25.1997 60.4082 24.9521 60.4832C24.9187 60.4228 24.8578 60.3751 24.8025 60.3348C24.5346 60.146 24.289 59.9334 24.0525 59.7099C24.5957 59.8749 25.1387 60.0471 25.68 60.223ZM23.3395 60.4613C23.14 60.2761 22.9461 60.0453 22.7261 59.8693C23.2009 60.1424 23.683 60.4044 24.1707 60.6574C24.2095 60.6775 24.2465 60.6941 24.2854 60.7086C24.2817 60.7104 24.2761 60.7122 24.2707 60.7142C24.1506 60.7673 24.0416 60.7856 23.938 60.7803C23.7367 60.6775 23.5372 60.573 23.3395 60.4613ZM26.7829 64.7419C26.8143 65.1084 26.5299 65.2165 26.334 65.0535C25.7188 64.5477 24.8728 64.5972 24.2409 64.0969C24.5106 64.1739 24.7785 64.2527 25.0537 64.3004C25.8278 64.434 26.6092 64.5312 27.3905 64.6319C27.3684 64.8245 27.2465 64.9306 26.7829 64.7419ZM27.0287 63.5857C27.1007 63.5215 27.1856 63.485 27.2984 63.5235C27.4167 63.5656 27.4627 63.65 27.4684 63.7433C27.3205 63.6957 27.1727 63.6424 27.0287 63.5857ZM26.4189 9.57307C26.3765 9.55472 26.3357 9.42643 26.3562 9.40269C26.6407 9.08387 27.012 9.18462 27.5291 9.1517C27.1247 9.54014 26.831 9.7636 26.4189 9.57307ZM27.0711 7.30447C27.0213 6.87375 27.1007 6.51643 27.5126 6.38455C27.7029 6.3241 27.6401 6.54756 27.6789 6.65209C27.8988 7.2532 27.2964 7.10656 27.0711 7.30447ZM32.1439 4.19836C32.1956 4.15626 32.3563 4.19098 32.4321 4.24226C32.5319 4.30649 32.5337 4.45474 32.4285 4.508C31.8263 4.80666 32.1089 5.12746 32.5653 5.5195C31.843 5.54703 31.6065 5.27391 31.5456 4.82861C31.4862 4.39447 31.9223 4.38529 32.1439 4.19836ZM33.3374 8.82551C32.9846 9.28916 32.6003 9.38991 32.2419 9.47052C31.3718 9.66843 30.5627 10.0698 29.6741 10.2035C29.5669 10.2183 29.4561 10.3025 29.4247 10.1393C29.4118 10.0714 29.4653 9.93399 29.5098 9.92301C30.7547 9.62435 31.8778 8.89693 33.3374 8.82551ZM28.185 6.87753C28.0852 6.74007 28.1757 6.61916 28.294 6.54396C28.6894 6.29297 28.3217 5.66254 28.8889 5.44807C28.9277 5.9648 29.0164 6.445 29.6832 6.04001C29.6832 6.03443 29.687 6.02903 29.687 6.02165C29.6926 6.02345 29.698 6.02723 29.7055 6.02903L29.6833 6.04181C30.0492 6.48531 30.4961 5.77427 30.8989 6.12619C30.9895 6.20499 31.2296 6.14634 31.2018 6.37537C31.1779 6.55853 31.0041 6.54036 30.8841 6.51643C30.0546 6.35342 29.336 6.84262 28.5436 6.8975C28.4179 6.90685 28.2829 7.01678 28.185 6.87753ZM29.9438 59.0833C29.8662 59.0356 29.7867 58.9899 29.7111 58.9366C29.2825 58.638 28.8372 58.3704 28.3885 58.1121C29.061 58.44 29.7095 58.8103 30.3466 59.197C30.2135 59.1583 30.0786 59.1198 29.9438 59.0833ZM35.8293 62.2736C35.9106 62.2736 35.9919 62.2736 36.0749 62.2718C36.3187 62.3707 36.5313 62.5064 36.7252 62.6694C36.3891 62.6768 36.0807 62.598 35.8293 62.2736ZM36.5646 10.9054C34.4864 10.9181 32.4339 11.2683 30.376 11.6164C30.9044 11.3562 31.4235 11.1107 31.9961 10.9586C32.7368 10.7625 33.4925 10.7992 34.2314 10.7039C35.155 10.5866 36.0713 10.5133 36.9894 10.5115C36.838 10.6213 36.6976 10.7569 36.5646 10.9054ZM38.2587 62.5118C38.3788 62.3781 38.5876 62.4092 38.776 62.4623C38.6041 62.4769 38.4323 62.4952 38.2587 62.5118ZM41.4415 5.03588C41.1774 4.93513 41.0554 4.80127 41.0407 4.59796C41.0111 4.19656 40.8245 4.0537 40.4681 4.27734C40.0413 4.54308 39.8474 4.14726 39.5646 4.00063C39.3466 3.88692 39.3412 3.68001 39.4502 3.5242C39.6183 3.28239 39.8086 3.49128 39.8991 3.60859C40.1688 3.95673 40.3776 4.06666 40.5272 3.54256C40.5936 3.30434 40.7581 3.27322 40.9594 3.30075C41.1718 3.33187 41.2643 3.47671 41.3235 3.67282C41.4546 4.10498 41.449 4.5447 41.4415 5.03588ZM42.247 7.70209C42.1935 7.64704 42.1103 7.60314 42.0918 7.53909C42.0475 7.39066 42.1471 7.29728 42.2673 7.22387C42.5721 7.03873 42.9176 7.05889 43.252 7.02038C43.2631 7.7388 42.5057 7.36673 42.247 7.70209ZM44.3106 8.43148C44.5471 8.64775 44.4473 9.01424 43.991 8.84008C43.7804 8.75948 43.5753 8.64217 43.2724 8.57254C43.6382 8.28665 44.063 8.20605 44.3106 8.43148ZM44.4566 61.5663C44.1074 61.6397 43.7565 61.6964 43.4369 61.6158C43.7048 61.5992 43.9689 61.5627 44.2257 61.4674C44.283 61.4454 44.4602 61.4253 44.5877 61.537C44.5453 61.5462 44.5008 61.5571 44.4566 61.5663ZM45.1086 7.24042C45.674 6.85378 45.5704 6.60459 44.8242 6.41208C45.2657 6.34245 45.5835 5.9612 45.7202 6.56789C45.8327 7.07724 45.6591 7.30088 45.1086 7.24042ZM48.3821 60.6683C48.2436 60.6903 48.105 60.7178 47.9664 60.7435C48.3803 60.1187 49.5625 59.7338 50.2092 60.082C49.6531 60.3531 49.0491 60.5566 48.3821 60.6683ZM50.9185 59.6824C51.1106 59.4551 51.3433 59.2866 51.6132 59.182C51.3896 59.36 51.1587 59.5268 50.9185 59.6824ZM57.907 16.2067C57.8867 16.1809 57.8664 16.1572 57.8461 16.1315C57.8682 16.1442 57.8885 16.159 57.9126 16.17C57.9107 16.1829 57.9088 16.1939 57.907 16.2067ZM61.1658 48.3943C61.116 48.4511 61.0661 48.506 61.0144 48.5629C61.2342 48.2367 61.4448 47.9069 61.6499 47.5733C61.4947 47.85 61.334 48.1232 61.1658 48.3943ZM63.8629 20.8558C64.1345 21.2626 64.3746 21.6933 64.5409 22.1733C64.8088 22.9502 65.2835 23.6191 65.6845 24.3394C65.0508 23.5679 64.4597 22.7451 64.0532 21.8307C63.8611 21.4018 63.7982 21.094 63.8629 20.8558ZM62.4054 22.9759C62.5938 22.9466 62.7877 22.8642 62.9798 22.7469C63.2256 22.5929 63.5969 22.5691 63.5488 22.8109C63.3529 23.7949 64.4337 24.0094 64.6664 24.7661C64.7865 25.1528 64.8161 25.2058 64.5132 25.3525C64.0588 25.5741 63.946 25.8179 64.4412 26.1331C64.5206 26.1826 64.5926 26.3017 64.5982 26.3933C64.6314 26.8202 64.7571 27.2821 64.8255 27.6943C64.2748 26.005 63.5212 24.4108 62.4054 22.9759ZM65.0968 40.4505C65.3406 39.4408 65.5273 38.4165 65.701 37.3885C65.7694 38.4586 65.4387 39.4646 65.0968 40.4505ZM65.7121 31.5465C65.7028 31.5044 65.6954 31.4603 65.6918 31.4147C65.7563 31.4621 65.7509 31.5042 65.7121 31.5465ZM67.7571 35.981C67.7091 34.7953 67.6057 33.6134 67.5206 32.4296C67.4762 31.8193 67.3378 31.2256 67.4375 30.5971C67.4522 30.5091 67.4671 30.4211 67.4838 30.3332C67.8643 32.2007 67.8901 34.099 67.7571 35.981Z" />
                                        </svg>
                                        <span>$25</span>
                                    </div>
                                </div>
                                <div class="item-content text-center">
                                    <div class="review-area">
                                        <ul>
                                            <li><i class=" bi bi-star-fill"></i></li>
                                            <li><i class=" bi bi-star-fill"></i></li>
                                            <li><i class=" bi bi-star-fill"></i></li>
                                            <li><i class=" bi bi-star-fill"></i></li>
                                        </ul>
                                        <span>Review(20)</span>
                                    </div>
                                    <h3><a href="shop-details.html">Beaf Machal</a></h3>
                                    <p>It is a long established fact that a reader will be distracted.</p>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="popular-item-warp">
                                <div class="item-img">
                                    <img class="img-fluid" src="{{ asset('client') }}/images/bg/populer2.png" alt="populer2">
                                    <div class="price-tag">
                                        <svg width="70" height="71" viewBox="0 0 70 71" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M69.5379 34.0184C69.2812 33.6483 69.1038 33.2214 69.0113 32.7669C68.7841 31.6455 68.5514 30.5259 68.3112 29.408C68.1986 28.8839 68.1745 28.303 67.9176 27.8559C67.7312 27.5279 67.5667 27.1907 67.4116 26.848C67.4522 26.8095 67.4615 26.7747 67.3672 26.7434C66.9182 25.7263 66.558 24.6727 65.9724 23.7179C65.3239 22.6605 64.8123 21.5263 64.1268 20.4855C64.2451 20.3957 64.4004 20.3187 64.5904 20.2473C64.6311 20.2325 64.6514 20.1667 64.6773 20.1264C64.4408 20.0897 64.2063 20.0494 63.9698 20.02C63.8996 20.0127 63.8348 19.9963 63.7739 19.9761C63.5098 19.6132 63.2381 19.2577 62.9629 18.906C62.9519 18.8711 62.9408 18.8382 62.9297 18.807C62.8318 18.5102 63.5319 18.5339 63.8533 18.3709C63.9826 18.3031 64.1822 18.3306 64.2264 18.0411C63.5078 17.8524 62.5472 18.402 62.0724 17.6654C61.6659 17.0314 61.0138 16.5514 60.8218 15.7817C61.3316 15.4134 61.9911 15.4537 62.6025 15.2025C62.3531 15.023 61.9837 15.089 61.9984 14.7682C62.0151 14.3467 62.5859 14.4329 62.717 14.1306C61.834 13.8227 60.9527 13.8704 60.0698 14.0793C59.8425 14.1344 59.7114 14.2405 59.39 14.0574C58.1911 13.3756 57.5666 12.2744 56.9385 11.1547C56.7593 10.8359 56.9755 10.6967 57.247 10.6783C58.0006 10.6306 58.7064 10.2715 59.4749 10.3375C59.824 10.3669 60.0807 10.244 60.3246 9.9912C60.0714 9.82999 59.8443 9.85932 59.6631 9.95827C58.8779 10.387 58.0449 10.2679 57.2154 10.2075C57.0177 10.1929 56.7092 10.2809 56.7038 9.98202C56.6963 9.74201 56.9938 9.69973 57.1933 9.63388C57.5775 9.5074 58.0394 9.67238 58.3904 9.34259C58.0929 9.17401 57.7347 9.4196 57.3761 9.08981C59.1368 8.46315 60.9083 8.21756 62.6854 7.75211C62.3843 7.57795 62.1386 7.6366 61.9115 7.67511C60.3228 7.94822 58.7377 8.23214 57.1509 8.5236C56.8035 8.58783 56.2993 8.73069 56.2273 8.37157C56.1515 8.01048 56.6873 8.08028 56.9827 7.9923C57.8566 7.73574 58.7192 7.44247 59.5875 7.16216C59.5708 7.09991 59.5543 7.03568 59.5358 6.97163C58.6472 7.12186 57.755 7.25572 56.8701 7.4297C56.3824 7.52685 55.8429 7.51948 55.4865 7.97575C55.1762 8.37517 54.8713 8.29637 54.5555 7.9556C53.6448 6.96965 52.5123 6.37412 51.2026 6.07005C50.8257 5.98387 50.7279 5.70356 51.0622 5.4416C51.5998 5.01645 52.1651 4.62801 52.7174 4.22481C52.3277 4.22121 52.0173 4.50692 51.6404 4.4722C50.9292 4.40617 50.4304 4.61703 50.2716 5.37557C50.2382 5.53497 49.9556 5.68341 49.9298 5.54415C49.806 4.86604 49.2611 5.34984 48.9489 5.16848C48.633 4.98532 47.9866 5.10803 48.0105 4.63898C48.029 4.25791 48.6109 4.17713 49.0117 4.18451C49.4976 4.19368 49.6841 4.00495 49.6269 3.41301C48.7697 4.04525 47.8295 4.1442 46.9224 4.08195C46.3313 4.04165 45.5775 4.09293 45.2265 3.32521C45.0898 3.02655 44.9291 3.15105 44.8329 3.44252C44.7499 3.69171 44.4727 3.75036 44.227 3.73939C44.0089 3.73021 43.911 3.54885 43.8519 3.36372C43.6246 2.62695 43.4935 2.57207 42.6604 2.86336C42.6622 2.99722 42.8044 3.0082 42.8545 3.07963C43.0355 3.33619 43.5675 3.38567 43.4623 3.74299C43.3458 4.13521 42.9118 3.79804 42.6236 3.87864C42.3778 3.94827 42.0988 3.8934 41.8514 3.8934C41.7923 3.27034 42.2042 2.88369 42.4592 2.42184C42.6551 2.06812 42.9802 1.85186 43.3144 1.80238C43.695 1.74553 43.8058 2.15053 43.8575 2.50784C44.301 2.2 43.8999 1.93246 43.9369 1.65755C44.0238 1.01992 43.6986 0.787102 43.1131 1.03449C42.91 1.12067 42.9856 0.994189 42.945 0.933736C42.5995 0.426185 40.8593 0.176998 40.4639 0.688147C40.0649 1.20307 39.5643 1.1921 39.0599 1.2691C38.6516 1.33136 38.3044 1.40296 38.3227 1.91051C38.3302 2.13595 38.3338 2.37057 38.0641 2.42904C37.8203 2.48212 37.5301 2.43084 37.4378 2.18903C37.2383 1.66133 36.9833 1.93804 36.684 2.06075C36.3441 2.2018 36.2295 2.49687 36.0227 2.83224C35.7954 1.97097 35.1673 2.46574 34.7019 2.40889C34.2124 2.34844 34.4394 2.87794 34.2401 3.10517C33.6933 2.1651 32.7697 2.29158 31.942 2.26405C31.6391 2.25488 31.2641 2.39234 30.9797 2.49129C30.2258 2.75523 30.9575 3.18218 31.0036 3.5816C30.5897 3.5852 30.2794 3.02997 29.8232 3.35994C29.245 3.77591 28.761 3.5359 28.3767 3.08682C28.107 2.7716 28.0016 2.77538 27.828 3.14727C27.4568 3.94252 26.8065 3.88944 26.1193 3.63845C25.9789 3.58718 25.82 3.39106 25.7277 3.42399C24.8317 3.74838 23.9117 4.01592 23.0748 4.48317C22.8828 4.5895 22.8625 4.78363 22.8494 4.97237C22.8365 5.21616 22.8532 5.44879 22.5169 5.51842C22.1935 5.58445 22.0384 5.4891 21.9037 5.18683C21.8317 5.02742 21.5507 4.60048 21.4252 5.21058C21.3828 5.41209 21.2275 5.34246 21.1038 5.35164C20.3464 5.40669 19.7164 5.71453 19.0827 6.14328C18.1183 6.79387 16.9822 7.18051 16.0918 7.97035C15.9033 8.13714 15.6004 8.17384 15.3565 8.28377C15.0868 8.40468 14.7783 8.48906 14.5677 8.67959C13.0806 10.0338 11.4862 11.2927 10.4573 13.0721C10.328 13.2994 10.2005 13.5613 9.99731 13.7044C9.43395 14.102 8.94804 14.6279 8.8501 15.2473C8.77446 15.7256 8.65058 15.8758 8.2258 15.8758C8.27386 16.1342 8.68758 16.0206 8.60451 16.3285C8.58783 16.3889 8.46775 16.4879 8.44744 16.4769C7.84889 16.1324 8.08904 16.6547 8.0816 16.847C8.07416 17.0908 8.04097 17.3051 7.93759 17.5379C7.43136 18.6813 6.91226 19.8266 6.92513 21.1148C6.92894 21.4319 6.4911 21.6645 6.69243 21.9596C7.06552 22.5112 6.76806 22.7915 6.39134 23.1562C6.00899 23.5263 5.54702 23.6125 4.92635 23.6767C5.59328 24.034 5.5619 24.5984 5.31994 24.9009C4.80265 25.5459 5.35313 26.649 4.33524 27.032C4.31311 27.0412 4.29642 27.1585 4.31855 27.1749C4.83022 27.5781 4.25761 27.6459 4.10054 27.754C3.74395 27.9996 3.64618 28.3111 3.6645 28.7107C3.683 29.0753 3.77895 29.4492 3.67738 29.7002C3.10296 29.9366 3.04002 29.0167 2.46179 29.31C2.03138 29.528 1.53821 29.7002 1.31094 30.1547C1.04486 30.6862 1.02654 31.1919 1.7136 31.3274C1.75242 31.8607 1.28682 31.1917 1.21663 31.6426C1.06137 32.6395 0.867474 33.6107 0.509071 34.5873C0.176424 35.4963 0.0989759 36.5646 0.0675975 37.6017C-0.00259577 39.8996 0.5497 42.0876 0.994983 44.3013C1.20556 45.3495 2.00363 46.1869 2.03881 47.2937C2.04262 47.4531 2.18664 47.5942 2.37509 47.4164C2.47666 47.3211 2.61342 47.1818 2.67255 47.3907C2.82218 47.9038 3.53536 48.241 3.04945 48.9796C3.29141 48.8605 3.44286 48.7194 3.53899 48.7505C3.75519 48.8202 3.70531 49.0474 3.70531 49.2325C3.70531 49.3865 3.42454 49.5001 3.58343 49.6449C4.22623 50.2404 4.38149 51.2942 5.31813 51.5799C5.75035 51.71 5.23868 52.2469 5.65059 52.1956C6.26383 52.1224 6.28614 52.6373 6.29158 52.9011C6.31552 53.9345 7.11377 54.5009 7.5625 55.2778C7.87283 55.8129 8.54901 55.9521 8.72458 56.5898C8.91304 57.2715 9.17712 57.8744 9.82011 58.3636C10.6606 59.0012 11.274 59.9267 12.2605 60.4085C14.5143 61.5098 16.6388 62.798 18.4546 64.5481C18.5544 64.6435 18.6782 64.8468 18.8573 64.7404C19.3856 64.4252 19.491 64.7331 19.5501 65.1638C19.5723 65.3178 19.6147 65.4754 19.7885 65.554C21.0519 66.124 22.1937 67.0072 23.6402 67.108C24.0927 67.1391 24.5398 67.0384 24.9019 67.4378C24.9887 67.5331 25.2253 67.6614 25.4135 67.4799C25.746 67.1573 26.0342 67.3791 26.3612 67.5238C27.1611 67.8737 27.9962 68.1377 28.8477 67.7253C29.1396 67.586 29.4131 67.6593 29.4684 67.837C29.6476 68.3996 30.1335 68.3665 30.538 68.5754C31.9863 69.3231 33.1852 68.9327 34.3694 67.9559C34.4286 68.1521 34.4951 68.3206 34.362 68.491C34.0018 68.9509 34 68.9509 34.5967 69.224C34.7371 69.2881 34.798 69.4 34.8073 69.5374C34.8294 69.8526 35.0345 69.8399 35.2858 69.9241C35.9231 70.133 36.59 69.5283 37.194 70.034C37.2254 70.0597 37.3455 70.0029 37.4102 69.9626C38.0975 69.5247 38.5261 68.7422 39.4183 68.5644C39.8431 68.48 39.3943 68.1191 39.5476 67.8021C39.8099 68.568 40.4508 67.6335 40.7742 68.176C41.1381 67.7636 41.6369 67.4723 41.8458 66.8437C42.267 68.11 42.6439 68.4214 43.477 68.2071C46.1316 67.5272 48.6384 66.5376 50.8313 64.8426C51.8289 64.0729 52.8818 63.3749 53.9385 62.6876C54.5777 62.2716 55.0119 62.4861 55.1523 63.2025C55.2798 63.8549 55.9356 64.192 56.5044 63.8475C56.8665 63.6276 57.2064 63.3417 57.4835 63.0247C57.6738 62.8067 58.0728 62.5611 57.8345 62.2129C57.5758 61.8337 57.1509 61.9729 56.8073 62.1323C56.1553 62.4366 55.5364 62.1928 54.7327 62.1507C55.2758 61.8117 55.6933 61.6138 56.0352 61.3261C57.5316 60.0708 59.1277 58.8999 60.3137 57.3386C61.182 56.197 62.2073 55.8433 63.5466 56.1751C63.9086 56.263 64.0305 56.1585 64.1377 55.7792C64.3447 55.0516 63.977 54.4379 63.8495 53.7836C63.7922 53.4903 63.6721 53.3035 63.8976 52.9993C64.6401 51.9988 65.3348 50.9651 65.913 49.8585C66.2049 49.3032 66.5558 48.8414 67.197 48.607C67.5554 48.4769 67.9249 48.0114 67.766 47.709C67.5037 47.2124 67.5942 46.822 67.7143 46.3493C67.7549 46.1917 67.7513 46.0231 67.7421 45.8546C68.226 44.9676 68.4828 44.0165 68.7415 43.0637C68.941 42.3271 69.2919 41.5775 69.0924 40.7895C68.9705 40.3057 69.1182 39.9099 69.2513 39.4737C69.4434 38.8453 69.8 38.2258 69.6115 37.5387C69.4379 36.8973 69.5727 36.2908 69.739 35.6879C69.9019 35.096 69.9112 34.5591 69.5379 34.0184ZM10.7147 27.5644C10.4856 27.7972 10.3673 27.4563 10.2714 27.2841C10.1901 27.1412 10.1217 26.9561 9.92766 27.0844C9.75227 27.1999 9.90372 27.3152 10.0053 27.3978C10.2104 27.5646 10.2066 27.788 10.1161 27.9823C9.9536 28.3251 9.76878 27.8962 9.5767 27.9696C9.42162 28.03 9.26273 28.019 9.10185 27.9879C9.31243 27.2603 9.5397 26.5385 9.79653 25.8237C9.80578 25.8311 9.81322 25.8383 9.82428 25.8474C9.88522 25.7704 9.89266 25.6715 9.88885 25.5689C9.95161 25.4003 10.0182 25.2317 10.0847 25.0632C10.1254 25.0558 10.1716 25.0504 10.2288 25.0522C10.4966 25.0578 10.5908 25.2557 10.5872 25.4975C10.5743 26.1956 11.4112 26.8607 10.7147 27.5644ZM10.5208 16.3661C10.5393 16.1021 10.6483 15.9172 10.953 15.908C11.3132 15.897 11.2412 16.1334 11.2042 16.335C11.1489 16.63 11.2836 16.8792 11.3649 17.1705C10.9363 17.0167 10.4763 16.9233 10.5208 16.3661ZM11.8858 25.7411C11.8488 25.8309 11.8138 25.9207 11.7786 26.0104C11.6825 25.8033 11.6863 25.5486 11.8064 25.2389C11.921 24.9439 11.8693 24.8248 11.7066 24.6305C11.5514 24.4453 11.52 24.1375 11.7195 24.0607C12.4307 23.7857 11.8969 23.3827 11.8414 22.9173C12.244 23.282 12.6302 23.1884 12.9423 22.9759C13.2434 22.7688 13.7034 22.5967 13.5095 22.0578C13.4892 22.0027 13.5355 21.8727 13.5833 21.8525C14.8247 21.3412 13.6278 21.0938 13.4283 20.6651C14.06 20.61 14.1191 20.1759 14.0507 19.6939C13.9621 19.0764 14.4719 19.0397 14.8174 19.0139C15.2293 18.9828 15.0964 19.384 15.0761 19.6242C15.0483 19.9724 15.1038 20.3095 15.1684 20.6467C13.8146 22.1641 12.6563 23.8188 11.8858 25.7411ZM13.105 53.8203C13.0274 53.7506 12.959 53.6774 12.8888 53.6022C12.9683 53.6774 13.0439 53.7542 13.1197 53.8331C13.1143 53.8277 13.1089 53.8241 13.105 53.8203ZM15.3663 22.4023C15.0855 22.8788 14.7124 23.359 14.3206 23.8023C14.559 23.3295 14.8138 22.864 15.0891 22.4077C15.176 22.2649 15.2629 22.1201 15.346 21.9752C15.3699 22.0632 15.4198 22.1328 15.5234 22.1512C15.4697 22.2338 15.4162 22.3144 15.3663 22.4023ZM16.6871 10.0808C16.8367 10.002 16.9403 10.09 17.0103 10.2145C17.2098 10.57 17.6163 10.4894 18.0614 10.7037C17.6346 10.8814 17.5682 11.4532 17.0324 11.2645C16.6537 11.1326 16.6998 10.7679 16.5928 10.4838C16.5282 10.3098 16.5117 10.1742 16.6871 10.0808ZM16.4803 30.6504C16.3214 29.8441 16.1571 29.0433 16.4841 28.2114C16.5986 27.9219 16.7796 27.6781 16.9497 27.4289C17.068 27.5809 17.1973 27.7404 17.356 27.8614C17.2027 28.3287 17.0605 28.7997 16.8942 29.2596C16.7279 29.7159 16.5966 30.1813 16.4803 30.6504ZM17.6237 58.0937C17.5276 58.0075 17.4409 57.9159 17.3558 57.8206C17.7178 58.0881 18.0855 58.3521 18.4588 58.6066C18.1613 58.4748 17.8731 58.3209 17.6237 58.0937ZM20.9268 9.63352C20.7255 9.7197 20.5241 9.80948 20.3264 9.90286C20.186 9.83503 20.1418 9.71592 20.1768 9.54554C20.2192 9.3219 20.1768 9.01964 20.5205 8.99769C20.8586 8.97754 20.9047 9.26163 20.9288 9.49246C20.9324 9.54194 20.9324 9.58782 20.9268 9.63352ZM25.7337 62.4825C25.9165 62.5467 26.0884 62.6253 26.2343 62.7426C26.3803 62.8599 26.5317 62.9553 26.6868 63.0377C26.6092 63.1019 26.5335 63.1935 26.4633 63.3218C26.4595 63.3272 26.4577 63.3292 26.4541 63.3346C26.2713 63.2502 26.0903 63.1642 25.9072 63.0798C25.8574 63.0377 25.8093 62.9974 25.7649 62.9661C25.6486 62.7885 25.6615 62.6365 25.7337 62.4825ZM25.7576 63.0102C25.5452 62.913 25.3346 62.814 25.1259 62.7151C25.3364 62.7811 25.5434 62.8837 25.754 62.9661C25.754 62.9826 25.7558 62.9956 25.7576 63.0102ZM25.68 60.223C25.4491 60.333 25.1997 60.4082 24.9521 60.4832C24.9187 60.4228 24.8578 60.3751 24.8025 60.3348C24.5346 60.146 24.289 59.9334 24.0525 59.7099C24.5957 59.8749 25.1387 60.0471 25.68 60.223ZM23.3395 60.4613C23.14 60.2761 22.9461 60.0453 22.7261 59.8693C23.2009 60.1424 23.683 60.4044 24.1707 60.6574C24.2095 60.6775 24.2465 60.6941 24.2854 60.7086C24.2817 60.7104 24.2761 60.7122 24.2707 60.7142C24.1506 60.7673 24.0416 60.7856 23.938 60.7803C23.7367 60.6775 23.5372 60.573 23.3395 60.4613ZM26.7829 64.7419C26.8143 65.1084 26.5299 65.2165 26.334 65.0535C25.7188 64.5477 24.8728 64.5972 24.2409 64.0969C24.5106 64.1739 24.7785 64.2527 25.0537 64.3004C25.8278 64.434 26.6092 64.5312 27.3905 64.6319C27.3684 64.8245 27.2465 64.9306 26.7829 64.7419ZM27.0287 63.5857C27.1007 63.5215 27.1856 63.485 27.2984 63.5235C27.4167 63.5656 27.4627 63.65 27.4684 63.7433C27.3205 63.6957 27.1727 63.6424 27.0287 63.5857ZM26.4189 9.57307C26.3765 9.55472 26.3357 9.42643 26.3562 9.40269C26.6407 9.08387 27.012 9.18462 27.5291 9.1517C27.1247 9.54014 26.831 9.7636 26.4189 9.57307ZM27.0711 7.30447C27.0213 6.87375 27.1007 6.51643 27.5126 6.38455C27.7029 6.3241 27.6401 6.54756 27.6789 6.65209C27.8988 7.2532 27.2964 7.10656 27.0711 7.30447ZM32.1439 4.19836C32.1956 4.15626 32.3563 4.19098 32.4321 4.24226C32.5319 4.30649 32.5337 4.45474 32.4285 4.508C31.8263 4.80666 32.1089 5.12746 32.5653 5.5195C31.843 5.54703 31.6065 5.27391 31.5456 4.82861C31.4862 4.39447 31.9223 4.38529 32.1439 4.19836ZM33.3374 8.82551C32.9846 9.28916 32.6003 9.38991 32.2419 9.47052C31.3718 9.66843 30.5627 10.0698 29.6741 10.2035C29.5669 10.2183 29.4561 10.3025 29.4247 10.1393C29.4118 10.0714 29.4653 9.93399 29.5098 9.92301C30.7547 9.62435 31.8778 8.89693 33.3374 8.82551ZM28.185 6.87753C28.0852 6.74007 28.1757 6.61916 28.294 6.54396C28.6894 6.29297 28.3217 5.66254 28.8889 5.44807C28.9277 5.9648 29.0164 6.445 29.6832 6.04001C29.6832 6.03443 29.687 6.02903 29.687 6.02165C29.6926 6.02345 29.698 6.02723 29.7055 6.02903L29.6833 6.04181C30.0492 6.48531 30.4961 5.77427 30.8989 6.12619C30.9895 6.20499 31.2296 6.14634 31.2018 6.37537C31.1779 6.55853 31.0041 6.54036 30.8841 6.51643C30.0546 6.35342 29.336 6.84262 28.5436 6.8975C28.4179 6.90685 28.2829 7.01678 28.185 6.87753ZM29.9438 59.0833C29.8662 59.0356 29.7867 58.9899 29.7111 58.9366C29.2825 58.638 28.8372 58.3704 28.3885 58.1121C29.061 58.44 29.7095 58.8103 30.3466 59.197C30.2135 59.1583 30.0786 59.1198 29.9438 59.0833ZM35.8293 62.2736C35.9106 62.2736 35.9919 62.2736 36.0749 62.2718C36.3187 62.3707 36.5313 62.5064 36.7252 62.6694C36.3891 62.6768 36.0807 62.598 35.8293 62.2736ZM36.5646 10.9054C34.4864 10.9181 32.4339 11.2683 30.376 11.6164C30.9044 11.3562 31.4235 11.1107 31.9961 10.9586C32.7368 10.7625 33.4925 10.7992 34.2314 10.7039C35.155 10.5866 36.0713 10.5133 36.9894 10.5115C36.838 10.6213 36.6976 10.7569 36.5646 10.9054ZM38.2587 62.5118C38.3788 62.3781 38.5876 62.4092 38.776 62.4623C38.6041 62.4769 38.4323 62.4952 38.2587 62.5118ZM41.4415 5.03588C41.1774 4.93513 41.0554 4.80127 41.0407 4.59796C41.0111 4.19656 40.8245 4.0537 40.4681 4.27734C40.0413 4.54308 39.8474 4.14726 39.5646 4.00063C39.3466 3.88692 39.3412 3.68001 39.4502 3.5242C39.6183 3.28239 39.8086 3.49128 39.8991 3.60859C40.1688 3.95673 40.3776 4.06666 40.5272 3.54256C40.5936 3.30434 40.7581 3.27322 40.9594 3.30075C41.1718 3.33187 41.2643 3.47671 41.3235 3.67282C41.4546 4.10498 41.449 4.5447 41.4415 5.03588ZM42.247 7.70209C42.1935 7.64704 42.1103 7.60314 42.0918 7.53909C42.0475 7.39066 42.1471 7.29728 42.2673 7.22387C42.5721 7.03873 42.9176 7.05889 43.252 7.02038C43.2631 7.7388 42.5057 7.36673 42.247 7.70209ZM44.3106 8.43148C44.5471 8.64775 44.4473 9.01424 43.991 8.84008C43.7804 8.75948 43.5753 8.64217 43.2724 8.57254C43.6382 8.28665 44.063 8.20605 44.3106 8.43148ZM44.4566 61.5663C44.1074 61.6397 43.7565 61.6964 43.4369 61.6158C43.7048 61.5992 43.9689 61.5627 44.2257 61.4674C44.283 61.4454 44.4602 61.4253 44.5877 61.537C44.5453 61.5462 44.5008 61.5571 44.4566 61.5663ZM45.1086 7.24042C45.674 6.85378 45.5704 6.60459 44.8242 6.41208C45.2657 6.34245 45.5835 5.9612 45.7202 6.56789C45.8327 7.07724 45.6591 7.30088 45.1086 7.24042ZM48.3821 60.6683C48.2436 60.6903 48.105 60.7178 47.9664 60.7435C48.3803 60.1187 49.5625 59.7338 50.2092 60.082C49.6531 60.3531 49.0491 60.5566 48.3821 60.6683ZM50.9185 59.6824C51.1106 59.4551 51.3433 59.2866 51.6132 59.182C51.3896 59.36 51.1587 59.5268 50.9185 59.6824ZM57.907 16.2067C57.8867 16.1809 57.8664 16.1572 57.8461 16.1315C57.8682 16.1442 57.8885 16.159 57.9126 16.17C57.9107 16.1829 57.9088 16.1939 57.907 16.2067ZM61.1658 48.3943C61.116 48.4511 61.0661 48.506 61.0144 48.5629C61.2342 48.2367 61.4448 47.9069 61.6499 47.5733C61.4947 47.85 61.334 48.1232 61.1658 48.3943ZM63.8629 20.8558C64.1345 21.2626 64.3746 21.6933 64.5409 22.1733C64.8088 22.9502 65.2835 23.6191 65.6845 24.3394C65.0508 23.5679 64.4597 22.7451 64.0532 21.8307C63.8611 21.4018 63.7982 21.094 63.8629 20.8558ZM62.4054 22.9759C62.5938 22.9466 62.7877 22.8642 62.9798 22.7469C63.2256 22.5929 63.5969 22.5691 63.5488 22.8109C63.3529 23.7949 64.4337 24.0094 64.6664 24.7661C64.7865 25.1528 64.8161 25.2058 64.5132 25.3525C64.0588 25.5741 63.946 25.8179 64.4412 26.1331C64.5206 26.1826 64.5926 26.3017 64.5982 26.3933C64.6314 26.8202 64.7571 27.2821 64.8255 27.6943C64.2748 26.005 63.5212 24.4108 62.4054 22.9759ZM65.0968 40.4505C65.3406 39.4408 65.5273 38.4165 65.701 37.3885C65.7694 38.4586 65.4387 39.4646 65.0968 40.4505ZM65.7121 31.5465C65.7028 31.5044 65.6954 31.4603 65.6918 31.4147C65.7563 31.4621 65.7509 31.5042 65.7121 31.5465ZM67.7571 35.981C67.7091 34.7953 67.6057 33.6134 67.5206 32.4296C67.4762 31.8193 67.3378 31.2256 67.4375 30.5971C67.4522 30.5091 67.4671 30.4211 67.4838 30.3332C67.8643 32.2007 67.8901 34.099 67.7571 35.981Z" />
                                        </svg>
                                        <span>$28</span>
                                    </div>
                                </div>
                                <div class="item-content text-center">
                                    <div class="review-area">
                                        <ul>
                                            <li><i class=" bi bi-star-fill"></i></li>
                                            <li><i class=" bi bi-star-fill"></i></li>
                                            <li><i class=" bi bi-star-fill"></i></li>
                                            <li><i class=" bi bi-star-fill"></i></li>
                                            <li><i class=" bi bi-star-fill"></i></li>
                                        </ul>
                                        <span>Review(37)</span>
                                    </div>
                                    <h3><a href="shop-details.html">Beaf Biriyani</a></h3>
                                    <p>It is a long established fact that a reader will be distracted.</p>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="popular-item-warp">
                                <div class="item-img">
                                    <img class="img-fluid" src="{{ asset('client') }}/images/bg/populer3.png" alt="populer3">
                                    <div class="price-tag">
                                        <svg width="70" height="71" viewBox="0 0 70 71" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M69.5379 34.0184C69.2812 33.6483 69.1038 33.2214 69.0113 32.7669C68.7841 31.6455 68.5514 30.5259 68.3112 29.408C68.1986 28.8839 68.1745 28.303 67.9176 27.8559C67.7312 27.5279 67.5667 27.1907 67.4116 26.848C67.4522 26.8095 67.4615 26.7747 67.3672 26.7434C66.9182 25.7263 66.558 24.6727 65.9724 23.7179C65.3239 22.6605 64.8123 21.5263 64.1268 20.4855C64.2451 20.3957 64.4004 20.3187 64.5904 20.2473C64.6311 20.2325 64.6514 20.1667 64.6773 20.1264C64.4408 20.0897 64.2063 20.0494 63.9698 20.02C63.8996 20.0127 63.8348 19.9963 63.7739 19.9761C63.5098 19.6132 63.2381 19.2577 62.9629 18.906C62.9519 18.8711 62.9408 18.8382 62.9297 18.807C62.8318 18.5102 63.5319 18.5339 63.8533 18.3709C63.9826 18.3031 64.1822 18.3306 64.2264 18.0411C63.5078 17.8524 62.5472 18.402 62.0724 17.6654C61.6659 17.0314 61.0138 16.5514 60.8218 15.7817C61.3316 15.4134 61.9911 15.4537 62.6025 15.2025C62.3531 15.023 61.9837 15.089 61.9984 14.7682C62.0151 14.3467 62.5859 14.4329 62.717 14.1306C61.834 13.8227 60.9527 13.8704 60.0698 14.0793C59.8425 14.1344 59.7114 14.2405 59.39 14.0574C58.1911 13.3756 57.5666 12.2744 56.9385 11.1547C56.7593 10.8359 56.9755 10.6967 57.247 10.6783C58.0006 10.6306 58.7064 10.2715 59.4749 10.3375C59.824 10.3669 60.0807 10.244 60.3246 9.9912C60.0714 9.82999 59.8443 9.85932 59.6631 9.95827C58.8779 10.387 58.0449 10.2679 57.2154 10.2075C57.0177 10.1929 56.7092 10.2809 56.7038 9.98202C56.6963 9.74201 56.9938 9.69973 57.1933 9.63388C57.5775 9.5074 58.0394 9.67238 58.3904 9.34259C58.0929 9.17401 57.7347 9.4196 57.3761 9.08981C59.1368 8.46315 60.9083 8.21756 62.6854 7.75211C62.3843 7.57795 62.1386 7.6366 61.9115 7.67511C60.3228 7.94822 58.7377 8.23214 57.1509 8.5236C56.8035 8.58783 56.2993 8.73069 56.2273 8.37157C56.1515 8.01048 56.6873 8.08028 56.9827 7.9923C57.8566 7.73574 58.7192 7.44247 59.5875 7.16216C59.5708 7.09991 59.5543 7.03568 59.5358 6.97163C58.6472 7.12186 57.755 7.25572 56.8701 7.4297C56.3824 7.52685 55.8429 7.51948 55.4865 7.97575C55.1762 8.37517 54.8713 8.29637 54.5555 7.9556C53.6448 6.96965 52.5123 6.37412 51.2026 6.07005C50.8257 5.98387 50.7279 5.70356 51.0622 5.4416C51.5998 5.01645 52.1651 4.62801 52.7174 4.22481C52.3277 4.22121 52.0173 4.50692 51.6404 4.4722C50.9292 4.40617 50.4304 4.61703 50.2716 5.37557C50.2382 5.53497 49.9556 5.68341 49.9298 5.54415C49.806 4.86604 49.2611 5.34984 48.9489 5.16848C48.633 4.98532 47.9866 5.10803 48.0105 4.63898C48.029 4.25791 48.6109 4.17713 49.0117 4.18451C49.4976 4.19368 49.6841 4.00495 49.6269 3.41301C48.7697 4.04525 47.8295 4.1442 46.9224 4.08195C46.3313 4.04165 45.5775 4.09293 45.2265 3.32521C45.0898 3.02655 44.9291 3.15105 44.8329 3.44252C44.7499 3.69171 44.4727 3.75036 44.227 3.73939C44.0089 3.73021 43.911 3.54885 43.8519 3.36372C43.6246 2.62695 43.4935 2.57207 42.6604 2.86336C42.6622 2.99722 42.8044 3.0082 42.8545 3.07963C43.0355 3.33619 43.5675 3.38567 43.4623 3.74299C43.3458 4.13521 42.9118 3.79804 42.6236 3.87864C42.3778 3.94827 42.0988 3.8934 41.8514 3.8934C41.7923 3.27034 42.2042 2.88369 42.4592 2.42184C42.6551 2.06812 42.9802 1.85186 43.3144 1.80238C43.695 1.74553 43.8058 2.15053 43.8575 2.50784C44.301 2.2 43.8999 1.93246 43.9369 1.65755C44.0238 1.01992 43.6986 0.787102 43.1131 1.03449C42.91 1.12067 42.9856 0.994189 42.945 0.933736C42.5995 0.426185 40.8593 0.176998 40.4639 0.688147C40.0649 1.20307 39.5643 1.1921 39.0599 1.2691C38.6516 1.33136 38.3044 1.40296 38.3227 1.91051C38.3302 2.13595 38.3338 2.37057 38.0641 2.42904C37.8203 2.48212 37.5301 2.43084 37.4378 2.18903C37.2383 1.66133 36.9833 1.93804 36.684 2.06075C36.3441 2.2018 36.2295 2.49687 36.0227 2.83224C35.7954 1.97097 35.1673 2.46574 34.7019 2.40889C34.2124 2.34844 34.4394 2.87794 34.2401 3.10517C33.6933 2.1651 32.7697 2.29158 31.942 2.26405C31.6391 2.25488 31.2641 2.39234 30.9797 2.49129C30.2258 2.75523 30.9575 3.18218 31.0036 3.5816C30.5897 3.5852 30.2794 3.02997 29.8232 3.35994C29.245 3.77591 28.761 3.5359 28.3767 3.08682C28.107 2.7716 28.0016 2.77538 27.828 3.14727C27.4568 3.94252 26.8065 3.88944 26.1193 3.63845C25.9789 3.58718 25.82 3.39106 25.7277 3.42399C24.8317 3.74838 23.9117 4.01592 23.0748 4.48317C22.8828 4.5895 22.8625 4.78363 22.8494 4.97237C22.8365 5.21616 22.8532 5.44879 22.5169 5.51842C22.1935 5.58445 22.0384 5.4891 21.9037 5.18683C21.8317 5.02742 21.5507 4.60048 21.4252 5.21058C21.3828 5.41209 21.2275 5.34246 21.1038 5.35164C20.3464 5.40669 19.7164 5.71453 19.0827 6.14328C18.1183 6.79387 16.9822 7.18051 16.0918 7.97035C15.9033 8.13714 15.6004 8.17384 15.3565 8.28377C15.0868 8.40468 14.7783 8.48906 14.5677 8.67959C13.0806 10.0338 11.4862 11.2927 10.4573 13.0721C10.328 13.2994 10.2005 13.5613 9.99731 13.7044C9.43395 14.102 8.94804 14.6279 8.8501 15.2473C8.77446 15.7256 8.65058 15.8758 8.2258 15.8758C8.27386 16.1342 8.68758 16.0206 8.60451 16.3285C8.58783 16.3889 8.46775 16.4879 8.44744 16.4769C7.84889 16.1324 8.08904 16.6547 8.0816 16.847C8.07416 17.0908 8.04097 17.3051 7.93759 17.5379C7.43136 18.6813 6.91226 19.8266 6.92513 21.1148C6.92894 21.4319 6.4911 21.6645 6.69243 21.9596C7.06552 22.5112 6.76806 22.7915 6.39134 23.1562C6.00899 23.5263 5.54702 23.6125 4.92635 23.6767C5.59328 24.034 5.5619 24.5984 5.31994 24.9009C4.80265 25.5459 5.35313 26.649 4.33524 27.032C4.31311 27.0412 4.29642 27.1585 4.31855 27.1749C4.83022 27.5781 4.25761 27.6459 4.10054 27.754C3.74395 27.9996 3.64618 28.3111 3.6645 28.7107C3.683 29.0753 3.77895 29.4492 3.67738 29.7002C3.10296 29.9366 3.04002 29.0167 2.46179 29.31C2.03138 29.528 1.53821 29.7002 1.31094 30.1547C1.04486 30.6862 1.02654 31.1919 1.7136 31.3274C1.75242 31.8607 1.28682 31.1917 1.21663 31.6426C1.06137 32.6395 0.867474 33.6107 0.509071 34.5873C0.176424 35.4963 0.0989759 36.5646 0.0675975 37.6017C-0.00259577 39.8996 0.5497 42.0876 0.994983 44.3013C1.20556 45.3495 2.00363 46.1869 2.03881 47.2937C2.04262 47.4531 2.18664 47.5942 2.37509 47.4164C2.47666 47.3211 2.61342 47.1818 2.67255 47.3907C2.82218 47.9038 3.53536 48.241 3.04945 48.9796C3.29141 48.8605 3.44286 48.7194 3.53899 48.7505C3.75519 48.8202 3.70531 49.0474 3.70531 49.2325C3.70531 49.3865 3.42454 49.5001 3.58343 49.6449C4.22623 50.2404 4.38149 51.2942 5.31813 51.5799C5.75035 51.71 5.23868 52.2469 5.65059 52.1956C6.26383 52.1224 6.28614 52.6373 6.29158 52.9011C6.31552 53.9345 7.11377 54.5009 7.5625 55.2778C7.87283 55.8129 8.54901 55.9521 8.72458 56.5898C8.91304 57.2715 9.17712 57.8744 9.82011 58.3636C10.6606 59.0012 11.274 59.9267 12.2605 60.4085C14.5143 61.5098 16.6388 62.798 18.4546 64.5481C18.5544 64.6435 18.6782 64.8468 18.8573 64.7404C19.3856 64.4252 19.491 64.7331 19.5501 65.1638C19.5723 65.3178 19.6147 65.4754 19.7885 65.554C21.0519 66.124 22.1937 67.0072 23.6402 67.108C24.0927 67.1391 24.5398 67.0384 24.9019 67.4378C24.9887 67.5331 25.2253 67.6614 25.4135 67.4799C25.746 67.1573 26.0342 67.3791 26.3612 67.5238C27.1611 67.8737 27.9962 68.1377 28.8477 67.7253C29.1396 67.586 29.4131 67.6593 29.4684 67.837C29.6476 68.3996 30.1335 68.3665 30.538 68.5754C31.9863 69.3231 33.1852 68.9327 34.3694 67.9559C34.4286 68.1521 34.4951 68.3206 34.362 68.491C34.0018 68.9509 34 68.9509 34.5967 69.224C34.7371 69.2881 34.798 69.4 34.8073 69.5374C34.8294 69.8526 35.0345 69.8399 35.2858 69.9241C35.9231 70.133 36.59 69.5283 37.194 70.034C37.2254 70.0597 37.3455 70.0029 37.4102 69.9626C38.0975 69.5247 38.5261 68.7422 39.4183 68.5644C39.8431 68.48 39.3943 68.1191 39.5476 67.8021C39.8099 68.568 40.4508 67.6335 40.7742 68.176C41.1381 67.7636 41.6369 67.4723 41.8458 66.8437C42.267 68.11 42.6439 68.4214 43.477 68.2071C46.1316 67.5272 48.6384 66.5376 50.8313 64.8426C51.8289 64.0729 52.8818 63.3749 53.9385 62.6876C54.5777 62.2716 55.0119 62.4861 55.1523 63.2025C55.2798 63.8549 55.9356 64.192 56.5044 63.8475C56.8665 63.6276 57.2064 63.3417 57.4835 63.0247C57.6738 62.8067 58.0728 62.5611 57.8345 62.2129C57.5758 61.8337 57.1509 61.9729 56.8073 62.1323C56.1553 62.4366 55.5364 62.1928 54.7327 62.1507C55.2758 61.8117 55.6933 61.6138 56.0352 61.3261C57.5316 60.0708 59.1277 58.8999 60.3137 57.3386C61.182 56.197 62.2073 55.8433 63.5466 56.1751C63.9086 56.263 64.0305 56.1585 64.1377 55.7792C64.3447 55.0516 63.977 54.4379 63.8495 53.7836C63.7922 53.4903 63.6721 53.3035 63.8976 52.9993C64.6401 51.9988 65.3348 50.9651 65.913 49.8585C66.2049 49.3032 66.5558 48.8414 67.197 48.607C67.5554 48.4769 67.9249 48.0114 67.766 47.709C67.5037 47.2124 67.5942 46.822 67.7143 46.3493C67.7549 46.1917 67.7513 46.0231 67.7421 45.8546C68.226 44.9676 68.4828 44.0165 68.7415 43.0637C68.941 42.3271 69.2919 41.5775 69.0924 40.7895C68.9705 40.3057 69.1182 39.9099 69.2513 39.4737C69.4434 38.8453 69.8 38.2258 69.6115 37.5387C69.4379 36.8973 69.5727 36.2908 69.739 35.6879C69.9019 35.096 69.9112 34.5591 69.5379 34.0184ZM10.7147 27.5644C10.4856 27.7972 10.3673 27.4563 10.2714 27.2841C10.1901 27.1412 10.1217 26.9561 9.92766 27.0844C9.75227 27.1999 9.90372 27.3152 10.0053 27.3978C10.2104 27.5646 10.2066 27.788 10.1161 27.9823C9.9536 28.3251 9.76878 27.8962 9.5767 27.9696C9.42162 28.03 9.26273 28.019 9.10185 27.9879C9.31243 27.2603 9.5397 26.5385 9.79653 25.8237C9.80578 25.8311 9.81322 25.8383 9.82428 25.8474C9.88522 25.7704 9.89266 25.6715 9.88885 25.5689C9.95161 25.4003 10.0182 25.2317 10.0847 25.0632C10.1254 25.0558 10.1716 25.0504 10.2288 25.0522C10.4966 25.0578 10.5908 25.2557 10.5872 25.4975C10.5743 26.1956 11.4112 26.8607 10.7147 27.5644ZM10.5208 16.3661C10.5393 16.1021 10.6483 15.9172 10.953 15.908C11.3132 15.897 11.2412 16.1334 11.2042 16.335C11.1489 16.63 11.2836 16.8792 11.3649 17.1705C10.9363 17.0167 10.4763 16.9233 10.5208 16.3661ZM11.8858 25.7411C11.8488 25.8309 11.8138 25.9207 11.7786 26.0104C11.6825 25.8033 11.6863 25.5486 11.8064 25.2389C11.921 24.9439 11.8693 24.8248 11.7066 24.6305C11.5514 24.4453 11.52 24.1375 11.7195 24.0607C12.4307 23.7857 11.8969 23.3827 11.8414 22.9173C12.244 23.282 12.6302 23.1884 12.9423 22.9759C13.2434 22.7688 13.7034 22.5967 13.5095 22.0578C13.4892 22.0027 13.5355 21.8727 13.5833 21.8525C14.8247 21.3412 13.6278 21.0938 13.4283 20.6651C14.06 20.61 14.1191 20.1759 14.0507 19.6939C13.9621 19.0764 14.4719 19.0397 14.8174 19.0139C15.2293 18.9828 15.0964 19.384 15.0761 19.6242C15.0483 19.9724 15.1038 20.3095 15.1684 20.6467C13.8146 22.1641 12.6563 23.8188 11.8858 25.7411ZM13.105 53.8203C13.0274 53.7506 12.959 53.6774 12.8888 53.6022C12.9683 53.6774 13.0439 53.7542 13.1197 53.8331C13.1143 53.8277 13.1089 53.8241 13.105 53.8203ZM15.3663 22.4023C15.0855 22.8788 14.7124 23.359 14.3206 23.8023C14.559 23.3295 14.8138 22.864 15.0891 22.4077C15.176 22.2649 15.2629 22.1201 15.346 21.9752C15.3699 22.0632 15.4198 22.1328 15.5234 22.1512C15.4697 22.2338 15.4162 22.3144 15.3663 22.4023ZM16.6871 10.0808C16.8367 10.002 16.9403 10.09 17.0103 10.2145C17.2098 10.57 17.6163 10.4894 18.0614 10.7037C17.6346 10.8814 17.5682 11.4532 17.0324 11.2645C16.6537 11.1326 16.6998 10.7679 16.5928 10.4838C16.5282 10.3098 16.5117 10.1742 16.6871 10.0808ZM16.4803 30.6504C16.3214 29.8441 16.1571 29.0433 16.4841 28.2114C16.5986 27.9219 16.7796 27.6781 16.9497 27.4289C17.068 27.5809 17.1973 27.7404 17.356 27.8614C17.2027 28.3287 17.0605 28.7997 16.8942 29.2596C16.7279 29.7159 16.5966 30.1813 16.4803 30.6504ZM17.6237 58.0937C17.5276 58.0075 17.4409 57.9159 17.3558 57.8206C17.7178 58.0881 18.0855 58.3521 18.4588 58.6066C18.1613 58.4748 17.8731 58.3209 17.6237 58.0937ZM20.9268 9.63352C20.7255 9.7197 20.5241 9.80948 20.3264 9.90286C20.186 9.83503 20.1418 9.71592 20.1768 9.54554C20.2192 9.3219 20.1768 9.01964 20.5205 8.99769C20.8586 8.97754 20.9047 9.26163 20.9288 9.49246C20.9324 9.54194 20.9324 9.58782 20.9268 9.63352ZM25.7337 62.4825C25.9165 62.5467 26.0884 62.6253 26.2343 62.7426C26.3803 62.8599 26.5317 62.9553 26.6868 63.0377C26.6092 63.1019 26.5335 63.1935 26.4633 63.3218C26.4595 63.3272 26.4577 63.3292 26.4541 63.3346C26.2713 63.2502 26.0903 63.1642 25.9072 63.0798C25.8574 63.0377 25.8093 62.9974 25.7649 62.9661C25.6486 62.7885 25.6615 62.6365 25.7337 62.4825ZM25.7576 63.0102C25.5452 62.913 25.3346 62.814 25.1259 62.7151C25.3364 62.7811 25.5434 62.8837 25.754 62.9661C25.754 62.9826 25.7558 62.9956 25.7576 63.0102ZM25.68 60.223C25.4491 60.333 25.1997 60.4082 24.9521 60.4832C24.9187 60.4228 24.8578 60.3751 24.8025 60.3348C24.5346 60.146 24.289 59.9334 24.0525 59.7099C24.5957 59.8749 25.1387 60.0471 25.68 60.223ZM23.3395 60.4613C23.14 60.2761 22.9461 60.0453 22.7261 59.8693C23.2009 60.1424 23.683 60.4044 24.1707 60.6574C24.2095 60.6775 24.2465 60.6941 24.2854 60.7086C24.2817 60.7104 24.2761 60.7122 24.2707 60.7142C24.1506 60.7673 24.0416 60.7856 23.938 60.7803C23.7367 60.6775 23.5372 60.573 23.3395 60.4613ZM26.7829 64.7419C26.8143 65.1084 26.5299 65.2165 26.334 65.0535C25.7188 64.5477 24.8728 64.5972 24.2409 64.0969C24.5106 64.1739 24.7785 64.2527 25.0537 64.3004C25.8278 64.434 26.6092 64.5312 27.3905 64.6319C27.3684 64.8245 27.2465 64.9306 26.7829 64.7419ZM27.0287 63.5857C27.1007 63.5215 27.1856 63.485 27.2984 63.5235C27.4167 63.5656 27.4627 63.65 27.4684 63.7433C27.3205 63.6957 27.1727 63.6424 27.0287 63.5857ZM26.4189 9.57307C26.3765 9.55472 26.3357 9.42643 26.3562 9.40269C26.6407 9.08387 27.012 9.18462 27.5291 9.1517C27.1247 9.54014 26.831 9.7636 26.4189 9.57307ZM27.0711 7.30447C27.0213 6.87375 27.1007 6.51643 27.5126 6.38455C27.7029 6.3241 27.6401 6.54756 27.6789 6.65209C27.8988 7.2532 27.2964 7.10656 27.0711 7.30447ZM32.1439 4.19836C32.1956 4.15626 32.3563 4.19098 32.4321 4.24226C32.5319 4.30649 32.5337 4.45474 32.4285 4.508C31.8263 4.80666 32.1089 5.12746 32.5653 5.5195C31.843 5.54703 31.6065 5.27391 31.5456 4.82861C31.4862 4.39447 31.9223 4.38529 32.1439 4.19836ZM33.3374 8.82551C32.9846 9.28916 32.6003 9.38991 32.2419 9.47052C31.3718 9.66843 30.5627 10.0698 29.6741 10.2035C29.5669 10.2183 29.4561 10.3025 29.4247 10.1393C29.4118 10.0714 29.4653 9.93399 29.5098 9.92301C30.7547 9.62435 31.8778 8.89693 33.3374 8.82551ZM28.185 6.87753C28.0852 6.74007 28.1757 6.61916 28.294 6.54396C28.6894 6.29297 28.3217 5.66254 28.8889 5.44807C28.9277 5.9648 29.0164 6.445 29.6832 6.04001C29.6832 6.03443 29.687 6.02903 29.687 6.02165C29.6926 6.02345 29.698 6.02723 29.7055 6.02903L29.6833 6.04181C30.0492 6.48531 30.4961 5.77427 30.8989 6.12619C30.9895 6.20499 31.2296 6.14634 31.2018 6.37537C31.1779 6.55853 31.0041 6.54036 30.8841 6.51643C30.0546 6.35342 29.336 6.84262 28.5436 6.8975C28.4179 6.90685 28.2829 7.01678 28.185 6.87753ZM29.9438 59.0833C29.8662 59.0356 29.7867 58.9899 29.7111 58.9366C29.2825 58.638 28.8372 58.3704 28.3885 58.1121C29.061 58.44 29.7095 58.8103 30.3466 59.197C30.2135 59.1583 30.0786 59.1198 29.9438 59.0833ZM35.8293 62.2736C35.9106 62.2736 35.9919 62.2736 36.0749 62.2718C36.3187 62.3707 36.5313 62.5064 36.7252 62.6694C36.3891 62.6768 36.0807 62.598 35.8293 62.2736ZM36.5646 10.9054C34.4864 10.9181 32.4339 11.2683 30.376 11.6164C30.9044 11.3562 31.4235 11.1107 31.9961 10.9586C32.7368 10.7625 33.4925 10.7992 34.2314 10.7039C35.155 10.5866 36.0713 10.5133 36.9894 10.5115C36.838 10.6213 36.6976 10.7569 36.5646 10.9054ZM38.2587 62.5118C38.3788 62.3781 38.5876 62.4092 38.776 62.4623C38.6041 62.4769 38.4323 62.4952 38.2587 62.5118ZM41.4415 5.03588C41.1774 4.93513 41.0554 4.80127 41.0407 4.59796C41.0111 4.19656 40.8245 4.0537 40.4681 4.27734C40.0413 4.54308 39.8474 4.14726 39.5646 4.00063C39.3466 3.88692 39.3412 3.68001 39.4502 3.5242C39.6183 3.28239 39.8086 3.49128 39.8991 3.60859C40.1688 3.95673 40.3776 4.06666 40.5272 3.54256C40.5936 3.30434 40.7581 3.27322 40.9594 3.30075C41.1718 3.33187 41.2643 3.47671 41.3235 3.67282C41.4546 4.10498 41.449 4.5447 41.4415 5.03588ZM42.247 7.70209C42.1935 7.64704 42.1103 7.60314 42.0918 7.53909C42.0475 7.39066 42.1471 7.29728 42.2673 7.22387C42.5721 7.03873 42.9176 7.05889 43.252 7.02038C43.2631 7.7388 42.5057 7.36673 42.247 7.70209ZM44.3106 8.43148C44.5471 8.64775 44.4473 9.01424 43.991 8.84008C43.7804 8.75948 43.5753 8.64217 43.2724 8.57254C43.6382 8.28665 44.063 8.20605 44.3106 8.43148ZM44.4566 61.5663C44.1074 61.6397 43.7565 61.6964 43.4369 61.6158C43.7048 61.5992 43.9689 61.5627 44.2257 61.4674C44.283 61.4454 44.4602 61.4253 44.5877 61.537C44.5453 61.5462 44.5008 61.5571 44.4566 61.5663ZM45.1086 7.24042C45.674 6.85378 45.5704 6.60459 44.8242 6.41208C45.2657 6.34245 45.5835 5.9612 45.7202 6.56789C45.8327 7.07724 45.6591 7.30088 45.1086 7.24042ZM48.3821 60.6683C48.2436 60.6903 48.105 60.7178 47.9664 60.7435C48.3803 60.1187 49.5625 59.7338 50.2092 60.082C49.6531 60.3531 49.0491 60.5566 48.3821 60.6683ZM50.9185 59.6824C51.1106 59.4551 51.3433 59.2866 51.6132 59.182C51.3896 59.36 51.1587 59.5268 50.9185 59.6824ZM57.907 16.2067C57.8867 16.1809 57.8664 16.1572 57.8461 16.1315C57.8682 16.1442 57.8885 16.159 57.9126 16.17C57.9107 16.1829 57.9088 16.1939 57.907 16.2067ZM61.1658 48.3943C61.116 48.4511 61.0661 48.506 61.0144 48.5629C61.2342 48.2367 61.4448 47.9069 61.6499 47.5733C61.4947 47.85 61.334 48.1232 61.1658 48.3943ZM63.8629 20.8558C64.1345 21.2626 64.3746 21.6933 64.5409 22.1733C64.8088 22.9502 65.2835 23.6191 65.6845 24.3394C65.0508 23.5679 64.4597 22.7451 64.0532 21.8307C63.8611 21.4018 63.7982 21.094 63.8629 20.8558ZM62.4054 22.9759C62.5938 22.9466 62.7877 22.8642 62.9798 22.7469C63.2256 22.5929 63.5969 22.5691 63.5488 22.8109C63.3529 23.7949 64.4337 24.0094 64.6664 24.7661C64.7865 25.1528 64.8161 25.2058 64.5132 25.3525C64.0588 25.5741 63.946 25.8179 64.4412 26.1331C64.5206 26.1826 64.5926 26.3017 64.5982 26.3933C64.6314 26.8202 64.7571 27.2821 64.8255 27.6943C64.2748 26.005 63.5212 24.4108 62.4054 22.9759ZM65.0968 40.4505C65.3406 39.4408 65.5273 38.4165 65.701 37.3885C65.7694 38.4586 65.4387 39.4646 65.0968 40.4505ZM65.7121 31.5465C65.7028 31.5044 65.6954 31.4603 65.6918 31.4147C65.7563 31.4621 65.7509 31.5042 65.7121 31.5465ZM67.7571 35.981C67.7091 34.7953 67.6057 33.6134 67.5206 32.4296C67.4762 31.8193 67.3378 31.2256 67.4375 30.5971C67.4522 30.5091 67.4671 30.4211 67.4838 30.3332C67.8643 32.2007 67.8901 34.099 67.7571 35.981Z" />
                                        </svg>
                                        <span>$21</span>
                                    </div>
                                </div>
                                <div class="item-content text-center">
                                    <div class="review-area">
                                        <ul>
                                            <li><i class=" bi bi-star-fill"></i></li>
                                            <li><i class=" bi bi-star-fill"></i></li>
                                            <li><i class=" bi bi-star-fill"></i></li>
                                            <li><i class=" bi bi-star-fill"></i></li>
                                        </ul>
                                        <span>Review(54)</span>
                                    </div>
                                    <h3><a href="shop-details.html">Thai Soup</a></h3>
                                    <p>It is a long established fact that a reader will be distracted.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="row">
                <div class="slider-pagination">
                    <div class="swiper-pagination-xyz"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- ========== Populer Items end============= -->

    <!-- ========== Menu List end============= -->
    <div class="menu-list-area1 mb-120">
        <div class="container">
            <div class="row d-flex justify-content-center mb-40">
                <div class="col-lg-8">
                    <div class="section-title text-center">
                        <span><img class="left-vec"  src="{{ asset('client') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec">Menu List<img class="right-vec" src="{{ asset('client') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec"></span>
                        <h2>Our Menu List</h2>
                    </div>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="menu-wrapper1">
                        <img class="menu-top-left" src="{{ asset('client') }}/images/icon/menu-top-left.svg" alt="menu-top-left">
                        <img class="menu-top-right" src="{{ asset('client') }}/images/icon/menu-top-right.svg" alt="menu-top-right">
                        <img class="menu-btm-right" src="{{ asset('client') }}/images/icon/menu-btm-right.svg" alt="menu-btm-right">
                        <img class="menu-btm-left" src="{{ asset('client') }}/images/icon/menu-btm-left.svg" alt="menu-btm-left">
                        <div class="section-title text-center pt-40">
                            <span><img class="left-vec"  src="{{ asset('client') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec">Welcome to Restho<img class="right-vec" src="{{ asset('client') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec"></span>
                            <h2>Indian Menu</h2>
                        </div>
                        <div class="menu-list">
                            <ul>
                                <li>
                                    <div class="sl">
                                        <span>01.</span>
                                    </div>
                                    <div class="menu-content">
                                        <div class="menu-title">
                                            <h4>Paneer Butter</h4>
                                            <span class="dot"><img src="{{ asset('client') }}/images/icon/dot.svg" alt=""></span>
                                            <span class="price">$10</span>
                                        </div>
                                        <p>To much delicious food in our restaurant.Visit us & taste it early.</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="sl">
                                        <span>02.</span>
                                    </div>
                                    <div class="menu-content">
                                        <div class="menu-title">
                                            <h4>Veg Biriyani</h4>
                                            <span class="dot"><img src="{{ asset('client') }}/images/icon/dot.svg" alt=""></span>
                                            <span class="price">$15</span>
                                        </div>
                                        <p>To much delicious food in our restaurant.Visit us & taste it early.</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="sl">
                                        <span>03.</span>
                                    </div>
                                    <div class="menu-content">
                                        <div class="menu-title">
                                            <h4>Fried Rice</h4>
                                            <span class="dot"><img src="{{ asset('client') }}/images/icon/dot.svg" alt=""></span>
                                            <span class="price">$22</span>
                                        </div>
                                        <p>To much delicious food in our restaurant.Visit us & taste it early.</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="sl">
                                        <span>04.</span>
                                    </div>
                                    <div class="menu-content">
                                        <div class="menu-title">
                                            <h4>Indian Sambar</h4>
                                            <span class="dot"><img src="{{ asset('client') }}/images/icon/dot.svg" alt=""></span>
                                            <span class="price">$18</span>
                                        </div>
                                        <p>To much delicious food in our restaurant.Visit us & taste it early.</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="sl">
                                        <span>05.</span>
                                    </div>
                                    <div class="menu-content">
                                        <div class="menu-title">
                                            <h4>Indian Rasam</h4>
                                            <span class="dot"><img src="{{ asset('client') }}/images/icon/dot.svg" alt=""></span>
                                            <span class="price">$10</span>
                                        </div>
                                        <p>To much delicious food in our restaurant.Visit us & taste it early.</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="sl">
                                        <span>06.</span>
                                    </div>
                                    <div class="menu-content">
                                        <div class="menu-title">
                                            <h4>Chicken Tikka</h4>
                                            <span class="dot"><img src="{{ asset('client') }}/images/icon/dot.svg" alt=""></span>
                                            <span class="price">$18</span>
                                        </div>
                                        <p>To much delicious food in our restaurant.Visit us & taste it early.</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="sl">
                                        <span>07.</span>
                                    </div>
                                    <div class="menu-content">
                                        <div class="menu-title">
                                            <h4>Vegetarian Burger</h4>
                                            <span class="dot"><img src="{{ asset('client') }}/images/icon/dot.svg" alt=""></span>
                                            <span class="price">$18</span>
                                        </div>
                                        <p>To much delicious food in our restaurant.Visit us & taste it early.</p>
                                    </div>
                                </li>
                            </ul>
                            <div class="notice-location">
                                <h4><span>N.B:</span> All food are available in restauarnt & don’t waste your food.</h4>
                                <p>Address: Mirpur DOHS, House No-167/170, Avenue-01.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="menu-wrapper1">
                        <img class="menu-top-left" src="{{ asset('client') }}/images/icon/menu-top-left.svg" alt="menu-top-left">
                        <img class="menu-top-right" src="{{ asset('client') }}/images/icon/menu-top-right.svg" alt="menu-top-right">
                        <img class="menu-btm-right" src="{{ asset('client') }}/images/icon/menu-btm-right.svg" alt="menu-btm-right">
                        <img class="menu-btm-left" src="{{ asset('client') }}/images/icon/menu-btm-left.svg" alt="menu-btm-left">
                        <div class="section-title text-center pt-40">
                            <span><img class="left-vec"  src="{{ asset('client') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec">Welcome to Restho<img class="right-vec" src="{{ asset('client') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec"></span>
                            <h2>Italian Menu</h2>
                        </div>
                        <div class="menu-list">
                            <ul>
                                <li>
                                    <div class="sl">
                                        <span>01.</span>
                                    </div>
                                    <div class="menu-content">
                                        <div class="menu-title">
                                            <h4>Grey Butter</h4>
                                            <span class="dot"><img src="{{ asset('client') }}/images/icon/dot.svg" alt=""></span>
                                            <span class="price">$10</span>
                                        </div>
                                        <p>To much delicious food in our restaurant.Visit us & taste it early.</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="sl">
                                        <span>02.</span>
                                    </div>
                                    <div class="menu-content">
                                        <div class="menu-title">
                                            <h4> Sauerkraut</h4>
                                            <span class="dot"><img src="{{ asset('client') }}/images/icon/dot.svg" alt=""></span>
                                            <span class="price">$12</span>
                                        </div>
                                        <p>To much delicious food in our restaurant.Visit us & taste it early.</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="sl">
                                        <span>03.</span>
                                    </div>
                                    <div class="menu-content">
                                        <div class="menu-title">
                                            <h4>Salt Beef</h4>
                                            <span class="dot"><img src="{{ asset('client') }}/images/icon/dot.svg" alt=""></span>
                                            <span class="price">$25</span>
                                        </div>
                                        <p>To much delicious food in our restaurant.Visit us & taste it early.</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="sl">
                                        <span>04.</span>
                                    </div>
                                    <div class="menu-content">
                                        <div class="menu-title">
                                            <h4>Italian Sambar</h4>
                                            <span class="dot"><img src="{{ asset('client') }}/images/icon/dot.svg" alt=""></span>
                                            <span class="price">$18</span>
                                        </div>
                                        <p>To much delicious food in our restaurant.Visit us & taste it early.</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="sl">
                                        <span>05.</span>
                                    </div>
                                    <div class="menu-content">
                                        <div class="menu-title">
                                            <h4>Italian Rasam</h4>
                                            <span class="dot"><img src="{{ asset('client') }}/images/icon/dot.svg" alt=""></span>
                                            <span class="price">$10</span>
                                        </div>
                                        <p>To much delicious food in our restaurant.Visit us & taste it early.</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="sl">
                                        <span>06.</span>
                                    </div>
                                    <div class="menu-content">
                                        <div class="menu-title">
                                            <h4>Chicken Tikka</h4>
                                            <span class="dot"><img src="{{ asset('client') }}/images/icon/dot.svg" alt=""></span>
                                            <span class="price">$18</span>
                                        </div>
                                        <p>To much delicious food in our restaurant.Visit us & taste it early.</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="sl">
                                        <span>07.</span>
                                    </div>
                                    <div class="menu-content">
                                        <div class="menu-title">
                                            <h4>Barley Soup</h4>
                                            <span class="dot"><img src="{{ asset('client') }}/images/icon/dot.svg" alt=""></span>
                                            <span class="price">$20</span>
                                        </div>
                                        <p>To much delicious food in our restaurant.Visit us & taste it early.</p>
                                    </div>
                                </li>
                            </ul>
                            <div class="notice-location">
                                <h4><span>N.B:</span> All food are available in restauarnt & don’t waste your food.</h4>
                                <p>Address: Mirpur DOHS, House No-167/170, Avenue-01.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <!-- ========== Menu List end============= -->
    <!-- ========== Best offer Start============= -->
    <div class="best-offer-area1 mb-120">
        <div class="container">
            <div class="row d-flex justify-content-center mb-40">
                <div class="col-lg-8">
                    <div class="section-title text-center">
                        <span><img class="left-vec"  src="{{ asset('client') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec">Best Offer<img class="right-vec" src="{{ asset('client') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec"></span>
                        <h2>Choose Your Best Offer</h2>
                    </div>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-6 col-md-6">
                    <div class="best-offer-wrap clearfix">
                        <div class="best-offer-img">
                            <img class="img-fluid" src="{{ asset('client') }}/images/bg/best-offer-img1.png" alt="best-offer-img1">
                            <div class="price-tag">
                                <span>$55</span>
                            </div>
                        </div>
                        <div class="best-offer-content">
                            <h3>Buy One Get One Free</h3>
                            <p>If you are going to use a passage of Lorem Ipsum need. </p>
                            <a class="primary-btn3 btn-sm">Limited Offer</a>
                            <ol class="features">
                                <li>Prawn with Noodls.</li>
                                <li>Special Drinks.</li>
                            </ol>
                        </div>
                        
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="best-offer-wrap clearfix">
                        <div class="best-offer-img">
                            <img class="img-fluid" src="{{ asset('client') }}/images/bg/best-offer-img2.png" alt="best-offer-img1">
                            <div class="price-tag">
                                <span>$55</span>
                            </div>
                        </div>
                        <div class="best-offer-content">
                            <h3>Buy One Get One Free</h3>
                            <p>If you are going to use a passage of Lorem Ipsum need. </p>
                            <a class="primary-btn3 btn-sm">Limited Offer</a>
                            <ol class="features">
                                <li>Fried Chicken.</li>
                                <li>Watermelon Juice.</li>
                            </ol>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ========== Best offer end============= -->

    <!-- ========== Testimonial Start============= -->
    <div class="testimonial-area1">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7 position-relative order-lg-1 order-2">
                    <div class="swiper testimonial-img-slider">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="testimonial-img">
                                    <img src="{{ asset('client') }}/images/bg/testi-autho-1.png" alt="testi-autho-1">
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="testimonial-img">
                                    <img src="{{ asset('client') }}/images/bg/testi-autho-2.png" alt="testi-autho-2">
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="testimonial-img">
                                    <img src="{{ asset('client') }}/images/bg/testi-autho-3.png" alt="testi-autho-3">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper testimonial-content-slider">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="testimonial-content">
                                    <div class="quoat">
                                        <img src="{{ asset('client') }}/images/icon/quate-icon.svg" alt="quate-icon">
                                    </div>
                                    <div class="author-name-review">
                                        <div class="author-name">
                                            <h4>Jonathon Smith</h4>
                                            <span>Guest</span>
                                        </div>
                                        <div class="review">
                                            <ul>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <p>If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything  hidden in the middle of text. All the Lorem Ipsum generators,
                                        to use a passage of Lorem Ipsum.</p>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="testimonial-content">
                                    <div class="quoat">
                                        <img src="{{ asset('client') }}/images/icon/quate-icon.svg" alt="quate-icon">
                                    </div>
                                    <div class="author-name-review">
                                        <div class="author-name">
                                            <h4>David Von</h4>
                                            <span>Guest</span>
                                        </div>
                                        <div class="review">
                                            <ul>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <p>All the Lorem Ipsum generators,If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything  hidden in the middle of text. 
                                        to use a passage of Lorem Ipsum.</p>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="testimonial-content">
                                    <div class="quoat">
                                        <img src="{{ asset('client') }}/images/icon/quate-icon.svg" alt="quate-icon">
                                    </div>
                                    <div class="author-name-review">
                                        <div class="author-name">
                                            <h4>Cristrofar Henry</h4>
                                            <span>Guest</span>
                                        </div>
                                        <div class="review">
                                            <ul>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <p>If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything  hidden in the middle of text. All the Lorem Ipsum generators,
                                        to use a passage of Lorem Ipsum.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 order-lg-2 order-1">
                    <div class="section-title">
                        <span><img class="left-vec"  src="{{ asset('client') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec">Testimonials<img class="right-vec" src="{{ asset('client') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec"></span>
                        <h2>Customer Feedback</h2>
                        <p>It is a long established fact that a reader will be distracted.Various versions have evolved over.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="testimonial-video-area mb-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="testi-video-wrap">
                        <img class="img-fluid" src="{{ asset('client') }}/images/bg/testi-video-bg.png" alt="">
                        <div class="video-icon">
                            <a class="gallery2-img" data-fancybox="gallery" href="https://www.youtube.com/watch?v=xwlqHOVklyk&amp;ab_channel=JoomlaTemplate"><i class="bi bi-play-circle"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ========== Testimonial end============= -->

    <!-- ========== Exparts Start============= -->
    <div class="cooking-expert-area mb-120">
        <div class="container">
            <div class="row d-flex justify-content-center mb-40">
                <div class="col-lg-8">
                    <div class="section-title text-center">
                        <span><img class="left-vec"  src="{{ asset('client') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec">Experties<img class="right-vec" src="{{ asset('client') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec"></span>
                        <h2>Cooking Experties</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="swiper expart-slider">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="cooking-expart-wrap">
                                <div class="exparts-img">
                                    <img class="img-fluid" src="{{ asset('client') }}/images/bg/exparts1.png" alt="">
                                    <div class="social-area">
                                        <div class="share-icon">
                                            <i class='bx bx-share-alt' ></i>
                                        </div>
                                        <ul>
                                            <li><a href="https://www.facebook.com/"><i class='bx bxl-facebook'></i></a></li>
                                            <li><a href="https://twitter.com/"><i class='bx bxl-twitter' ></i></a></li>
                                            <li><a href="https://www.instagram.com/"><i class='bx bxl-instagram-alt' ></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="exparts-content text-center">
                                    <h3><a href="chef-details.html">Mr. Willium Jhon</a></h3>
                                    <p>Senior Chef   </p>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="cooking-expart-wrap">
                                <div class="exparts-img">
                                    <img class="img-fluid" src="{{ asset('client') }}/images/bg/exparts2.png" alt="">
                                    <div class="social-area">
                                        <div class="share-icon">
                                            <i class='bx bx-share-alt' ></i>
                                        </div>
                                        <ul>
                                            <li><a href="https://www.facebook.com/"><i class='bx bxl-facebook'></i></a></li>
                                            <li><a href="https://twitter.com/"><i class='bx bxl-twitter' ></i></a></li>
                                            <li><a href="https://www.instagram.com/"><i class='bx bxl-instagram-alt' ></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="exparts-content text-center">
                                    <h3><a href="chef-details.html">Markoney Smith</a></h3>
                                    <p>Chef of Head</p>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="cooking-expart-wrap">
                                <div class="exparts-img">
                                    <img class="img-fluid" src="{{ asset('client') }}/images/bg/exparts3.png" alt="">
                                    <div class="social-area">
                                        <div class="share-icon">
                                            <i class='bx bx-share-alt' ></i>
                                        </div>
                                        <ul>
                                            <li><a href="https://www.facebook.com/"><i class='bx bxl-facebook'></i></a></li>
                                            <li><a href="https://twitter.com/"><i class='bx bxl-twitter' ></i></a></li>
                                            <li><a href="https://www.instagram.com/"><i class='bx bxl-instagram-alt' ></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="exparts-content text-center">
                                    <h3><a href="chef-details.html">Jackline Nory</a></h3>
                                    <p>Assistant Chef</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ========== Exparts end============= -->

    <!-- ========== Food Gallery Start============= -->
    <div class="food-gallery-area mb-120">
        <div class="container-fluid">
            <div class="row d-flex justify-content-center mb-40">
                <div class="col-lg-8">
                    <div class="section-title text-center">
                        <span><img class="left-vec"  src="{{ asset('client') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec">Gallery<img class="right-vec" src="{{ asset('client') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec"></span>
                        <h2>Restho’s Gallery</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="swiper gallery-slider1">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <a href="{{ asset('client') }}/images/bg/gallery-big-1.png" data-fancybox="gallery" class="gallery2-img">
                                <div class="gallery-wrap">
                                    <img class="img-fluid" src="{{ asset('client') }}/images/bg/gallery-1.png" alt="">
                                    <div class="overlay d-flex align-items-center justify-content-center">
                                        <div class="items-content text-center">
                                            <span><img class="left-vec" src="{{ asset('client') }}/images/icon/shape-white1.svg" alt="sub-title-vec">Cooking<img class="right-vec" src="{{ asset('client') }}/images/icon/shape-white1.svg" alt="sub-title-vec"></span>
                                            <h3>Spicy Beef</h3>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="swiper-slide">
                            <a href="{{ asset('client') }}/images/bg/gallery-big-2.png" data-fancybox="gallery" class="gallery2-img">
                                <div class="gallery-wrap">
                                    <img class="img-fluid" src="{{ asset('client') }}/images/bg/gallery-2.png" alt="">
                                    <div class="overlay d-flex align-items-center justify-content-center">
                                        <div class="items-content text-center">
                                            <span><img class="left-vec" src="{{ asset('client') }}/images/icon/shape-white1.svg" alt="sub-title-vec">Restaurent<img class="right-vec" src="{{ asset('client') }}/images/icon/shape-white1.svg" alt="sub-title-vec"></span>
                                            <h3>Restho Interior Part</h3>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="swiper-slide">
                            <a href="{{ asset('client') }}/images/bg/gallery-big-3.png" data-fancybox="gallery" class="gallery2-img">
                                <div class="gallery-wrap">
                                    <img class="img-fluid" src="{{ asset('client') }}/images/bg/gallery-3.png" alt="">
                                    <div class="overlay d-flex align-items-center justify-content-center">
                                        <div class="items-content text-center">
                                            <span><img class="left-vec" src="{{ asset('client') }}/images/icon/shape-white1.svg" alt="sub-title-vec">Cooking<img class="right-vec" src="{{ asset('client') }}/images/icon/shape-white1.svg" alt="sub-title-vec"></span>
                                            <h3>Delicious Food</h3>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="swiper-slide">
                            <a href="{{ asset('client') }}/images/bg/gallery-big-4.png" data-fancybox="gallery" class="gallery2-img">
                                <div class="gallery-wrap">
                                    <img class="img-fluid" src="{{ asset('client') }}/images/bg/gallery-4.png" alt="">
                                    <div class="overlay d-flex align-items-center justify-content-center">
                                        <div class="items-content text-center">
                                            <span><img class="left-vec" src="{{ asset('client') }}/images/icon/shape-white1.svg" alt="sub-title-vec">Cooking<img class="right-vec" src="{{ asset('client') }}/images/icon/shape-white1.svg" alt="sub-title-vec"></span>
                                            <h3>Chef Cooking Vegetarian</h3>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="swiper-slide">
                            <a href="{{ asset('client') }}/images/bg/gallery-big-5.png" data-fancybox="gallery" class="gallery2-img">
                                <div class="gallery-wrap">
                                    <img class="img-fluid" src="{{ asset('client') }}/images/bg/gallery-5.png" alt="">
                                    <div class="overlay d-flex align-items-center justify-content-center">
                                        <div class="items-content text-center">
                                            <span><img class="left-vec" src="{{ asset('client') }}/images/icon/shape-white1.svg" alt="sub-title-vec">Cooking<img class="right-vec" src="{{ asset('client') }}/images/icon/shape-white1.svg" alt="sub-title-vec"></span>
                                            <h3>Chef Cooking Vegetarian</h3>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
               
            </div>
        </div>
    </div>
    <!-- ========== Food Gallery end============= -->

    <!-- ========== Recent post Start============= -->
    <div class="recent-post-area mb-120">
        <div class="container">
            <div class="row d-flex justify-content-center mb-40">
                <div class="col-lg-8">
                    <div class="section-title text-center">
                        <span><img class="left-vec"  src="{{ asset('client') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec">Recent News<img class="right-vec" src="{{ asset('client') }}/images/icon/sub-title-vec.svg" alt="sub-title-vec"></span>
                        <h2>Our Recent News</h2>
                    </div>
                </div>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-lg-4 col-md-6 col-sm-10">
                    <div class="news-wrap">
                        <div class="post-thum">
                            <img class="img-fluid" src="{{ asset('client') }}/images/blog/blog-1.png" alt="">
                            <div class="batch">
                                <a class="primary-btn " href="blog-grid.html">07 Aug,2022</a>
                            </div>
                        </div>
                        <div class="news-content">
                            <div class="news-meta">
                                <div class="publisher">
                                    <a href="blog-grid.html"><img src="{{ asset('client') }}/images/icon/User.svg" alt=""> By Admin</a>
                                </div>
                                <div class="comment">
                                    <a href="blog-grid.html"><img src="{{ asset('client') }}/images/icon/Comment.svg" alt=""> Comment(10)</a>
                                </div>
                            </div>
                            <h3><a href="blog-details.html">Eat Healthy Food & Get 
                                Your Happiness</a></h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-10">
                    <div class="news-wrap">
                        <div class="post-thum">
                            <img class="img-fluid" src="{{ asset('client') }}/images/blog/blog-2.png" alt="">
                            <div class="batch">
                                <a class="primary-btn " href="blog-grid.html">07 Aug,2022</a>
                            </div>
                        </div>
                        <div class="news-content">
                            <div class="news-meta">
                                <div class="publisher">
                                    <a href="blog-grid.html"><img src="{{ asset('client') }}/images/icon/User.svg" alt=""> By Admin</a>
                                </div>
                                <div class="comment">
                                    <a href="blog-grid.html"><img src="{{ asset('client') }}/images/icon/Comment.svg" alt=""> Comment(10)</a>
                                </div>
                            </div>
                            <h3><a href="blog-details.html">Cooking Delicious Food Our Experties Chef.</a></h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-10">
                    <div class="news-wrap">
                        <div class="post-thum">
                            <img class="img-fluid" src="{{ asset('client') }}/images/blog/blog-3.png" alt="">
                            <div class="batch">
                                <a class="primary-btn " href="blog-grid.html">07 Aug,2022</a>
                            </div>
                        </div>
                        <div class="news-content">
                            <div class="news-meta">
                                <div class="publisher">
                                    <a href="blog-grid.html"><img src="{{ asset('client') }}/images/icon/User.svg" alt=""> By Admin</a>
                                </div>
                                <div class="comment">
                                    <a href="blog-grid.html"><img src="{{ asset('client') }}/images/icon/Comment.svg" alt=""> Comment(10)</a>
                                </div>
                            </div>
                            <h3><a href="blog-details.html">To Serve Food Customer With Coffee.</a></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ========== Recent post end============= -->

    <!-- ========== Footer post end============= -->
    <footer>
        <div class="footer-top ">
            <div class="container">
                <div class="row justify-content-center align-items-center gy-5">
                    <div class="col-lg-4 col-md-6  order-md-1 order-2">
                        <div class="footer-widget one">
                            <div class="widget-title">
                                <h3>Our Facilities</h3>
                            </div>
                           <div class="menu-container">
                                <ul>
                                    <li><a href="menu1.html">Indian Menu</a></li>
                                    <li><a href="menu1.html">Menu Item</a></li>
                                    <li><a href="reservation.html">Private Event</a></li>
                                    <li><a href="menu1.html">Italian Menu</a></li>
                                    <li><a href="category.html">Best Offer</a></li>
                                </ul>
    
                                <ul>
                                    <li><a href="category.html">Popular Item</a></li>
                                    <li><a href="menu1.html">Regular Menu</a></li>
                                    <li><a href="menu1.html">New Food</a></li>
                                    <li><a href="category.html">Special Offer</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 order-md-2 order-1">
                        <div class="footer-widgetfooter-widget social-area">
                            <div class="footer-logo text-center">
                                <a href="index-2.html"><img src="{{ asset('client') }}/images/header1-logo.svg" alt=""></a>
                                <p>Established . 2022</p>
                                <span><img src="{{ asset('client') }}/images/icon/footer-shape.svg" alt=""></span>
                            </div>
                            <div class="footer-social">
                                <ul class="social-link d-flex align-items-center justify-content-center">
                                    <li><a href="https://www.facebook.com/"><i class="bx bxl-facebook"></i></a></li>
                                    <li><a href="https://www.instagram.com/"><i class='bx bxl-instagram-alt'></i></a></li>
                                    <li><a href="https://www.pinterest.com/"><i class='bx bxl-linkedin' ></i></a></li>
                                    <li><a href="https://twitter.com/"><i class="bx bxl-twitter"></i></a></li>
                                </ul>
                            </div>
    
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 order-3">
                        <div class="footer-widget one">
                            <div class="widget-title">
                                <h3>Address Info</h3>
                            </div>
                            <div class="contact-info">
                                <div class="single-contact">
                                    <span class="title">Phone:</span>
                                    <span class="content"><a href="tel:+8801776766767">+880-1776-766-767</a></span>
                                </div>
                                <div class="single-contact">
                                    <span class="title">Email:</span>
                                    <span class="content"><a href="https://demo.egenslab.com/cdn-cgi/l/email-protection#0b62656d644b6e736a667b676e25686466"><span class="__cf_email__" data-cfemail="87eee9e1e8c7e2ffe6eaf7ebe2a9e4e8ea">[email&#160;protected]</span></a></span>
                                </div>
                                <div class="single-contact">
                                    <span class="title">Fax ID:</span>
                                    <span class="content"><a href="fax:+9975667786">+99-75667-786</a></span>
                                </div>
                                <div class="single-contact">
                                    <span class="title">Location:</span>
                                    <span class="content"><a href="https://goo.gl/maps/2Q4gzMK8mNc1uYnL7">Mirpur DOHS,House-167/170,<br> 
                                        Road-02 Avenue-01.</a></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-btm">
            <div class="container">
                <div class="row border-ttop g-2">
                    <div class="col-md-8 justify-content-md-start justify-content-center">
                        <div class="copyright-area">
                            <p>@Copyright by <a href="#">Egenslab</a>-2022, All Right Reserved.</p>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex justify-content-md-end justify-content-center">
                        <div class="privacy-policy">
                            <p><a href="#">Privacy & Policy</a> | <a href="#">Terms and Conditions</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- ========== Footer post end============= -->


    <script data-cfasync="false" src="https://demo.egenslab.com/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script src="{{ asset('client') }}/js/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('client') }}/js/jquery-ui.js"></script>
    <script src="{{ asset('client') }}/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('client') }}/js/swiper-bundle.min.js"></script>
    <script src="{{ asset('client') }}/js/jquery.nice-select.js"></script>
    <script src="{{ asset('client') }}/js/jquery.fancybox.min.js"></script>
    <script src="{{ asset('client') }}/js/odometer.min.js"></script>
    <script src="{{ asset('client') }}/js/viewport.jquery.js"></script>
    <script src="{{ asset('client') }}/js/isotope.pkgd.min.js"></script>
    <script src="{{ asset('client') }}/js/SmoothScroll.js"></script>
    <script src="{{ asset('client') }}/js/jquery.nice-number.min.js"></script>
    <script src="{{ asset('client') }}/js/jquery.magnific-popup.min.js"></script>
    <script src="{{ asset('client') }}/js/imagesloaded.pkgd.js"></script>
    <script src="{{ asset('client') }}/js/masonry.pkgd.min.js"></script>
    <script src="{{ asset('client') }}/js/main.js"></script>

</body>

<!-- Mirrored from demo.egenslab.com/html/restho/preview/ by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 12 Jan 2025 11:31:19 GMT -->
</html>