<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from uena.dexignzone.com/laravel/demo/index by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 12 Jan 2025 11:02:13 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta name="csrf-token" content="3pvIDLSX29wd150Hxi6MALt7d8F4BtSQooZ74xzA">
	<meta name="description" content="Some description for the page" />
	<title>Uena | Dashboard</title>
	<!-- Favicon icon -->
	<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('admin') }}/images/favicon.png">


	<link href="{{ asset('admin') }}/vendor/chartist/css/chartist.min.css" rel="stylesheet" type="text/css" />




	<link href="{{ asset('admin') }}/vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" type="text/css" />
	<link href="{{ asset('admin') }}/css/style.css" rel="stylesheet" type="text/css" />


</head>

<body>

	<!--*******************
        Preloader start
    ********************-->
	<div id="preloader">
		<div class="sk-three-bounce">
			<div class="sk-child sk-bounce1"></div>
			<div class="sk-child sk-bounce2"></div>
			<div class="sk-child sk-bounce3"></div>
		</div>
	</div>
	<!--*******************
        Preloader end
    ********************-->

	<!--**********************************
        Main wrapper start
    ***********************************-->
	<div id="main-wrapper">

		<!--**********************************
            Nav header start
        ***********************************-->
		<div class="nav-header">
			<a href="index.html" class="brand-logo">
				<svg class="logo-abbr" width="64" height="64" viewBox="0 0 64 64" fill="none"
					xmlns="http://www.w3.org/2000/svg">
					<rect width="64" height="64" rx="2" fill="#FFF0E4" class="svg-logo-rect" />
					<path class="svg-logo-path"
						d="M31.5785 19.6842C31.5785 22.0351 30.765 26.0526 29.1379 31.7368C27.5109 37.386 26.6973 41 26.6973 42.5789C26.6973 44.1228 27.378 44.8947 28.7395 44.8947C30.1341 44.8947 32.0268 43.8246 34.4176 41.6842C34.5172 39.8947 34.7995 37.7544 35.2644 35.2632C36.659 27.4386 38.3525 21.7719 40.3448 18.2632C42.3372 14.7544 44.6616 13 47.318 13C48.8787 13 50.0409 13.4912 50.8046 14.4737C51.6015 15.4211 52 16.6842 52 18.2632C52 23.2456 48.7791 29.9474 42.3372 38.3684C42.2375 39.7368 42.1877 41.1228 42.1877 42.5263C42.1877 45.4386 42.6526 46.8947 43.5824 46.8947C44.1469 46.8947 44.6284 46.8246 45.0268 46.6842L45.7739 48.6842C43.7152 50.2281 41.6564 51 39.5977 51C36.6092 51 34.8991 49.1579 34.4674 45.4737C30.4828 49.1579 27.0792 51 24.2567 51C22.5632 51 21.1852 50.4561 20.1226 49.3684C19.0932 48.2456 18.5785 46.6316 18.5785 44.5263C18.5785 43.2982 18.9604 41.3158 19.7241 38.5789C20.5211 35.807 20.9859 34.1754 21.1188 33.6842C15.7063 31.3333 13 28.0702 13 23.8947C13 21.0175 14.2618 18.4912 16.7854 16.3158C19.3091 14.1053 22.1149 13 25.2031 13C29.4534 13 31.5785 15.2281 31.5785 19.6842ZM22.0651 29.8947C23.2273 25.614 23.8084 22.4211 23.8084 20.3158C23.8084 18.2105 23.0945 17.1579 21.6667 17.1579C20.272 17.1579 18.9936 17.8246 17.8314 19.1579C16.6692 20.4912 16.0881 21.9649 16.0881 23.5789C16.0881 25.1579 16.6692 26.4912 17.8314 27.5789C18.9936 28.6316 20.4049 29.4035 22.0651 29.8947ZM49.0115 18.1579C49.0115 17.2105 48.6794 16.7368 48.0153 16.7368C46.5543 16.7368 44.8774 22.0702 42.9847 32.7368C44.4789 30.6667 45.857 28.193 47.1188 25.3158C48.3806 22.4035 49.0115 20.0175 49.0115 18.1579Z"
						fill="#FF720D" />
				</svg>
				<div class="brand-title">Uena</div>
			</a>
			<div class="nav-control">
				<div class="hamburger">
					<span class="line"></span><span class="line"></span><span class="line"></span>
				</div>
			</div>
		</div>
		<!--**********************************
            Nav header end
        ***********************************-->

		<!--**********************************
            Header start
        ***********************************-->

		<!--**********************************
            Chat box start
        ***********************************-->
		<div class="chatbox">
			<div class="chatbox-close"></div>
			<div class="custom-tab-1">
				<ul class="nav nav-tabs">
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#notes">Notes</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#alerts">Alerts</a>
					</li>
					<li class="nav-item">
						<a class="nav-link active" data-toggle="tab" href="#chat">Chat</a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane fade active show" id="chat" role="tabpanel">
						<div class="card mb-sm-3 mb-md-0 contacts_card dz-chat-user-box">
							<div class="card-header chat-list-header text-center">
								<a href="#"><svg xmlns="http://www.w3.org/2000/svg"
										xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px"
										viewBox="0 0 24 24" version="1.1">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<rect fill="#000000" x="4" y="11" width="16" height="2" rx="1" />
											<rect fill="#000000" opacity="0.3"
												transform="translate(12.000000, 12.000000) rotate(-270.000000) translate(-12.000000, -12.000000) "
												x="4" y="11" width="16" height="2" rx="1" />
										</g>
									</svg></a>
								<div>
									<h6 class="mb-1">Chat List</h6>
									<p class="mb-0">Show All</p>
								</div>
								<a href="#"><svg xmlns="http://www.w3.org/2000/svg"
										xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px"
										viewBox="0 0 24 24" version="1.1">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<rect x="0" y="0" width="24" height="24" />
											<circle fill="#000000" cx="5" cy="12" r="2" />
											<circle fill="#000000" cx="12" cy="12" r="2" />
											<circle fill="#000000" cx="19" cy="12" r="2" />
										</g>
									</svg></a>
							</div>
							<div class="card-body contacts_body p-0 dz-scroll  " id="DZ_W_Contacts_Body">
								<ul class="contacts">
									<li class="name-first-letter">A</li>
									<li class="active dz-chat-user">
										<div class="d-flex bd-highlight">
											<div class="img_cont">
												<img src="{{ asset('admin') }}/images/avatar/1.jpg" class="rounded-circle user_img"
													alt="" />
												<span class="online_icon"></span>
											</div>
											<div class="user_info">
												<span>Archie Parker</span>
												<p>Kalid is online</p>
											</div>
										</div>
									</li>
									<li class="dz-chat-user">
										<div class="d-flex bd-highlight">
											<div class="img_cont">
												<img src="{{ asset('admin') }}/images/avatar/2.jpg" class="rounded-circle user_img"
													alt="" />
												<span class="online_icon offline"></span>
											</div>
											<div class="user_info">
												<span>Alfie Mason</span>
												<p>Taherah left 7 mins ago</p>
											</div>
										</div>
									</li>
									<li class="dz-chat-user">
										<div class="d-flex bd-highlight">
											<div class="img_cont">
												<img src="{{ asset('admin') }}/images/avatar/3.jpg" class="rounded-circle user_img"
													alt="" />
												<span class="online_icon"></span>
											</div>
											<div class="user_info">
												<span>AharlieKane</span>
												<p>Sami is online</p>
											</div>
										</div>
									</li>
									<li class="dz-chat-user">
										<div class="d-flex bd-highlight">
											<div class="img_cont">
												<img src="{{ asset('admin') }}/images/avatar/4.jpg" class="rounded-circle user_img"
													alt="" />
												<span class="online_icon offline"></span>
											</div>
											<div class="user_info">
												<span>Athan Jacoby</span>
												<p>Nargis left 30 mins ago</p>
											</div>
										</div>
									</li>
									<li class="name-first-letter">B</li>
									<li class="dz-chat-user">
										<div class="d-flex bd-highlight">
											<div class="img_cont">
												<img src="{{ asset('admin') }}/images/avatar/5.jpg" class="rounded-circle user_img"
													alt="" />
												<span class="online_icon offline"></span>
											</div>
											<div class="user_info">
												<span>Bashid Samim</span>
												<p>Rashid left 50 mins ago</p>
											</div>
										</div>
									</li>
									<li class="dz-chat-user">
										<div class="d-flex bd-highlight">
											<div class="img_cont">
												<img src="{{ asset('admin') }}/images/avatar/1.jpg" class="rounded-circle user_img"
													alt="" />
												<span class="online_icon"></span>
											</div>
											<div class="user_info">
												<span>Breddie Ronan</span>
												<p>Kalid is online</p>
											</div>
										</div>
									</li>
									<li class="dz-chat-user">
										<div class="d-flex bd-highlight">
											<div class="img_cont">
												<img src="{{ asset('admin') }}/images/avatar/2.jpg" class="rounded-circle user_img"
													alt="" />
												<span class="online_icon offline"></span>
											</div>
											<div class="user_info">
												<span>Ceorge Carson</span>
												<p>Taherah left 7 mins ago</p>
											</div>
										</div>
									</li>
									<li class="name-first-letter">D</li>
									<li class="dz-chat-user">
										<div class="d-flex bd-highlight">
											<div class="img_cont">
												<img src="{{ asset('admin') }}/images/avatar/3.jpg" class="rounded-circle user_img"
													alt="" />
												<span class="online_icon"></span>
											</div>
											<div class="user_info">
												<span>Darry Parker</span>
												<p>Sami is online</p>
											</div>
										</div>
									</li>
									<li class="dz-chat-user">
										<div class="d-flex bd-highlight">
											<div class="img_cont">
												<img src="{{ asset('admin') }}/images/avatar/4.jpg" class="rounded-circle user_img"
													alt="" />
												<span class="online_icon offline"></span>
											</div>
											<div class="user_info">
												<span>Denry Hunter</span>
												<p>Nargis left 30 mins ago</p>
											</div>
										</div>
									</li>
									<li class="name-first-letter">J</li>
									<li class="dz-chat-user">
										<div class="d-flex bd-highlight">
											<div class="img_cont">
												<img src="{{ asset('admin') }}/images/avatar/5.jpg" class="rounded-circle user_img"
													alt="" />
												<span class="online_icon offline"></span>
											</div>
											<div class="user_info">
												<span>Jack Ronan</span>
												<p>Rashid left 50 mins ago</p>
											</div>
										</div>
									</li>
									<li class="dz-chat-user">
										<div class="d-flex bd-highlight">
											<div class="img_cont">
												<img src="{{ asset('admin') }}/images/avatar/1.jpg" class="rounded-circle user_img"
													alt="" />
												<span class="online_icon"></span>
											</div>
											<div class="user_info">
												<span>Jacob Tucker</span>
												<p>Kalid is online</p>
											</div>
										</div>
									</li>
									<li class="dz-chat-user">
										<div class="d-flex bd-highlight">
											<div class="img_cont">
												<img src="{{ asset('admin') }}/images/avatar/2.jpg" class="rounded-circle user_img"
													alt="" />
												<span class="online_icon offline"></span>
											</div>
											<div class="user_info">
												<span>James Logan</span>
												<p>Taherah left 7 mins ago</p>
											</div>
										</div>
									</li>
									<li class="dz-chat-user">
										<div class="d-flex bd-highlight">
											<div class="img_cont">
												<img src="{{ asset('admin') }}/images/avatar/3.jpg" class="rounded-circle user_img"
													alt="" />
												<span class="online_icon"></span>
											</div>
											<div class="user_info">
												<span>Joshua Weston</span>
												<p>Sami is online</p>
											</div>
										</div>
									</li>
									<li class="name-first-letter">O</li>
									<li class="dz-chat-user">
										<div class="d-flex bd-highlight">
											<div class="img_cont">
												<img src="{{ asset('admin') }}/images/avatar/4.jpg" class="rounded-circle user_img"
													alt="" />
												<span class="online_icon offline"></span>
											</div>
											<div class="user_info">
												<span>Oliver Acker</span>
												<p>Nargis left 30 mins ago</p>
											</div>
										</div>
									</li>
									<li class="dz-chat-user">
										<div class="d-flex bd-highlight">
											<div class="img_cont">
												<img src="{{ asset('admin') }}/images/avatar/5.jpg" class="rounded-circle user_img"
													alt="" />
												<span class="online_icon offline"></span>
											</div>
											<div class="user_info">
												<span>Oscar Weston</span>
												<p>Rashid left 50 mins ago</p>
											</div>
										</div>
									</li>
								</ul>
							</div>
						</div>
						<div class="card chat dz-chat-history-box d-none">
							<div class="card-header chat-list-header text-center">
								<a href="#" class="dz-chat-history-back">
									<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
										width="18px" height="18px" viewBox="0 0 24 24" version="1.1">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<polygon points="0 0 24 0 24 24 0 24" />
											<rect fill="#000000" opacity="0.3"
												transform="translate(15.000000, 12.000000) scale(-1, 1) rotate(-90.000000) translate(-15.000000, -12.000000) "
												x="14" y="7" width="2" height="10" rx="1" />
											<path
												d="M3.7071045,15.7071045 C3.3165802,16.0976288 2.68341522,16.0976288 2.29289093,15.7071045 C1.90236664,15.3165802 1.90236664,14.6834152 2.29289093,14.2928909 L8.29289093,8.29289093 C8.67146987,7.914312 9.28105631,7.90106637 9.67572234,8.26284357 L15.6757223,13.7628436 C16.0828413,14.136036 16.1103443,14.7686034 15.7371519,15.1757223 C15.3639594,15.5828413 14.7313921,15.6103443 14.3242731,15.2371519 L9.03007346,10.3841355 L3.7071045,15.7071045 Z"
												fill="#000000" fill-rule="nonzero"
												transform="translate(9.000001, 11.999997) scale(-1, -1) rotate(90.000000) translate(-9.000001, -11.999997) " />
										</g>
									</svg>
								</a>
								<div>
									<h6 class="mb-1">Chat with Khelesh</h6>
									<p class="mb-0 text-success">Online</p>
								</div>
								<div class="dropdown">
									<a href="#" data-toggle="dropdown" aria-expanded="false"><svg
											xmlns="http://www.w3.org/2000/svg"
											xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px"
											viewBox="0 0 24 24" version="1.1">
											<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
												<rect x="0" y="0" width="24" height="24" />
												<circle fill="#000000" cx="5" cy="12" r="2" />
												<circle fill="#000000" cx="12" cy="12" r="2" />
												<circle fill="#000000" cx="19" cy="12" r="2" />
											</g>
										</svg></a>
									<ul class="dropdown-menu dropdown-menu-right">
										<li class="dropdown-item"><i class="fa fa-user-circle text-primary mr-2"></i>
											View profile</li>
										<li class="dropdown-item"><i class="fa fa-users text-primary mr-2"></i> Add to
											close friends</li>
										<li class="dropdown-item"><i class="fa fa-plus text-primary mr-2"></i> Add to
											group</li>
										<li class="dropdown-item"><i class="fa fa-ban text-primary mr-2"></i> Block</li>
									</ul>
								</div>
							</div>
							<div class="card-body msg_card_body dz-scroll" id="DZ_W_Contacts_Body3">
								<div class="d-flex justify-content-start mb-4">
									<div class="img_cont_msg">
										<img src="{{ asset('admin') }}/images/avatar/1.jpg" class="rounded-circle user_img_msg"
											alt="" />
									</div>
									<div class="msg_cotainer">
										Hi, how are you samim?
										<span class="msg_time">8:40 AM, Today</span>
									</div>
								</div>
								<div class="d-flex justify-content-end mb-4">
									<div class="msg_cotainer_send">
										Hi Khalid i am good tnx how about you?
										<span class="msg_time_send">8:55 AM, Today</span>
									</div>
									<div class="img_cont_msg">
										<img src="{{ asset('admin') }}/images/avatar/2.jpg" class="rounded-circle user_img_msg"
											alt="" />
									</div>
								</div>
								<div class="d-flex justify-content-start mb-4">
									<div class="img_cont_msg">
										<img src="{{ asset('admin') }}/images/avatar/1.jpg" class="rounded-circle user_img_msg"
											alt="" />
									</div>
									<div class="msg_cotainer">
										I am good too, thank you for your chat template
										<span class="msg_time">9:00 AM, Today</span>
									</div>
								</div>
								<div class="d-flex justify-content-end mb-4">
									<div class="msg_cotainer_send">
										You are welcome
										<span class="msg_time_send">9:05 AM, Today</span>
									</div>
									<div class="img_cont_msg">
										<img src="{{ asset('admin') }}/images/avatar/2.jpg" class="rounded-circle user_img_msg"
											alt="" />
									</div>
								</div>
								<div class="d-flex justify-content-start mb-4">
									<div class="img_cont_msg">
										<img src="{{ asset('admin') }}/images/avatar/1.jpg" class="rounded-circle user_img_msg"
											alt="" />
									</div>
									<div class="msg_cotainer">
										I am looking for your next templates
										<span class="msg_time">9:07 AM, Today</span>
									</div>
								</div>
								<div class="d-flex justify-content-end mb-4">
									<div class="msg_cotainer_send">
										Ok, thank you have a good day
										<span class="msg_time_send">9:10 AM, Today</span>
									</div>
									<div class="img_cont_msg">
										<img src="{{ asset('admin') }}/images/avatar/2.jpg" class="rounded-circle user_img_msg"
											alt="" />
									</div>
								</div>
								<div class="d-flex justify-content-start mb-4">
									<div class="img_cont_msg">
										<img src="{{ asset('admin') }}/images/avatar/1.jpg" class="rounded-circle user_img_msg"
											alt="" />
									</div>
									<div class="msg_cotainer">
										Bye, see you
										<span class="msg_time">9:12 AM, Today</span>
									</div>
								</div>
								<div class="d-flex justify-content-start mb-4">
									<div class="img_cont_msg">
										<img src="{{ asset('admin') }}/images/avatar/1.jpg" class="rounded-circle user_img_msg"
											alt="" />
									</div>
									<div class="msg_cotainer">
										Hi, how are you samim?
										<span class="msg_time">8:40 AM, Today</span>
									</div>
								</div>
								<div class="d-flex justify-content-end mb-4">
									<div class="msg_cotainer_send">
										Hi Khalid i am good tnx how about you?
										<span class="msg_time_send">8:55 AM, Today</span>
									</div>
									<div class="img_cont_msg">
										<img src="{{ asset('admin') }}/images/avatar/2.jpg" class="rounded-circle user_img_msg"
											alt="" />
									</div>
								</div>
								<div class="d-flex justify-content-start mb-4">
									<div class="img_cont_msg">
										<img src="{{ asset('admin') }}/images/avatar/1.jpg" class="rounded-circle user_img_msg"
											alt="" />
									</div>
									<div class="msg_cotainer">
										I am good too, thank you for your chat template
										<span class="msg_time">9:00 AM, Today</span>
									</div>
								</div>
								<div class="d-flex justify-content-end mb-4">
									<div class="msg_cotainer_send">
										You are welcome
										<span class="msg_time_send">9:05 AM, Today</span>
									</div>
									<div class="img_cont_msg">
										<img src="{{ asset('admin') }}/images/avatar/2.jpg" class="rounded-circle user_img_msg"
											alt="" />
									</div>
								</div>
								<div class="d-flex justify-content-start mb-4">
									<div class="img_cont_msg">
										<img src="{{ asset('admin') }}/images/avatar/1.jpg" class="rounded-circle user_img_msg"
											alt="" />
									</div>
									<div class="msg_cotainer">
										I am looking for your next templates
										<span class="msg_time">9:07 AM, Today</span>
									</div>
								</div>
								<div class="d-flex justify-content-end mb-4">
									<div class="msg_cotainer_send">
										Ok, thank you have a good day
										<span class="msg_time_send">9:10 AM, Today</span>
									</div>
									<div class="img_cont_msg">
										<img src="{{ asset('admin') }}/images/avatar/2.jpg" class="rounded-circle user_img_msg"
											alt="" />
									</div>
								</div>
								<div class="d-flex justify-content-start mb-4">
									<div class="img_cont_msg">
										<img src="{{ asset('admin') }}/images/avatar/1.jpg" class="rounded-circle user_img_msg"
											alt="" />
									</div>
									<div class="msg_cotainer">
										Bye, see you
										<span class="msg_time">9:12 AM, Today</span>
									</div>
								</div>
							</div>
							<div class="card-footer type_msg">
								<div class="input-group">
									<textarea class="form-control" placeholder="Type your message..."></textarea>
									<div class="input-group-append">
										<button type="button" class="btn btn-primary"><i
												class="fa fa-location-arrow"></i></button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="alerts" role="tabpanel">
						<div class="card mb-sm-3 mb-md-0 contacts_card">
							<div class="card-header chat-list-header text-center">
								<a href="#"><svg xmlns="http://www.w3.org/2000/svg"
										xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px"
										viewBox="0 0 24 24" version="1.1">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<rect x="0" y="0" width="24" height="24" />
											<circle fill="#000000" cx="5" cy="12" r="2" />
											<circle fill="#000000" cx="12" cy="12" r="2" />
											<circle fill="#000000" cx="19" cy="12" r="2" />
										</g>
									</svg></a>
								<div>
									<h6 class="mb-1">Notications</h6>
									<p class="mb-0">Show All</p>
								</div>
								<a href="#"><svg xmlns="http://www.w3.org/2000/svg"
										xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px"
										viewBox="0 0 24 24" version="1.1">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<rect x="0" y="0" width="24" height="24" />
											<path
												d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z"
												fill="#000000" fill-rule="nonzero" opacity="0.3" />
											<path
												d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z"
												fill="#000000" fill-rule="nonzero" />
										</g>
									</svg></a>
							</div>
							<div class="card-body contacts_body p-0 dz-scroll" id="DZ_W_Contacts_Body1">
								<ul class="contacts">
									<li class="name-first-letter">SEVER STATUS</li>
									<li class="active">
										<div class="d-flex bd-highlight">
											<div class="img_cont primary">KK</div>
											<div class="user_info">
												<span>David Nester Birthday</span>
												<p class="text-primary">Today</p>
											</div>
										</div>
									</li>
									<li class="name-first-letter">SOCIAL</li>
									<li>
										<div class="d-flex bd-highlight">
											<div class="img_cont success">RU</div>
											<div class="user_info">
												<span>Perfection Simplified</span>
												<p>Jame Smith commented on your status</p>
											</div>
										</div>
									</li>
									<li class="name-first-letter">SEVER STATUS</li>
									<li>
										<div class="d-flex bd-highlight">
											<div class="img_cont primary">AU</div>
											<div class="user_info">
												<span>AharlieKane</span>
												<p>Sami is online</p>
											</div>
										</div>
									</li>
									<li>
										<div class="d-flex bd-highlight">
											<div class="img_cont info">MO</div>
											<div class="user_info">
												<span>Athan Jacoby</span>
												<p>Nargis left 30 mins ago</p>
											</div>
										</div>
									</li>
								</ul>
							</div>
							<div class="card-footer"></div>
						</div>
					</div>
					<div class="tab-pane fade" id="notes">
						<div class="card mb-sm-3 mb-md-0 note_card">
							<div class="card-header chat-list-header text-center">
								<a href="#"><svg xmlns="http://www.w3.org/2000/svg"
										xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px"
										viewBox="0 0 24 24" version="1.1">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<rect fill="#000000" x="4" y="11" width="16" height="2" rx="1" />
											<rect fill="#000000" opacity="0.3"
												transform="translate(12.000000, 12.000000) rotate(-270.000000) translate(-12.000000, -12.000000) "
												x="4" y="11" width="16" height="2" rx="1" />
										</g>
									</svg></a>
								<div>
									<h6 class="mb-1">Notes</h6>
									<p class="mb-0">Add New Nots</p>
								</div>
								<a href="#"><svg xmlns="http://www.w3.org/2000/svg"
										xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px"
										viewBox="0 0 24 24" version="1.1">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<rect x="0" y="0" width="24" height="24" />
											<path
												d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z"
												fill="#000000" fill-rule="nonzero" opacity="0.3" />
											<path
												d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z"
												fill="#000000" fill-rule="nonzero" />
										</g>
									</svg></a>
							</div>
							<div class="card-body contacts_body p-0 dz-scroll" id="DZ_W_Contacts_Body2">
								<ul class="contacts">
									<li class="active">
										<div class="d-flex bd-highlight">
											<div class="user_info">
												<span>New order placed..</span>
												<p>10 Aug 2020</p>
											</div>
											<div class="ml-auto">
												<a href="#" class="btn btn-primary btn-xs sharp mr-1"><i
														class="fa fa-pencil"></i></a>
												<a href="#" class="btn btn-danger btn-xs sharp"><i
														class="fa fa-trash"></i></a>
											</div>
										</div>
									</li>
									<li>
										<div class="d-flex bd-highlight">
											<div class="user_info">
												<span>Youtube, a video-sharing website..</span>
												<p>10 Aug 2020</p>
											</div>
											<div class="ml-auto">
												<a href="#" class="btn btn-primary btn-xs sharp mr-1"><i
														class="fa fa-pencil"></i></a>
												<a href="#" class="btn btn-danger btn-xs sharp"><i
														class="fa fa-trash"></i></a>
											</div>
										</div>
									</li>
									<li>
										<div class="d-flex bd-highlight">
											<div class="user_info">
												<span>john just buy your product..</span>
												<p>10 Aug 2020</p>
											</div>
											<div class="ml-auto">
												<a href="#" class="btn btn-primary btn-xs sharp mr-1"><i
														class="fa fa-pencil"></i></a>
												<a href="#" class="btn btn-danger btn-xs sharp"><i
														class="fa fa-trash"></i></a>
											</div>
										</div>
									</li>
									<li>
										<div class="d-flex bd-highlight">
											<div class="user_info">
												<span>Athan Jacoby</span>
												<p>10 Aug 2020</p>
											</div>
											<div class="ml-auto">
												<a href="#" class="btn btn-primary btn-xs sharp mr-1"><i
														class="fa fa-pencil"></i></a>
												<a href="#" class="btn btn-danger btn-xs sharp"><i
														class="fa fa-trash"></i></a>
											</div>
										</div>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--**********************************
            Chat box End
        ***********************************-->

		<!--**********************************
            Header start
        ***********************************-->
		<div class="header">
			<div class="header-content">
				<nav class="navbar navbar-expand">
					<div class="collapse navbar-collapse justify-content-between">
						<div class="header-left">
							<div class="dashboard_bar">
								Dashboard </div>
						</div>

						<ul class="navbar-nav header-right">
							<li class="nav-item">
								<div class="input-group search-area d-xl-inline-flex d-none">
									<input type="text" class="form-control" placeholder="Search here...">
									<div class="input-group-append">
										<button class="input-group-text"><i class="flaticon-381-search-2"></i></button>
									</div>
								</div>
							</li>
							<li class="nav-item dropdown notification_dropdown">
								<a class="nav-link  ai-icon" href="#" role="button" data-toggle="dropdown">
									<svg width="28" height="28" viewBox="0 0 28 28" fill="none"
										xmlns="http://www.w3.org/2000/svg">
										<path
											d="M14 1.16663C11.6793 1.16663 9.45374 2.0885 7.8128 3.72944C6.17186 5.37039 5.24999 7.59598 5.24999 9.91663V16.7416L3.31332 19.6583C3.08119 20.0162 2.94953 20.4299 2.9321 20.8562C2.91468 21.2824 3.01213 21.7055 3.21427 22.0811C3.41641 22.4568 3.71581 22.7712 4.08112 22.9915C4.44644 23.2118 4.86424 23.3298 5.29082 23.3333H22.7091C23.1385 23.3319 23.5594 23.2143 23.9274 22.9932C24.2954 22.772 24.5967 22.4554 24.7993 22.0769C25.002 21.6984 25.0986 21.2722 25.0787 20.8433C25.0589 20.4145 24.9234 19.9989 24.6866 19.6408L22.75 16.7416V9.91663C22.75 7.59598 21.8281 5.37039 20.1872 3.72944C18.5462 2.0885 16.3206 1.16663 14 1.16663Z"
											fill="#636363" />
										<path
											d="M14 26.8333C14.7231 26.8325 15.4282 26.6077 16.0184 26.1899C16.6085 25.772 17.0548 25.1817 17.2958 24.4999H10.7042C10.9452 25.1817 11.3915 25.772 11.9816 26.1899C12.5718 26.6077 13.2769 26.8325 14 26.8333Z"
											fill="#636363" />
									</svg>
									<div class="pulse-css"></div>
								</a>
								<div class="dropdown-menu dropdown-menu-right">
									<div id="DZ_W_Notification1" class="widget-media dz-scroll p-3"
										style="height:380px;">
										<ul class="timeline">
											<li>
												<div class="timeline-panel">
													<div class="media mr-2">
														<img alt="image" width="50" src="{{ asset('admin') }}/images/avatar/1.jpg">
													</div>
													<div class="media-body">
														<h6 class="mb-1">Dr sultads Send you Photo</h6>
														<small class="d-block">29 July 2020 - 02:26 PM</small>
													</div>
												</div>
											</li>
											<li>
												<div class="timeline-panel">
													<div class="media mr-2 media-info">
														KG
													</div>
													<div class="media-body">
														<h6 class="mb-1">Resport created successfully</h6>
														<small class="d-block">29 July 2020 - 02:26 PM</small>
													</div>
												</div>
											</li>
											<li>
												<div class="timeline-panel">
													<div class="media mr-2 media-success">
														<i class="fa fa-home"></i>
													</div>
													<div class="media-body">
														<h6 class="mb-1">Reminder : Treatment Time!</h6>
														<small class="d-block">29 July 2020 - 02:26 PM</small>
													</div>
												</div>
											</li>
											<li>
												<div class="timeline-panel">
													<div class="media mr-2">
														<img alt="image" width="50" src="{{ asset('admin') }}/images/avatar/1.jpg">
													</div>
													<div class="media-body">
														<h6 class="mb-1">Dr sultads Send you Photo</h6>
														<small class="d-block">29 July 2020 - 02:26 PM</small>
													</div>
												</div>
											</li>
											<li>
												<div class="timeline-panel">
													<div class="media mr-2 media-danger">
														KG
													</div>
													<div class="media-body">
														<h6 class="mb-1">Resport created successfully</h6>
														<small class="d-block">29 July 2020 - 02:26 PM</small>
													</div>
												</div>
											</li>
											<li>
												<div class="timeline-panel">
													<div class="media mr-2 media-primary">
														<i class="fa fa-home"></i>
													</div>
													<div class="media-body">
														<h6 class="mb-1">Reminder : Treatment Time!</h6>
														<small class="d-block">29 July 2020 - 02:26 PM</small>
													</div>
												</div>
											</li>
										</ul>
									</div>
									<a class="all-notification" href="#">See all notifications <i
											class="ti-arrow-right"></i></a>
								</div>
							</li>
							<li class="nav-item dropdown notification_dropdown">
								<a class="nav-link bell bell-link" href="#">
									<svg width="26" height="26" viewBox="0 0 26 26" fill="none"
										xmlns="http://www.w3.org/2000/svg">
										<path
											d="M22.9166 0.166626H3.08332C2.30977 0.166626 1.56791 0.473917 1.02093 1.0209C0.473947 1.56788 0.166656 2.30974 0.166656 3.08329V18.25C0.166656 19.0235 0.473947 19.7654 1.02093 20.3124C1.56791 20.8593 2.30977 21.1666 3.08332 21.1666H4.24999V25.25C4.25 25.3512 4.27637 25.4507 4.32648 25.5386C4.3766 25.6266 4.44874 25.7 4.53582 25.7516C4.6245 25.8028 4.72509 25.8298 4.82749 25.8298C4.92988 25.8298 5.03048 25.8028 5.11915 25.7516L13.7291 21.1666H22.9166C23.6902 21.1666 24.4321 20.8593 24.979 20.3124C25.526 19.7654 25.8333 19.0235 25.8333 18.25V3.08329C25.8333 2.30974 25.526 1.56788 24.979 1.0209C24.4321 0.473917 23.6902 0.166626 22.9166 0.166626ZM21.75 15.3333H4.24999C4.09528 15.3333 3.9469 15.2718 3.83751 15.1624C3.72811 15.053 3.66665 14.9047 3.66665 14.75C3.66665 14.5952 3.72811 14.4469 3.83751 14.3375C3.9469 14.2281 4.09528 14.1666 4.24999 14.1666H21.75C21.9047 14.1666 22.0531 14.2281 22.1625 14.3375C22.2719 14.4469 22.3333 14.5952 22.3333 14.75C22.3333 14.9047 22.2719 15.053 22.1625 15.1624C22.0531 15.2718 21.9047 15.3333 21.75 15.3333ZM21.75 11.25H4.24999C4.09528 11.25 3.9469 11.1885 3.83751 11.0791C3.72811 10.9697 3.66665 10.8213 3.66665 10.6666C3.66665 10.5119 3.72811 10.3635 3.83751 10.2541C3.9469 10.1447 4.09528 10.0833 4.24999 10.0833H21.75C21.9047 10.0833 22.0531 10.1447 22.1625 10.2541C22.2719 10.3635 22.3333 10.5119 22.3333 10.6666C22.3333 10.8213 22.2719 10.9697 22.1625 11.0791C22.0531 11.1885 21.9047 11.25 21.75 11.25ZM21.75 7.16663H4.24999C4.09528 7.16663 3.9469 7.10517 3.83751 6.99577C3.72811 6.88637 3.66665 6.738 3.66665 6.58329C3.66665 6.42858 3.72811 6.28021 3.83751 6.17081C3.9469 6.06142 4.09528 5.99996 4.24999 5.99996H21.75C21.9047 5.99996 22.0531 6.06142 22.1625 6.17081C22.2719 6.28021 22.3333 6.42858 22.3333 6.58329C22.3333 6.738 22.2719 6.88637 22.1625 6.99577C22.0531 7.10517 21.9047 7.16663 21.75 7.16663Z"
											fill="#636363" />
									</svg>
									<div class="pulse-css"></div>
								</a>
							</li>
							<li class="nav-item dropdown notification_dropdown">
								<a class="nav-link" href="#" data-toggle="dropdown">
									<svg width="26" height="26" viewBox="0 0 26 26" fill="none"
										xmlns="http://www.w3.org/2000/svg">
										<path
											d="M10.515 24.8942C11.2013 25.4995 12.0849 25.8336 13 25.8336C13.9151 25.8336 14.7987 25.4995 15.485 24.8942C18.4892 22.2517 25.5125 15.2751 25.8334 7.68005C25.9373 5.80349 25.2965 3.96171 24.0502 2.55489C22.804 1.14807 21.0529 0.289858 19.1775 0.16672C17.964 0.10879 16.7563 0.367679 15.6732 0.917932C14.5901 1.46819 13.6688 2.29086 13 3.30505C12.3342 2.28795 11.4135 1.46302 10.3296 0.912401C9.24579 0.361783 8.03662 0.10469 6.82252 0.16672C4.94711 0.289858 3.19606 1.14807 1.9498 2.55489C0.703537 3.96171 0.0627602 5.80349 0.166687 7.68005C0.48752 15.2751 7.51085 22.2517 10.515 24.8942Z"
											fill="#636363" />
									</svg>
									<div class="pulse-css"></div>
								</a>
								<div class="dropdown-menu dropdown-menu-right">
									<div id="DZ_W_TimeLine02"
										class="widget-timeline dz-scroll style-1 ps ps--active-y p-3 height370">
										<ul class="timeline">
											<li>
												<div class="timeline-badge primary"></div>
												<a class="timeline-panel text-muted" href="#">
													<span>10 minutes ago</span>
													<h6 class="mb-0">Youtube, a video-sharing website, goes live <strong
															class="text-primary">$500</strong>.</h6>
												</a>
											</li>
											<li>
												<div class="timeline-badge info">
												</div>
												<a class="timeline-panel text-muted" href="#">
													<span>20 minutes ago</span>
													<h6 class="mb-0">New order placed <strong
															class="text-info">#XF-2356.</strong></h6>
													<p class="mb-0">Quisque a consequat ante Sit amet magna at
														volutapt...</p>
												</a>
											</li>
											<li>
												<div class="timeline-badge danger">
												</div>
												<a class="timeline-panel text-muted" href="#">
													<span>30 minutes ago</span>
													<h6 class="mb-0">john just buy your product <strong
															class="text-warning">Sell $250</strong></h6>
												</a>
											</li>
											<li>
												<div class="timeline-badge success">
												</div>
												<a class="timeline-panel text-muted" href="#">
													<span>15 minutes ago</span>
													<h6 class="mb-0">StumbleUpon is acquired by eBay. </h6>
												</a>
											</li>
											<li>
												<div class="timeline-badge warning">
												</div>
												<a class="timeline-panel text-muted" href="#">
													<span>20 minutes ago</span>
													<h6 class="mb-0">Mashable, a news website and blog, goes live.</h6>
												</a>
											</li>
											<li>
												<div class="timeline-badge dark">
												</div>
												<a class="timeline-panel text-muted" href="#">
													<span>20 minutes ago</span>
													<h6 class="mb-0">Mashable, a news website and blog, goes live.</h6>
												</a>
											</li>
										</ul>
									</div>
								</div>
							</li>
							<li class="nav-item dropdown header-profile">
								<a class="nav-link" href="#" role="button" data-toggle="dropdown">
									<img src="{{ asset('admin') }}/images/profile/pic1.jpg" width="20" alt="" />
									<div class="header-info">
										<span class="fs-20 font-w500">Robertos Jr.</span>
										<small>Super Admin</small>
									</div>
								</a>
								<div class="dropdown-menu dropdown-menu-right">
									<a href="app-profile.html" class="dropdown-item ai-icon">
										<svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" class="text-primary"
											width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
											stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
											<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
											<circle cx="12" cy="7" r="4"></circle>
										</svg>
										<span class="ml-2">Profile </span>
									</a>
									<a href="email-inbox.html" class="dropdown-item ai-icon">
										<svg id="icon-inbox" xmlns="http://www.w3.org/2000/svg" class="text-success"
											width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
											stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
											<path
												d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
											</path>
											<polyline points="22,6 12,13 2,6"></polyline>
										</svg>
										<span class="ml-2">Inbox </span>
									</a>
									<a href="page-login.html" class="dropdown-item ai-icon">
										<svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger"
											width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
											stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
											<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
											<polyline points="16 17 21 12 16 7"></polyline>
											<line x1="21" y1="12" x2="9" y2="12"></line>
										</svg>
										<span class="ml-2">Logout </span>
									</a>
								</div>
							</li>
						</ul>
					</div>
				</nav>
			</div>
		</div>
		<!--**********************************
            Header end ti-comment-alt
        ***********************************-->

		<!--**********************************
            Header end ti-comment-alt
        ***********************************-->

		<!--**********************************
            Sidebar start
        ***********************************-->
		<!--**********************************
    Sidebar start
***********************************-->
		<div class="deznav">
			<div class="deznav-scroll">
				<ul class="metismenu" id="menu">
					<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="flaticon-025-dashboard"></i>
							<span class="nav-text">Dashboard</span>
						</a>
						<ul aria-expanded="false">
							<li><a href="index.html">Dashboard Light</a></li>
							<li><a href="index-2.html">Dashboard Dark</a></li>
							<li><a href="orders-list.html">Orders list</a></li>
							<li><a href="order-detail.html">Order Detail</a></li>
							<li><a href="customer-list.html">Customers</a></li>
							<li><a href="analytics.html">Analytics</a></li>
							<li><a href="reviews.html">Reviews</a></li>
						</ul>

					</li>
					<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="flaticon-050-info"></i>
							<span class="nav-text">Apps</span>
						</a>
						<ul aria-expanded="false">
							<li><a href="app-profile.html">Profile</a></li>
							<li><a href="post-details.html">Post Details</a></li>
							<li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Email</a>
								<ul aria-expanded="false">
									<li><a href="email-compose.html">Compose</a></li>
									<li><a href="email-inbox.html">Inbox</a></li>
									<li><a href="email-read.html">Read</a></li>
								</ul>
							</li>
							<li><a href="app-calender.html">Calendar</a></li>
							<li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Shop</a>
								<ul aria-expanded="false">
									<li><a href="ecom-product-grid.html">Product Grid</a></li>
									<li><a href="ecom-product-list.html">Product List</a></li>
									<li><a href="ecom-product-detail.html">Product Details</a></li>
									<li><a href="ecom-product-order.html">Order</a></li>
									<li><a href="ecom-checkout.html">Checkout</a></li>
									<li><a href="ecom-invoice.html">Invoice</a></li>
									<li><a href="ecom-customers.html">Customers</a></li>
								</ul>
							</li>
						</ul>
					</li>
					<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="flaticon-041-graph"></i>
							<span class="nav-text">Charts</span>
						</a>
						<ul aria-expanded="false">
							<li><a href="chart-flot.html">Flot</a></li>
							<li><a href="chart-morris.html">Morris</a></li>
							<li><a href="chart-chartjs.html">Chartjs</a></li>
							<li><a href="chart-chartist.html">Chartist</a></li>
							<li><a href="chart-sparkline.html">Sparkline</a></li>
							<li><a href="chart-peity.html">Peity</a></li>
						</ul>
					</li>
					<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="flaticon-086-star"></i>
							<span class="nav-text">Bootstrap</span>
						</a>
						<ul aria-expanded="false">
							<li><a href="ui-accordion.html">Accordion</a></li>
							<li><a href="ui-alert.html">Alert</a></li>
							<li><a href="ui-badge.html">Badge</a></li>
							<li><a href="ui-button.html">Button</a></li>
							<li><a href="ui-modal.html">Modal</a></li>
							<li><a href="ui-button-group.html">Button Group</a></li>
							<li><a href="ui-list-group.html">List Group</a></li>
							<li><a href="ui-media-object.html">Media Object</a></li>
							<li><a href="ui-card.html">Cards</a></li>
							<li><a href="ui-carousel.html">Carousel</a></li>
							<li><a href="ui-dropdown.html">Dropdown</a></li>
							<li><a href="ui-popover.html">Popover</a></li>
							<li><a href="ui-progressbar.html">Progressbar</a></li>
							<li><a href="ui-tab.html">Tab</a></li>
							<li><a href="ui-typography.html">Typography</a></li>
							<li><a href="ui-pagination.html">Pagination</a></li>
							<li><a href="ui-grid.html">Grid</a></li>

						</ul>
					</li>
					<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="flaticon-045-heart"></i>
							<span class="nav-text">Plugins</span>
						</a>
						<ul aria-expanded="false">
							<li><a href="uc-select2.html">Select 2</a></li>
							<li><a href="uc-nestable.html">Nestedable</a></li>
							<li><a href="uc-noui-slider.html">Noui Slider</a></li>
							<li><a href="uc-sweetalert.html">Sweet Alert</a></li>
							<li><a href="uc-toastr.html">Toastr</a></li>
							<li><a href="map-jqvmap.html">Jqv Map</a></li>
							<li><a href="uc-lightgallery.html">Light Gallery</a></li>
						</ul>
					</li>
					<li><a href="widget-basic.html" class="ai-icon" aria-expanded="false">
							<i class="flaticon-013-checkmark"></i>
							<span class="nav-text">Widget</span>
						</a>
					</li>
					<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="flaticon-072-printer"></i>
							<span class="nav-text">Forms</span>
						</a>
						<ul aria-expanded="false">
							<li><a href="form-element.html">Form Elements</a></li>
							<li><a href="form-wizard.html">Wizard</a></li>
							<li><a href="form-editor-summernote.html">Summernote</a></li>
							<li><a href="form-pickers.html">Pickers</a></li>
							<li><a href="form-validation-jquery.html">Jquery Validate</a></li>
						</ul>
					</li>
					<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="flaticon-043-menu"></i>
							<span class="nav-text">Table</span>
						</a>
						<ul aria-expanded="false">
							<li><a href="table-bootstrap-basic.html">Bootstrap</a></li>
							<li><a href="table-datatable-basic.html">Datatable</a></li>
						</ul>
					</li>
					<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="flaticon-022-copy"></i>
							<span class="nav-text">Pages</span>
						</a>
						<ul aria-expanded="false">
							<li><a href="login.html">Login 1</a></li>
							<li><a href="page-login.html">Login 2</a></li>
							<li><a href="page-register.html">Register</a></li>
							<li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Error</a>
								<ul aria-expanded="false">
									<li><a href="page-error-400.html">Error 400</a></li>
									<li><a href="page-error-403.html">Error 403</a></li>
									<li><a href="page-error-404.html">Error 404</a></li>
									<li><a href="page-error-500.html">Error 500</a></li>
									<li><a href="page-error-503.html">Error 503</a></li>
								</ul>
							</li>
							<li><a href="page-lock-screen.html">Lock Screen</a></li>
							<li><a href="empty-page.html">Empty Page</a></li>
						</ul>
					</li>
				</ul>


				<div class="copyright">
					<p><strong>Uena Admin Dashboard</strong> © 2022 All Rights Reserved</p>
					<p class="fs-12">Made with <span class="heart"></span> by DexignZone</p>
				</div>
			</div>
		</div>
		<!--**********************************
    Sidebar end
***********************************--> <!--**********************************
            Sidebar end
        ***********************************-->



		<!--**********************************
            Content body start
        ***********************************-->
		<div class="content-body">
			<!-- row -->

			<div class="container-fluid">
				<div class="row">
					<div class="col-xl-3 col-xxl-4">
						<div class="row">
							<div class="col-xl-12 col-lg-6 col-md-6">
								<div class="card">
									<div class="card-header border-0 pb-0">
										<div class="separator"></div>
										<div class="mr-auto">
											<h4 class="card-title mb-2">Daily Target Income</h4>
											<p class="fs-12 mb-0">Lorem ipsum dolor sit</p>
										</div>
									</div>
									<div class="card-body  text-center">
										<div id="radialChart"></div>
										<h3 class="fs-28 text-black font-w500">$749.56</h3>
										<span class="mb-3 d-block">from $1,000</span>
										<p class="fs-14">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
										</p>
										<a href="post-details.html" class="btn btn-outline-primary">More Details</a>
									</div>
								</div>
							</div>
							<div class="col-xl-12 col-lg-6 col-md-6">
								<div class="card trending-menus">
									<div class="card-header border-0">
										<div class="separator"></div>
										<div class="mr-auto">
											<h4 class="text-black fs-20">Daily Trending Menus</h4>
											<p class="fs-13 mb-0">Lorem ipsum dolor</p>
										</div>

									</div>
									<div class="card-body ">
										<div class="dz-scroll height500" id="tredingMenus">
											<div class="d-flex pb-3 mb-3 tr-row align-items-center border-bottom">
												<span class="num">#1</span>
												<div class="mr-auto pr-3">
													<a href="post-details.html">
														<h2 class="text-black fs-14 font-w500">Chicken curry special
															with cucumber</h2>
													</a>
													<span class="text-black font-w600 d-inline-block mr-3">$5.6 </span>
													<span class="fs-14">Order 89x</span>
												</div>
												<img src="{{ asset('admin') }}/images/menu/Untitled-1.jpg" alt="">
											</div>
											<div class="d-flex pb-3 mb-3 tr-row align-items-center border-bottom">
												<span class="num">#2</span>
												<div class="mr-auto pr-3">
													<a href="post-details.html">
														<h2 class="text-black fs-14 font-w500">Italiano Pizza With
															Garlic</h2>
													</a>
													<span class="text-black font-w600 d-inline-block mr-3">$5.6 </span>
													<span class="fs-14">Order 89x</span>
												</div>
												<img src="{{ asset('admin') }}/images/menu/Untitled-2.jpg" alt="">
											</div>
											<div class="d-flex pb-3 mb-3 tr-row align-items-center border-bottom">
												<span class="num">#3</span>
												<div class="mr-auto pr-3">
													<a href="post-details.html">
														<h2 class="text-black fs-14 font-w500">Watermelon juice with ice
														</h2>
													</a>
													<span class="text-black font-w600 d-inline-block mr-3">$5.6 </span>
													<span class="fs-14">Order 89x</span>
												</div>
												<img src="{{ asset('admin') }}/images/menu/Untitled-3.jpg" alt="">
											</div>
											<div class="d-flex pb-3 mb-3  tr-row align-items-center border-bottom">
												<span class="num">#4</span>
												<div class="mr-auto pr-3">
													<a href="post-details.html">
														<h2 class="text-black fs-14 font-w500">Tuna Soup spinach with
															himalaya salt</h2>
													</a>
													<span class="text-black font-w600 d-inline-block mr-3">$5.6 </span>
													<span class="fs-14">Order 89x</span>
												</div>
												<img src="{{ asset('admin') }}/images/menu/Untitled-4.jpg" alt="">
											</div>
											<div class="d-flex pb-3 mb-3 tr-row align-items-center">
												<span class="num">#5</span>
												<div class="mr-auto pr-3">
													<a href="post-details.html">
														<h2 class="text-black fs-14 font-w500">Medium Spicy Spagethi
															Italiano</h2>
													</a>
													<span class="text-black font-w600 d-inline-block mr-3">$5.6 </span>
													<span class="fs-14">Order 89x</span>
												</div>
												<img src="{{ asset('admin') }}/images/menu/Untitled-5.jpg" alt="">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-9 col-xxl-8">
						<div class="row">
							<div class="col-xl-4 col-xxl-6 col-sm-6">
								<div class="card">
									<div class="card-header media border-0 pb-0">
										<div class="media-body d-flex">
											<div class="mr-4 mt-1">
												<svg class="card-ico" width="28" height="28" viewBox="0 0 28 28"
													fill="none" xmlns="http://www.w3.org/2000/svg">
													<path
														d="M3.10333 12.25H4.66666V19.8333C4.66666 20.6069 4.97395 21.3487 5.52093 21.8957C6.06791 22.4427 6.80978 22.75 7.58333 22.75H8.74999C9.52354 22.75 10.2654 22.4427 10.8124 21.8957C11.3594 21.3487 11.6667 20.6069 11.6667 19.8333V12.25H13.23C13.7436 12.25 14.2362 12.0459 14.5994 11.6827C14.9626 11.3195 15.1667 10.8269 15.1667 10.3133C15.1683 9.94246 15.0608 9.57933 14.8575 9.26914L10.4533 2.41498C10.2072 2.03206 9.86883 1.71709 9.4693 1.49895C9.06977 1.28082 8.62186 1.1665 8.16666 1.1665C7.71146 1.1665 7.26355 1.28082 6.86401 1.49895C6.46448 1.71709 6.12613 2.03206 5.87999 2.41498L1.47583 9.26914C1.27256 9.57933 1.16504 9.94246 1.16666 10.3133C1.16666 10.8269 1.3707 11.3195 1.7339 11.6827C2.09709 12.0459 2.58969 12.25 3.10333 12.25Z"
														fill="#FF720D" />
													<path
														d="M24.8966 15.75H23.3333V8.16667C23.3333 7.39312 23.026 6.65125 22.479 6.10427C21.932 5.55729 21.1902 5.25 20.4166 5.25H19.25C18.4764 5.25 17.7346 5.55729 17.1876 6.10427C16.6406 6.65125 16.3333 7.39312 16.3333 8.16667V15.75H14.77C14.5156 15.75 14.2638 15.8001 14.0288 15.8974C13.7939 15.9947 13.5804 16.1374 13.4005 16.3172C13.2207 16.4971 13.078 16.7106 12.9807 16.9455C12.8834 17.1805 12.8333 17.4323 12.8333 17.6867C12.8317 18.0575 12.9392 18.4206 13.1425 18.7308L17.5466 25.585C17.7928 25.9679 18.1311 26.2829 18.5307 26.501C18.9302 26.7192 19.3781 26.8335 19.8333 26.8335C20.2885 26.8335 20.7364 26.7192 21.1359 26.501C21.5355 26.2829 21.8738 25.9679 22.12 25.585L26.5241 18.7308C26.7274 18.4206 26.8349 18.0575 26.8333 17.6867C26.8333 17.4323 26.7832 17.1805 26.6859 16.9455C26.5886 16.7106 26.4459 16.4971 26.2661 16.3172C26.0862 16.1374 25.8727 15.9947 25.6378 15.8974C25.4028 15.8001 25.151 15.75 24.8966 15.75Z"
														fill="#FF720D" />
												</svg>
											</div>
											<div>
												<h2 class="text-black fs-32 font-w600 mb-0">892 <span
														class="text-success fs-18 font-w500">+2,7%</span></h2>
												<p class="mb-0 text-black font-w500">Orders</p>
											</div>
										</div>
									</div>
									<div class="card-body p-0">
										<div id="widgetChart1" class="dashboard-chart"></div>
									</div>
								</div>
							</div>
							<div class="col-xl-4 col-xxl-6 col-sm-6">
								<div class="card">
									<div class="card-header media border-0 pb-0">
										<div class="media-body d-flex">
											<div class="mr-4 mt-1">
												<svg class="card-ico" width="28" height="28" viewBox="0 0 28 28"
													fill="none" xmlns="http://www.w3.org/2000/svg">
													<path
														d="M14.4608 12.0692C17.0158 9.47919 16.835 5.09253 14.0816 2.72419C11.5733 0.56586 7.67079 0.711693 5.32579 3.12086C3.06246 5.44253 2.95163 9.12919 4.87079 11.5442C7.15746 14.4259 11.7716 14.7875 14.4608 12.0692Z"
														fill="#FF720D" />
													<path
														d="M4.08329 26.8334H15.75C17.78 26.8334 18.6958 25.025 18.7191 23.2342C18.7541 20.615 17.7216 17.9259 15.7091 16.1992C12.0866 13.0025 6.55663 13.3409 3.35996 16.9634C1.60996 18.9467 1.16663 21.3559 1.16663 23.9167C1.16663 25.5267 2.47329 26.8334 4.08329 26.8334Z"
														fill="#FF720D" />
													<path
														d="M25.0833 7.58333C25.0891 2.94 19.5999 0.145831 15.8374 2.85833C18.0541 5.6175 18.0541 9.54916 15.8374 12.3083C15.7849 12.3725 16.9983 12.9092 17.0916 12.9442C20.7024 14.4433 25.0774 11.5208 25.0833 7.58333Z"
														fill="#FF720D" />
													<path
														d="M19.25 13.4167C17.8908 13.4167 16.555 13.79 15.3825 14.4842C15.5983 14.3559 16.9808 15.7909 17.15 15.9775C18.8416 17.78 19.8333 20.2592 19.8333 22.75V23.9167C19.8333 24.7508 19.5766 25.5675 19.0983 26.25H23.9166C25.5266 26.25 26.8333 24.9433 26.8333 23.3333C26.8333 21.2625 26.9383 19.2617 25.9175 17.3833C25.0891 15.8609 23.7358 14.6475 22.1375 13.9825C21.2216 13.6092 20.2358 13.4167 19.25 13.4167Z"
														fill="#FF720D" />
												</svg>
											</div>
											<div>
												<h2 class="text-black fs-32 font-w600 mb-0">9,334 <span
														class="text-danger fs-18 font-w500">-0,5%</span></h2>
												<p class="mb-0 text-black font-w500">Customers</p>
											</div>
										</div>
									</div>
									<div class="card-body p-0">
										<div id="widgetChart2" class="dashboard-chart"></div>
									</div>
								</div>
							</div>
							<div class="col-xl-4 col-xxl-12">
								<div class="card">
									<div class="card-header media border-0 pb-0">
										<div class="media-body d-flex">
											<div class="mr-4 mt-1">
												<svg class="card-ico" width="28" height="28" viewBox="0 0 28 28"
													fill="none" xmlns="http://www.w3.org/2000/svg">
													<path
														d="M23.9167 10.5H4.08335C2.47252 10.5 1.16669 11.8058 1.16669 13.4167V14.5833C1.16669 16.1942 2.47252 17.5 4.08335 17.5H23.9167C25.5275 17.5 26.8334 16.1942 26.8334 14.5833V13.4167C26.8334 11.8058 25.5275 10.5 23.9167 10.5Z"
														fill="#FF720D" />
													<path
														d="M23.9167 19.8334H4.08335C2.47252 19.8334 1.16669 21.1392 1.16669 22.75V23.9167C1.16669 25.5275 2.47252 26.8334 4.08335 26.8334H23.9167C25.5275 26.8334 26.8334 25.5275 26.8334 23.9167V22.75C26.8334 21.1392 25.5275 19.8334 23.9167 19.8334Z"
														fill="#FF720D" />
													<path
														d="M23.9167 1.16669H4.08335C2.47252 1.16669 1.16669 2.47252 1.16669 4.08335V5.25002C1.16669 6.86085 2.47252 8.16669 4.08335 8.16669H23.9167C25.5275 8.16669 26.8334 6.86085 26.8334 5.25002V4.08335C26.8334 2.47252 25.5275 1.16669 23.9167 1.16669Z"
														fill="#FF720D" />
												</svg>
											</div>
											<div>
												<h2 class="text-black fs-32 font-w600 mb-0">524</h2>
												<p class="mb-0 text-black font-w500">Menu</p>
											</div>
										</div>
									</div>
									<div class="card-body p-0">
										<div id="widgetChart3" class="dashboard-chart"></div>
									</div>
								</div>
							</div>
							<div class="col-xl-12">
								<div class="card">
									<div class="card-header pb-0 flex-wrap border-0">
										<div class="separator mb-3"></div>
										<div class="mr-auto mb-3">
											<h4 class="text-black fs-20">Customer Map</h4>
											<p class="fs-13 mb-0">Lorem ipsum dolor sit amet, consectetur</p>
										</div>

										<div class="d-flex mb-3">
											<div class="custom-control custom-switch toggle-switch text-right mr-4">
												<input type="checkbox" class="custom-control-input" id="customSwitch11">
												<label class="custom-control-label fs-14 text-black pr-2"
													for="customSwitch11">Number</label>
											</div>
											<div class="custom-control custom-switch toggle-switch text-right mr-4">
												<input type="checkbox" class="custom-control-input" id="customSwitch12">
												<label class="custom-control-label fs-14 text-black pr-2"
													for="customSwitch12">Analytics</label>
											</div>
										</div>
										<div class="card-tabs mb-3">
											<ul class="nav nav-tabs" role="tablist">
												<li class="nav-item">
													<a class="nav-link active" data-toggle="tab" href="#Monthly"
														role="tab">
														Monthly
													</a>
												</li>
												<li class="nav-item">
													<a class="nav-link " data-toggle="tab" href="#Weekly" role="tab">
														Weekly
													</a>
												</li>
												<li class="nav-item">
													<a class="nav-link" data-toggle="tab" href="#Today" role="tab">
														Today
													</a>
												</li>
											</ul>
										</div>
									</div>
									<div class="card-body tab-content pb-0 pt-2">
										<div class="tab-pane fade active show" id="Monthly">
											<div id="customerMap" class="customer-chart"></div>
										</div>
										<div class="tab-pane fade" id="Weekly">
											<div id="customerMap2" class="customer-chart"></div>
										</div>
										<div class="tab-pane fade" id="Today">
											<div id="customerMap3" class="customer-chart"></div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-12">
								<div class="card">
									<div class="card-header border-0 flex-wrap pb-0">
										<div class="d-flex mb-3">
											<div class="separator"></div>
											<div class="mr-auto">
												<h4 class="text-black fs-20">Recent Order Request</h4>
												<p class="fs-13 mb-0">Lorem ipsum dolor</p>
											</div>
										</div>
										<select class="form-control style-1 default-select mb-3">
											<option>Newest</option>
											<option>Oldest</option>
										</select>
									</div>
									<div class="card-body p-0">
										<div class="table-responsive">
											<table class="table order-request">
												<tbody class="loadmore-content" id="orderRequestContent">
													<tr>
														<td>
															<div class="media align-items-center">
																<img class="mr-3 " width="87"
																	src="{{ asset('admin') }}/images/card/Untitled-1.jpg"
																	alt="DexignZone">
																<div class="media-body">
																	<h5 class="mt-0 mb-2"><a class="text-black"
																			href="ecom-product-detail.html">Sweet Cheezy
																			Pizza for Kids Meal (Small Size)</a></h5>
																	<p class="mb-0 text-primary">#0010235</p>
																</div>
															</div>
														</td>
														<td>
															<h5 class="mb-2 text-black wspace-no">Peter Parkur</h5>
															<p class="mb-0">South Corner st41256 london</p>
														</td>
														<td>
															<div
																class="d-flex align-items-center justify-content-center">
																<h4 class="mb-0 mr-3 fs-20 text-black d-inline-block">
																	$7.4</h4>
																<p class="mb-0 fs-20 d-inline-block">x3</p>
															</div>
														</td>
														<td>
															<div class="d-flex align-items-center">
																<a class="btn text-warning"
																	href="javascript:void(0);">PENDING</a>
																<div class="dropdown ml-auto">
																	<a class="btn-link" data-toggle="dropdown">
																		<svg xmlns="http://www.w3.org/2000/svg"
																			xmlns:xlink="http://www.w3.org/1999/xlink"
																			width="24px" height="24px"
																			viewBox="0 0 24 24" version="1.1">
																			<g stroke="none" stroke-width="1"
																				fill="none" fill-rule="evenodd">
																				<rect x="0" y="0" width="24"
																					height="24"></rect>
																				<circle fill="#000000" cx="12" cy="5"
																					r="2"></circle>
																				<circle fill="#000000" cx="12" cy="12"
																					r="2"></circle>
																				<circle fill="#000000" cx="12" cy="19"
																					r="2"></circle>
																			</g>
																		</svg>
																	</a>
																	<div class="dropdown-menu dropdown-menu-right">
																		<a class="dropdown-item text-black"
																			href="javascript:void(0);">
																			Accept order
																		</a>
																		<a class="dropdown-item text-black"
																			href="javascript:void(0);">
																			Reject order
																		</a>
																		<a class="dropdown-item text-black"
																			href="javascript:void(0);">
																			View Details
																		</a>
																	</div>
																</div>
															</div>
														</td>
													</tr>
													<tr>
														<td>
															<div class="media align-items-center">
																<img class="mr-3 " width="87"
																	src="{{ asset('admin') }}/images/card/Untitled-2.jpg"
																	alt="DexignZone">
																<div class="media-body">
																	<h5 class="mt-0 mb-2"><a class="text-black"
																			href="ecom-product-detail.html">Tuna soup
																			spinach with himalaya salt</a></h5>
																	<p class="mb-0 text-primary">#0010235</p>
																</div>
															</div>
														</td>
														<td>
															<h5 class="mb-2 text-black wspace-no">Jimmy Kueai</h5>
															<p class="mb-0">South Corner St.41256 London</p>
														</td>
														<td>
															<div
																class="d-flex align-items-center justify-content-center">
																<h4 class="mb-0 mr-3 fs-20 text-black d-inline-block">
																	$7.4</h4>
																<p class="mb-0 fs-20 d-inline-block">x3</p>
															</div>
														</td>
														<td>
															<div class="d-flex align-items-center">
																<a class="btn text-warning"
																	href="javascript:void(0);">PENDING</a>
																<div class="dropdown ml-auto">
																	<div class="btn-link" data-toggle="dropdown">
																		<svg xmlns="http://www.w3.org/2000/svg"
																			xmlns:xlink="http://www.w3.org/1999/xlink"
																			width="24px" height="24px"
																			viewBox="0 0 24 24" version="1.1">
																			<g stroke="none" stroke-width="1"
																				fill="none" fill-rule="evenodd">
																				<rect x="0" y="0" width="24"
																					height="24"></rect>
																				<circle fill="#000000" cx="12" cy="5"
																					r="2"></circle>
																				<circle fill="#000000" cx="12" cy="12"
																					r="2"></circle>
																				<circle fill="#000000" cx="12" cy="19"
																					r="2"></circle>
																			</g>
																		</svg>
																	</div>
																	<div class="dropdown-menu dropdown-menu-right">
																		<a class="dropdown-item text-black"
																			href="javascript:void(0);">
																			Accept order
																		</a>
																		<a class="dropdown-item text-black"
																			href="javascript:void(0);">
																			Reject order
																		</a>
																		<a class="dropdown-item text-black"
																			href="javascript:void(0);">
																			View Details
																		</a>
																	</div>
																</div>
															</div>
														</td>
													</tr>
													<tr>
														<td>
															<div class="media align-items-center">
																<img class="mr-3 " width="87"
																	src="{{ asset('admin') }}/images/card/Untitled-3.jpg"
																	alt="DexignZone">
																<div class="media-body">
																	<h5 class="mt-0 mb-2"><a class="text-black"
																			href="ecom-product-detail.html">Extreme
																			Deluxe Pizza Mozarella with Garlic</a></h5>
																	<p class="mb-0 text-primary">#0010235</p>
																</div>
															</div>
														</td>
														<td>
															<h5 class="mb-2 text-black wspace-no">Cindy Alexa</h5>
															<p class="mb-0">Blue Ocean St.41551 London</p>
														</td>
														<td>
															<div
																class="d-flex align-items-center justify-content-center">
																<h4 class="mb-0 mr-3 fs-20 text-black d-inline-block">
																	$8.2</h4>
																<p class="mb-0 fs-20 d-inline-block">x1</p>
															</div>
														</td>
														<td>
															<div class="d-flex align-items-center">
																<a class="btn text-success"
																	href="javascript:void(0);">DELIVERED</a>
																<div class="dropdown ml-auto">
																	<div class="btn-link" data-toggle="dropdown">
																		<svg xmlns="http://www.w3.org/2000/svg"
																			xmlns:xlink="http://www.w3.org/1999/xlink"
																			width="24px" height="24px"
																			viewBox="0 0 24 24" version="1.1">
																			<g stroke="none" stroke-width="1"
																				fill="none" fill-rule="evenodd">
																				<rect x="0" y="0" width="24"
																					height="24"></rect>
																				<circle fill="#000000" cx="12" cy="5"
																					r="2"></circle>
																				<circle fill="#000000" cx="12" cy="12"
																					r="2"></circle>
																				<circle fill="#000000" cx="12" cy="19"
																					r="2"></circle>
																			</g>
																		</svg>
																	</div>
																	<div class="dropdown-menu dropdown-menu-right">
																		<a class="dropdown-item text-black"
																			href="javascript:void(0);">
																			Accept order
																		</a>
																		<a class="dropdown-item text-black"
																			href="javascript:void(0);">
																			Reject order
																		</a>
																		<a class="dropdown-item text-black"
																			href="javascript:void(0);">
																			View Details
																		</a>
																	</div>
																</div>
															</div>
														</td>
													</tr>
													<tr>
														<td>
															<div class="media align-items-center">
																<img class="mr-3 " width="87"
																	src="{{ asset('admin') }}/images/card/Untitled-1.jpg"
																	alt="DexignZone">
																<div class="media-body">
																	<h5 class="mt-0 mb-2"><a class="text-black"
																			href="ecom-product-detail.html">Mozarella
																			Pizza With Random Topping</a></h5>
																	<p class="mb-0 text-primary">#0010235</p>
																</div>
															</div>
														</td>
														<td>
															<h5 class="mb-2 text-black wspace-no">Cindy Alexa</h5>
															<p class="mb-0">Blue Ocean St.41551 London</p>
														</td>
														<td>
															<div
																class="d-flex align-items-center justify-content-center">
																<h4 class="mb-0 mr-3 fs-20 text-black d-inline-block">
																	$8.2</h4>
																<p class="mb-0 fs-20 d-inline-block">x1</p>
															</div>
														</td>
														<td>
															<div class="d-flex align-items-center">
																<a class="btn text-light"
																	href="javascript:void(0);">CANCELED</a>
																<div class="dropdown ml-auto">
																	<div class="btn-link" data-toggle="dropdown">
																		<svg xmlns="http://www.w3.org/2000/svg"
																			xmlns:xlink="http://www.w3.org/1999/xlink"
																			width="24px" height="24px"
																			viewBox="0 0 24 24" version="1.1">
																			<g stroke="none" stroke-width="1"
																				fill="none" fill-rule="evenodd">
																				<rect x="0" y="0" width="24"
																					height="24"></rect>
																				<circle fill="#000000" cx="12" cy="5"
																					r="2"></circle>
																				<circle fill="#000000" cx="12" cy="12"
																					r="2"></circle>
																				<circle fill="#000000" cx="12" cy="19"
																					r="2"></circle>
																			</g>
																		</svg>
																	</div>
																	<div class="dropdown-menu dropdown-menu-right">
																		<a class="dropdown-item text-black"
																			href="javascript:void(0);">
																			Accept order
																		</a>
																		<a class="dropdown-item text-black"
																			href="javascript:void(0);">
																			Reject order
																		</a>
																		<a class="dropdown-item text-black"
																			href="javascript:void(0);">
																			View Details
																		</a>
																	</div>
																</div>
															</div>
														</td>
													</tr>
													<tr>
														<td>
															<div class="media align-items-center">
																<img class="mr-3 " width="87"
																	src="{{ asset('admin') }}/images/card/Untitled-2.jpg"
																	alt="DexignZone">
																<div class="media-body">
																	<h5 class="mt-0 mb-2"><a class="text-black"
																			href="ecom-product-detail.html">Tuna soup
																			spinach with himalaya salt</a></h5>
																	<p class="mb-0 text-primary">#0010235</p>
																</div>
															</div>
														</td>
														<td>
															<h5 class="mb-2 text-black wspace-no">Jimmy Kueai</h5>
															<p class="mb-0">Blue Ocean St.41551 London</p>
														</td>
														<td>
															<div
																class="d-flex align-items-center justify-content-center">
																<h4 class="mb-0 mr-3 fs-20 text-black d-inline-block">
																	$7.4</h4>
																<p class="mb-0 fs-20 d-inline-block">x1</p>
															</div>
														</td>
														<td>
															<div class="d-flex align-items-center">
																<a class="btn text-success"
																	href="javascript:void(0);">DELIVERED</a>
																<div class="dropdown ml-auto">
																	<div class="btn-link" data-toggle="dropdown">
																		<svg xmlns="http://www.w3.org/2000/svg"
																			xmlns:xlink="http://www.w3.org/1999/xlink"
																			width="24px" height="24px"
																			viewBox="0 0 24 24" version="1.1">
																			<g stroke="none" stroke-width="1"
																				fill="none" fill-rule="evenodd">
																				<rect x="0" y="0" width="24"
																					height="24"></rect>
																				<circle fill="#000000" cx="12" cy="5"
																					r="2"></circle>
																				<circle fill="#000000" cx="12" cy="12"
																					r="2"></circle>
																				<circle fill="#000000" cx="12" cy="19"
																					r="2"></circle>
																			</g>
																		</svg>
																	</div>
																	<div class="dropdown-menu dropdown-menu-right">
																		<a class="dropdown-item text-black"
																			href="javascript:void(0);">
																			Accept order
																		</a>
																		<a class="dropdown-item text-black"
																			href="javascript:void(0);">
																			Reject order
																		</a>
																		<a class="dropdown-item text-black"
																			href="javascript:void(0);">
																			View Details
																		</a>
																	</div>
																</div>
															</div>
														</td>
													</tr>
												</tbody>
											</table>
											<div class="card-footer border-0 pt-0 text-center">
												<a href="javascript:void(0);"
													class="btn btn-outline-primary dz-load-more" id="orderRequest"
													rel="order-request.html">View More</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--**********************************
            Content body end
        ***********************************-->


		<!--**********************************
            Footer start
        ***********************************-->

		<!--**********************************
  Footer start
***********************************-->
		<div class="footer">
			<div class="copyright">
				<p>Copyright © Designed &amp; Developed by <a href="https://dexignzone.com/"
						target="_blank">DexignZone</a> 2022</p>
			</div>
		</div>
		<!--**********************************
  Footer end
***********************************-->
		<!--**********************************
            Footer end
        ***********************************-->

		<!--**********************************
           Support ticket button start
        ***********************************-->

		<!--**********************************
           Support ticket button end
        ***********************************-->


	</div>
	<!--**********************************
        Main wrapper end
    ***********************************-->

	<!--**********************************
        Scripts
    ***********************************-->

	<script src="{{ asset('admin') }}/vendor/global/global.min.js" type="text/javascript"></script>
	<script src="{{ asset('admin') }}/vendor/bootstrap-select/dist/js/bootstrap-select.min.js" type="text/javascript"></script>
	<script src="{{ asset('admin') }}/vendor/chart.js/Chart.bundle.min.js" type="text/javascript"></script>
	<script src="{{ asset('admin') }}/vendor/peity/jquery.peity.min.js" type="text/javascript"></script>
	<script src="{{ asset('admin') }}/vendor/apexchart/apexchart.js" type="text/javascript"></script>
	<script src="{{ asset('admin') }}/js/dashboard/dashboard-1.js" type="text/javascript"></script>
	<script src="{{ asset('admin') }}/js/custom.min.js" type="text/javascript"></script>
	<script src="{{ asset('admin') }}/js/deznav-init.js" type="text/javascript"></script>
	<script src="{{ asset('admin') }}/js/demo.js" type="text/javascript"></script>
	<script src="{{ asset('admin') }}/js/styleSwitcher.js" type="text/javascript"></script>

</body>

<!-- Mirrored from uena.dexignzone.com/laravel/demo/index by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 12 Jan 2025 11:04:22 GMT -->

</html>