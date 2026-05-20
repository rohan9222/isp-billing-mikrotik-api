
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Friends Communication</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- google fonts -->

	<!-- favicon -->
	<link rel="shortcut icon" href="https://fcnetwork24.com/img/icon.png" type="image/x-icon">
	<!-- Css link -->
	<!-- <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> -->
	<!-- <link rel="stylesheet" href="css/font-awesome.min.css"> -->
	<!-- <link rel="stylesheet" href="css/all.css"> -->
	<!-- <link rel="stylesheet" href="css/owl.carousel.css"> -->
	<!-- <link rel="stylesheet" href="css/owl.transitions.css"> -->
	<!-- <link rel="stylesheet" href="css/animate.min.css"> -->
	<!-- <link rel="stylesheet" href="css/animate.css"> -->
	<link rel="stylesheet" href="https://fcnetwork24.com/css/lightbox.css">
	<!-- <link rel="stylesheet" href="css/bootstrap.css"> -->
	<!-- <link rel="stylesheet" href="css/bootstrap.css"> -->
	<!-- <link rel="stylesheet" href="css/preloader.css"> -->
	<!-- <link rel="stylesheet" href="css/image.css"> -->
	<!-- <link rel="stylesheet" href="css/icon.css"> -->
	<!-- <link rel="stylesheet" href="css/style.css"> -->
	<link rel="stylesheet" href="https://fcnetwork24.com/style.css">
	<!-- <link rel="stylesheet" href="css/responsive.css"> -->
</head>

<body id="top" class="container-fluid m-0 p-0">

	<header id="navigation" class="navbar sticky-top animated-header navbar-expand-md bg-light">
		<div class="container text-center">
			<!-- site logo or Site brand name -->
			<a class="navbar-brand" href="">
                @if (siteUrlSettings('site_logo'))
                    <img class="d-inline-block align-text-top" style="width: 190px; height: 53px;" src="{{ site_image(siteUrlSettings('site_logo')) }}" alt="logo"/>
                @else
                    @if (siteUrlSettings('site_icon'))
                        <img class="d-inline-block align-text-top" src="{{ site_image(siteUrlSettings('site_icon')) }}" alt="" width="40" />
                        <span class="font-sans-serif text-success">{{ siteUrlSettings('site_name') ?? 'Code Pagol' }}</span>
                    @else
                        <span class="font-sans-serif text-success">{{ siteUrlSettings('site_name') ?? 'Code Pagol' }}</span>
                    @endif
                @endif
				{{-- <img src="{{ siteUrlSettings('site_logo') ?? asset('images/logo.png') }}" alt="Logo" width="164" height="40" class="d-inline-block align-text-top"> --}}
			</a>

			<!-- main nav panel -->
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="nav nav-tabs navbar-nav me-auto mb-2 mb-lg-0">
					<li class="nav-item">
					<a class="nav-link" href="#banner">Home</a>
					</li>
					<li class="nav-item">
					<a class="nav-link" href="#features">Service</a>
					</li>
					<li class="nav-item">
					<a class="nav-link" href="#gallery">Gallery</a>
					</li>
					<li class="nav-item">
					<a class="nav-link" href="#pricing-table">Price</a>
					</li>
					<li class="nav-item">
					<a class="nav-link" href="#team">Team</a>
					</li>
					<li class="nav-item">
					<a class="nav-link" href="#blog">Blog</a>
					</li>
					<li class="nav-item">
					<a class="nav-link" href="#testimonial">Testimonial</a>
					</li>
					<li class="nav-item">
					<a class="nav-link" href="#contact-form">Contact</a>
					</li>
				</ul>
			</div>

			<!-- Mobile Nav Button -->
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
		</div>
	</header>


	<div data-bs-spy="scroll" data-bs-target="#navigation" data-bs-root-margin="0px 0px -40%" data-bs-smooth-scroll="true" class="scrollspy-example bg-light rounded-2 wrapper" tabindex="0">
		<section id="banner" class="bg-success">
			<div id="carouselExampleCaptions" class="carousel slide carousel-fade" data-bs-ride="carousel">
				<div class="carousel-indicators">
					<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
					<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
					<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
					<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="3" aria-label="Slide 4"></button>
					<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="4" aria-label="Slide 5"></button>
					<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="5" aria-label="Slide 6"></button>
					<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="6" aria-label="Slide 7"></button>
					<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="7" aria-label="Slide 8"></button>
				</div>
				<div class="carousel-inner">
					<div class="carousel-item active">
						<img src="./images/slide/img0.jpg" class="img-fluid" alt="...">
						<!-- <div class="carousel-caption d-none d-md-block">
							<h5>First slide label</h5>
							<p>Some representative placeholder content for the first slide.</p>
						</div> -->
					</div>
					<div class="carousel-item">
						<img src="./images//slide/img01.jpg" class="img-fluid" alt="...">
					</div>
					<div class="carousel-item">
						<img src="./images//slide/img1.jpg" class="img-fluid" alt="...">
					</div>
					<div class="carousel-item">
						<img src="./images//slide/img2.jpg" class="img-fluid" alt="...">
					</div>
					<div class="carousel-item">
						<img src="./images//slide/img3.jpg" class="img-fluid" alt="...">
					</div>
					<div class="carousel-item">
						<img src="./images//slide/img4.jpg" class="img-fluid" alt="...">
					</div>
					<div class="carousel-item">
						<img src="./images//slide/img6.jpg" class="img-fluid" alt="...">
					</div>
					<div class="carousel-item">
						<img src="./images//slide/img7.jpg" class="img-fluid" alt="...">
					</div>

				</div>
				<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
					<span class="carousel-control-prev-icon" aria-hidden="true"></span>
					<span class="visually-hidden">Previous</span>
				</button>
				<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
					<span class="carousel-control-next-icon" aria-hidden="true"></span>
					<span class="visually-hidden">Next</span>
				</button>
			</div>
			<!-- <div class="scrolldown">
				<a id="scroll" href="#features" class="scroll"></a>
			</div> -->
		</section>

		<section id="features">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="title">
							<h6 class="text-success">Welcome to Friends Communication</h6>
							<h2>We are always Faster & Reliable</h2>
							<p>Friends Communication has come a way since its establishment in 2020. From small beginnings as a provider of Internet access to local businesses, we have grown consistently and organically as a communications provider serving a diverse portfolio of business class voice and data services.
							</p>
							<p>Our Services are</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4 col-xs-6 col-sm-6">
						<div class="feature-block text-center">
							<div class="icon-box">
								<i class="fa-solid fa-house-signal"></i>
							</div>
							<h4 class="wow fadeInUp" data-wow-delay=".3s">Home Internet</h4>
							<p class="wow fadeInUp" data-wow-delay=".5s">Lorem ipsum dolor sit amet, consectetur adipisic-<br>ing
								elit, sed do eiusmod tempor incididunt ut <br> labore et dolore magna aliqua. Ut enim ad minim</p>
						</div>
					</div>
					<div class="col-md-4 col-xs-6 col-sm-6">
						<div class="feature-block text-center">
							<div class="icon-box">
								<i class="fa-solid fa-building-circle-check"></i>
							</div>
							<h4 class="wow fadeInUp" data-wow-delay=".3s">Corporate Internet</h4>
							<p class="wow fadeInUp" data-wow-delay=".5s">Lorem ipsum dolor sit amet, consectetur adipisic-<br>ing
								elit, sed do eiusmod tempor incididunt ut <br> labore et dolore magna aliqua. Ut enim ad minim</p>
						</div>
					</div>
					<div class="col-md-4 col-xs-6 col-sm-6">
						<div class="feature-block text-center">
							<div class="icon-box">
								<i class="fa-solid fa-network-wired"></i>
							</div>
							<h4 class="wow fadeInUp" data-wow-delay=".3s">Data Connectivity</h4>
							<p class="wow fadeInUp" data-wow-delay=".5s">Lorem ipsum dolor sit amet, consectetur adipisic-<br>ing
								elit, sed do eiusmod tempor incididunt ut <br> labore et dolore magna aliqua. Ut enim ad minim</p>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- Our Valuable Clint -->

		<section id="client-logo">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="title p-0">
							<h2 class="text-success">Our Valuable Clints</h2>
						</div>
					</div>
				</div>
				<div id="Client_Logo" class="clint-carousel">
					<div class="item">
						<a href="#"><img class="img-thumbnail" src="img/clientLogo/client-logo1.jpg" alt=""></a>
					</div>
					<div class="item">
						<a href="#"><img class="img-thumbnail" src="img/clientLogo/client-logo2.jpg" alt=""></a>
					</div>
					<div>
						<a href="#"><img class="img-thumbnail" src="img/clientLogo/client-logo3.jpg" alt=""></a>
					</div>
					<div class="item">
						<a href="#"><img class="img-thumbnail" src="img/clientLogo/client-logo4.jpg" alt=""></a>
					</div>
					<div class="item">
						<a href="#"><img class="img-thumbnail" src="img/clientLogo/client-logo5.jpg" alt=""></a>
					</div>
					<div class="item">
						<a href="#"><img class="img-thumbnail" src="img/clientLogo/client-logo6.jpg" alt=""></a>
					</div>
					<div class="item">
						<a href="#"><img class="img-thumbnail" src="img/clientLogo/client-logo1.jpg" alt=""></a>
					</div>
					<div class="item">
						<a href="#"><img class="img-thumbnail" src="img/clientLogo/client-logo2.jpg" alt=""></a>
					</div>
					<div class="item">
						<a href="#"><img class="img-thumbnail" src="img/clientLogo/client-logo3.jpg" alt=""></a>
					</div>
					<div class="item">
						<a href="#"><img class="img-thumbnail" src="img/clientLogo/client-logo4.jpg" alt=""></a>
					</div>
					<div class="item">
						<a href="#"><img class="img-thumbnail" src="img/clientLogo/client-logo5.jpg" alt=""></a>
					</div>
					<div class="item">
						<a href="#"><img class="img-thumbnail" src="img/clientLogo/client-logo6.jpg" alt=""></a>
					</div>
			</div>
		</section>


		<section id="gallery" class="bg-success">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="title p-0">
							<h2>LATEST WORKS</h2>
							<!-- <p>Dantes remained confused and silent by this explanation of the <br> thoughts which had unconsciously</p> -->
						</div>
						<div class="recent-work-mixMenu">
							<ul>
								<li><button class="filter" data-filter="all">All</button></li>
								<li><button class="filter" data-filter=".category-1">Equipment</button></li>
								<li><button class="filter" data-filter=".category-2">SERVER</button></li>
								<li><button class="filter" data-filter=".category-3">Illustration</button></li>
								<li><button class="filter" data-filter=".category-4">Media</button></li>
							</ul>
						</div>

						<div class="recent-work-pic container">
							<ul id="gallery-images" class="row">
								<li class="mix category-1 col-md-2 col-sm-3 col-6" data-my-order="4">
									<a class="gallery-items-link" href="img/spliceing.jpg" data-lightbox="gallery-set" data-alt="" data-title="My caption">
										<img class="img-thumbnail" src="img/spliceing.jpg" alt="">
										<div class="overlay">
											<h3>Web design</h3>
											<i class="fa-solid fa-network-wired"></i>
										</div>
									</a>
								</li>
								<li class="mix category-1 col-md-2 col-sm-3 col-6" data-my-order="1">
									<a class="gallery-items-link" href="img/Clever.png" data-lightbox="gallery-set">
										<img class="img-thumbnail" src="img/Clever.png" alt="">
										<div class="overlay">
											<h3>Web design</h3>
											<i class="ion-ios-plus-empty"></i>
										</div>
									</a>
								</li>
								<li class="mix category-1 col-md-2 col-sm-3 col-6" data-my-order="2">
									<a class="gallery-items-link" href="img/crimping.jpg" data-lightbox="gallery-set">
										<img class="img-thumbnail" src="img/crimping.jpg" alt="">
										<div class="overlay">
											<h3>Web design</h3>
											<i class="ion-ios-plus-empty"></i>
										</div>
									</a>
								</li>
								<li class="mix category-1 col-md-2 col-sm-3 col-6" data-my-order="3">
									<a class="gallery-items-link" href="img/optical-meter.jpg" data-lightbox="gallery-set">
										<img class="img-thumbnail" src="img/optical-meter.jpg" alt="">
										<div class="overlay">
											<h3>Web design</h3>
											<i class="ion-ios-plus-empty"></i>
										</div>
									</a>
								</li>
								<li class="mix category-1 col-md-2 col-sm-3 col-6" data-my-order="5">
									<a class="gallery-items-link" href="img/ONU.jpg" data-lightbox="gallery-set">
										<img class="img-thumbnail" src="img/ONU.jpg" alt="">
										<div class="overlay">
											<h3>Web design</h3>
											<i class="ion-ios-plus-empty"></i>
										</div>
									</a>
								</li>
								<li class="mix category-1 col-md-2 col-sm-3 col-6" data-my-order="6">
									<a class="gallery-items-link" href="img/Router.png" data-lightbox="gallery-set">
										<img class="img-thumbnail" src="img/Router.png" alt="">
										<div class="overlay">
											<h3>Web design</h3>
											<i class="ion-ios-plus-empty"></i>
										</div>
									</a>
								</li>
								<li class="mix category-2 col-md-2 col-sm-3 col-6" data-my-order="6">
									<a class="gallery-items-link" href="img/server.jpg" data-lightbox="gallery-set">
										<img class="img-thumbnail" src="img/server.jpg" alt="">
										<div class="overlay">
											<h3>Web design</h3>
											<i class="ion-ios-plus-empty"></i>
										</div>
									</a>
								</li>
								<li class="mix category-2 col-md-2 col-sm-3 col-6" data-my-order="6">
									<a class="gallery-items-link" href="img/rack.jpg" data-lightbox="gallery-set">
										<img class="img-thumbnail" src="img/rack.jpg" alt="">
										<div class="overlay">
											<h3>Web design</h3>
											<i class="ion-ios-plus-empty"></i>
										</div>
									</a>
								</li>
								<li class="mix category-3 col-md-2 col-sm-3 col-6" data-my-order="6">
									<a class="gallery-items-link" href="img/Patchcord.jpeg" data-lightbox="gallery-set">
										<img class="img-thumbnail" src="img/Patchcord.jpeg" alt="">
										<div class="overlay">
											<h3>Web design</h3>
											<i class="ion-ios-plus-empty"></i>
										</div>
									</a>
								</li>
								<li class="mix category-3 col-md-2 col-sm-3 col-6" data-my-order="6">
									<a class="gallery-items-link" href="img/UPT-Cord.jpg" data-lightbox="gallery-set">
										<img class="img-thumbnail" src="img/UPT-Cord.jpg" alt="">
										<div class="overlay">
											<h3>Web design</h3>
											<i class="ion-ios-plus-empty"></i>
										</div>
									</a>
								</li>
								<li class="mix category-3 col-md-2 col-sm-3 col-6" data-my-order="6">
									<a class="gallery-items-link" href="img/utp-fiber.jpg" data-lightbox="gallery-set">
										<img class="img-thumbnail" src="img/utp-fiber.jpg" alt="">
										<div class="overlay">
											<h3>Web design</h3>
											<i class="ion-ios-plus-empty"></i>
										</div>
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- Price Table -->

		<section id="pricing-table">
			<div class="container">
				<div class="row">
					<div class="title">
						<h2>INTERNET PACKaGE PLAN</h2>
						<p class="text-success">We are offer the best Internet Package Plan Offer for You</p>
					</div>
				</div>
				<div class="row mx-auto">
					<div class="col-xxl-3 col-md-6 pb-1 d-flex">
						<div class="pricing-box mb-30">
							<div class="pricing-head">
								<h6>MINOR</h6>
								<div class="pricing-icon services-icon">
								<i class="fa fa-wifi"></i>
								</div>
							</div>
							<div class="pricing-lists mb-30 pkg-w-300" style="width:250px">
								<h5>8 Mbps</h5>
								<!--<img src="img/logos/title_line.png" alt="" style="width:45px;height: 3px;margin: 0 auto;">-->
								<ul class="mt-3">
								<li>24 HOURS UNLIMITED</li>
								<li>Fiber Optics</li>
								<li>Talk Time - N/A</li>
								<li>Free SMS - N/A</li>
								<li>OTC Fee - 3000 Taka</li>
								<li>24/7 Customer Care</li>
								</ul>
							</div>
							<div class="price mb-20">
								<h2>500৳ <span>/MONTH</span>
								</h2>
							</div>
							<div class="pricing-btn">
								<a href="https://package.fcnetwork24.com/" class="price-btn">
								<span>+</span>Get Online Register </a>
							</div>
						</div>
					</div>
					<div class="col-xxl-3 col-md-6 pb-1 d-flex">
						<div class="pricing-box pricing-box-2 mb-30">
							<div class="pricing-head">
							<h6>JUNIOR</h6>
							<div class="pricing-icon services-icon">
								<i class="fa fa-wifi"></i>
							</div>
							</div>
							<div class="pricing-lists mb-30 pkg-w-300" style="width:250px">
							<h5>10 Mbps</h5>
							<!--<img src="img/logos/title_line.png" alt="" style="width:45px;height: 3px;margin: 0 auto;">-->
							<ul class="mt-3">
								<li>24 HOURS UNLIMITED</li>
								<li>Fiber Optics</li>
								<li>Talk Time - N/A</li>
								<li>Free SMS - N/A</li>
								<li>OTC Fee - 2000 Taka</li>
								<li>24/7 Customer Care</li>
							</ul>
							</div>
							<div class="price mb-20">
							<h2>650৳ <span>/MONTH</span>
							</h2>
							</div>
							<div class="pricing-btn">
							<a href="https://package.fcnetwork24.com/" class="price-btn">
								<span>+</span>Get Online Register </a>
							</div>
						</div>
					</div>
					<div class="col-xxl-3 col-md-6 pb-1 d-flex">
						<div class="pricing-box pricing-box-3 mb-30">
							<div class="pricing-head">
							<h6>LEARNER</h6>
							<div class="pricing-icon services-icon">
								<i class="fa fa-wifi"></i>
							</div>
							</div>
							<div class="pricing-lists mb-30 pkg-w-300" style="width:250px">
							<h5>15 Mbps</h5>
							<!--<img src="img/logos/title_line.png" alt="" style="width:45px;height: 3px;margin: 0 auto;">-->
							<ul class="mt-3">
								<li>24 HOURS UNLIMITED</li>
								<li>Fiber Optics</li>
								<li>Talk Time - N/A</li>
								<li>Free SMS - N/A</li>
								<li>OTC Fee - 1000 Taka</li>
								<li>24/7 Customer Care</li>
								<!--<li class="d-none-sm">
									<div class="row">
										<div class="col-4"></div>
										<div class="col-4">
											<p style="width:300px">&nbsp; <br><br> &nbsp;</p>
										</div>
										<div class="col-4"></div>
									</div>
								</li>-->
							</ul>
							</div>
							<div class="price mb-20">
							<h2>800৳ <span>/MONTH</span>
							</h2>
							</div>
							<div class="pricing-btn">
							<a href="hhttps://package.fcnetwork24.com/" class="price-btn">
								<span>+</span>Get Online Register </a>
							</div>
						</div>
					</div>
					<div class="col-xxl-3 col-md-6 pb-1 d-flex">
						<div class="pricing-box mb-30">
							<div class="pricing-head">
							<h6>BASIC</h6>
							<div class="pricing-icon services-icon">
								<i class="fa fa-wifi"></i>
							</div>
							</div>
							<div class="pricing-lists mb-30 pkg-w-300" style="width:250px">
							<h5>20 Mbps</h5>
							<!--<img src="img/logos/title_line.png" alt="" style="width:45px;height: 3px;margin: 0 auto;">-->
							<ul class="mt-3">
								<li>24 HOURS UNLIMITED</li>
								<li>Fiber Optics</li>
								<li>Free 200 Min Talk Time /month</li>
								<li>Free 200 SMS /month</li>
											<li>OTC Fee - 500 Taka</li>
								<li>24/7 Customer Care</li>
							</ul>
							</div>
							<div class="price mb-20">
							<h2>1000৳ <span>/MONTH</span>
							</h2>
							</div>
							<div class="pricing-btn">
							<a href="https://package.fcnetwork24.com/" class="price-btn">
								<span>+</span>Get Online Register </a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
<!--
		<section id="play-video">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="block">
							<h2 class="wow fadeInUp" data-wow-delay=".3s">GET THE TEMPLATE</h2>
							<p class="wow fadeInUp" data-wow-delay=".5s">Dantes remained confused and silent by this explanation of
								the </p>
							<a href="https://vimeo.com/channels/staffpicks/145743834" class="html5lightbox" data-width=800
								data-height=400>
								<div class="button ion-ios-play-outline wow zoomIn" data-wow-delay=".3s"></div>
							</a>
						</div>
					</div>
				</div>
			</div>
		</section> -->

		<section id="team" class="bg-success">
			<div class="container">
				<div class="row">
					<div class="title">
						<h2>CREATIVE TEAM</h2>
						<p>Dantes remained confused and silent by this explanation of the <br> thoughts which had unconsciously</p>
					</div>
					<div class="col-md-12">
						<div id="team-list" class="owl-carousel">
							<div>
								<div class="block wow fadeInLeft" data-wow-delay=".9s">
									<img src="img\team-demo.png" alt="">
									<div class="team-overlay">
										<h3>ROBERT SMITH <span>Product Designer</span></h3>
										<span class="icon"><i class="ion-quote"></i></span>
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore
											et dolore magna aliqua. Ut enim ad minim veniam, quis</p>
									</div>
								</div>
							</div>
							<div>
								<div class="block wow fadeInLeft" data-wow-delay=".9s">
									<img src="img\team-demo.png" alt="">
									<div class="team-overlay">
										<h3>ROBERT SMITH <span>Product Designer</span></h3>
										<span class="icon"><i class="ion-quote"></i></span>
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore
											et dolore magna aliqua. Ut enim ad minim veniam, quis</p>
									</div>
								</div>
							</div>
							<div>
								<div class="block wow fadeInLeft" data-wow-delay=".9s">
									<img src="img\team-demo.png" alt="">
									<div class="team-overlay">
										<h3>ROBERT SMITH <span>Product Designer</span></h3>
										<span class="icon"><i class="ion-quote"></i></span>
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore
											et dolore magna aliqua. Ut enim ad minim veniam, quis</p>
									</div>
								</div>
							</div>
							<div>
								<div class="block wow fadeInLeft" data-wow-delay=".9s">
									<img src="img\team-demo.png" alt="">
									<div class="team-overlay">
										<h3>ROBERT SMITH <span>Product Designer</span></h3>
										<span class="icon"><i class="ion-quote"></i></span>
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore
											et dolore magna aliqua. Ut enim ad minim veniam, quis</p>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</section>

		<section id="blog">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="title">
							<h2>Blog</h2>
							<p>Dantes remained confused and silent by this explanation of the <br> thoughts which had unconsciously
							</p>
						</div>
						<div id="blog-post" class="owl-carousel">
							<div>
								<div class="block">
									<img src="img/blog/blog-1.jpg" alt="" class="img-thumbnail">
									<div class="content">
										<h4><a href="blog.html">Hey,This is a blog title</a></h4>
										<small>By admin / Sept 18, 2014</small>
										<p>
											Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus ex itaque repudiandae nihil
											qui debitis atque necessitatibus aliquam, consequuntur autem!
										</p>
										<a href="blog.html" class="btn btn-read">Read More</a>

									</div>
								</div>
							</div>
							<div>
								<div class="block">
									<img src="img/blog/blog-2.jpg" alt="" class="img-thumbnail">
									<div class="content">
										<h4><a href="blog.html">Hey,This is a blog title</a></h4>
										<small>By admin / Sept 18, 2014</small>
										<p>
											Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus ex itaque repudiandae nihil
											qui debitis atque necessitatibus aliquam, consequuntur autem!
										</p>
										<a href="blog.html" class="btn btn-read">Read More</a>

									</div>
								</div>
							</div>
							<div>
								<div class="block">
									<img src="img/blog/blog-3.jpg" alt="" class="img-thumbnail">
									<div class="content">
										<h4><a href="blog.html">Hey,This is a blog title</a></h4>
										<small>By admin / Sept 18, 2014</small>
										<p>
											Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus ex itaque repudiandae nihil
											qui debitis atque necessitatibus aliquam, consequuntur autem!
										</p>
										<a href="blog.html" class="btn btn-read">Read More</a>

									</div>
								</div>
							</div>
							<div>
								<div class="block">
									<img src="img/blog/blog-4.jpg" alt="" class="img-thumbnail">
									<div class="content">
										<h4><a href="blog.html">Hey,This is a blog title</a></h4>
										<small>By admin / Sept 18, 2014</small>
										<p>
											Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus ex itaque repudiandae nihil
											qui debitis atque necessitatibus aliquam, consequuntur autem!
										</p>
										<a href="blog.html" class="btn btn-read">Read More</a>

									</div>
								</div>
							</div>
							<div>
								<div class="block">
									<img src="img/blog/blog-1.jpg" alt="" class="img-thumbnail">
									<div class="content">
										<h4><a href="blog.html">Hey,This is a blog title</a></h4>
										<small>By admin / Sept 18, 2014</small>
										<p>
											Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus ex itaque repudiandae nihil
											qui debitis atque necessitatibus aliquam, consequuntur autem!
										</p>
										<a href="blog.html" class="btn btn-read">Read More</a>

									</div>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</section>




		<section id="testimonial">
			<div class="container">
				<div class="row">
					<div class="title">
						<h2>TESTIMONIAL</h2>
						<p>Dantes remained confused and silent by this explanation of the <br> thoughts which had unconsciously</p>
					</div>
					<div class="col col-md-6">
						<div class="media wow fadeInLeft" data-wow-delay=".3s">
							<div class="media-left">
								<a href="#">
									<img src="img/service-img.png" alt="">
								</a>
							</div>
							<div class="media-body">
								<a href="#">
									<h4 class="media-heading">Jonathon Andrew</h4>
								</a>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore
									et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
									aliquip ex ea commo</p>
							</div>
						</div>
					</div>
					<div class="col col-md-6">
						<div class="media wow fadeInRight" data-wow-delay=".3s">
							<div class="media-left">
								<a href="#">
									<img src="img/service-img.png" alt="">
								</a>
							</div>
							<div class="media-body">
								<a href="#">
									<h4 class="media-heading">Jonathon Andrew</h4>
								</a>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore
									et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
									aliquip ex ea commo</p>
							</div>
						</div>
					</div>
					<div class="col col-md-6">
						<div class="media wow fadeInLeft" data-wow-delay=".3s">
							<div class="media-left">
								<a href="#">
									<img src="img/service-img.png" alt="">
								</a>
							</div>
							<div class="media-body">
								<a href="#">
									<h4 class="media-heading">Jonathon Andrew</h4>
								</a>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore
									et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
									aliquip ex ea commo</p>
							</div>
						</div>
					</div>
					<div class="col col-md-6">
						<div class="media wow fadeInRight" data-wow-delay=".3s">
							<div class="media-left">
								<a href="#">
									<img src="img/service-img.png" alt="">
								</a>
							</div>
							<div class="media-body">
								<a href="#">
									<h4 class="media-heading">Jonathon Andrew</h4>
								</a>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore
									et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
									aliquip ex ea commo</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section id="contact-form">
			<div class="container">
				<div class="row">
					<div class="title">
						<h2>CONTACT US</h2>
						<p>Dantes remained confused and silent by this explanation of the <br> thoughts which had unconsciously</p>
					</div>
					<div class="col-md-6 col">
						<!-- map -->
						<div class="map">
							<div id="googleMap"></div>
						</div>
						<!--/map-->

					</div>
					<div class="col-md-6">
						<form>
							<input type="text" class="form-control" placeholder="Name">
							<input type="text" class="form-control" placeholder="Email">
							<textarea class="form-control" rows="3" placeholder="Message"></textarea>
							<button class="btn btn-default" type="submit">SEND</button>
						</form>
					</div>
				</div>
			</div>
		</section>
		<footer>
			<div class="container">
				<div class="row">
					<div class="col-10">
						<div class="">
							<a href=""><img src="img/logo.png" alt=""></a>
							<p>All rights reserved © 2020</p>
						</div>
					</div>
					<div class="col-2">
						<div class="text-light">
							<!-- hitwebcounter Code START -->
                            <h6>Our Respective Visitor</h6>
                            <div class="p-2"><img src="https://counter5.optistats.ovh/private/freecounterstat.php?c=yrq4zymn7m8gth9flg3rqlyl6b46p3yz" border="0" title="website counter code" alt="website counter code"></div>
						</div>
					</div>
				</div>
			</div>
		</footer>
	</div>

	<!-- back to top button -->
	<button type="button" class="btn btn-floating" id="btn-back-to-top">
		<i class="fa-solid fa-circle-up fa-2xl text-success"></i>
	</button>

	<!-- load Js -->
	<script src="https://fcnetwork24.com/js/jquery.js"></script>
	<script src="https://fcnetwork24.com/js/bootstrap.js"></script>
	<script src="https://fcnetwork24.com/js/slick.js"></script>
	<script src="https://fcnetwork24.com/js/jquery.mixitup.js"></script>
	<script src="https://fcnetwork24.com/js/lightbox.js"></script>
	<script src="https://fcnetwork24.com/js/script.js"></script>

</body>

</html>
