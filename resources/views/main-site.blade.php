{{-- Dynamic Main Site - Controlled from Billing Admin Panel --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $siteData?->hero_title ?? (siteUrlSettings('site_name') ?? config('app.name')) }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ siteUrlSettings('site_description') ?? '' }}">

    <link rel="shortcut icon" href="{{ site_image(siteUrlSettings('site_favicon')) }}" type="image/x-icon">

    @vite(['resources/sass/main-site.scss', 'resources/js/main-site.js'])

    <x-portal-dynamic-theme />
</head>

<body id="top" class="container-fluid m-0 p-0">

    {{-- =========================================================
         NAVBAR
    ========================================================== --}}
    <header id="navigation" class="navbar sticky-top animated-header navbar-expand-md bg-light">
        <div class="container text-center">
            {{-- Logo / Brand --}}
            <a class="navbar-brand" href="#top">
                @if (siteUrlSettings('site_logo'))
                    <img class="d-inline-block align-text-top" style="width:190px;height:53px;"
                        src="{{ site_image(siteUrlSettings('site_logo')) }}" alt="logo" />
                @else
                    @if (siteUrlSettings('site_icon'))
                        <img class="d-inline-block align-text-top" src="{{ site_image(siteUrlSettings('site_icon')) }}"
                            alt="" width="40" />
                        <span
                            class="font-sans-serif text-success">{{ siteUrlSettings('site_name') ?? config('app.name') }}</span>
                    @else
                        <span
                            class="font-sans-serif text-success">{{ siteUrlSettings('site_name') ?? config('app.name') }}</span>
                    @endif
                @endif
            </a>

            {{-- Nav Links --}}
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="nav nav-tabs navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="#banner">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#features">Service</a></li>
                    <li class="nav-item"><a class="nav-link" href="#gallery">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link" href="#pricing-table">Price</a></li>
                    <li class="nav-item"><a class="nav-link" href="#team">Team</a></li>
                    <li class="nav-item"><a class="nav-link" href="#blog">Blog</a></li>
                    <li class="nav-item"><a class="nav-link" href="#testimonial">Testimonial</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact-form">Contact</a></li>
                </ul>
            </div>

            <div class="d-flex align-items-center ms-auto ms-lg-3 me-2">
                <button id="theme-toggle" class="btn btn-link rounded-circle p-2 text-light text-decoration-none border-0" type="button" aria-label="Toggle Theme">
                    <i class="bi bi-moon-stars" id="theme-toggle-icon" style="font-size: 1.25rem;"></i>
                </button>
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </header>


    <div data-bs-spy="scroll" data-bs-target="#navigation" data-bs-root-margin="0px 0px -40%"
        data-bs-smooth-scroll="true" class="scrollspy-example bg-light rounded-2 wrapper" tabindex="0">

        {{-- =========================================================
             HERO / BANNER SLIDER
        ========================================================== --}}
        <section id="banner" class="bg-success">
            <div id="carouselExampleCaptions" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="7000">

                {{-- Indicators --}}
                @php $slides = $siteData?->hero_slides ?? []; @endphp
                @if (count($slides) > 0)
                    <div class="carousel-indicators">
                        @foreach ($slides as $index => $slide)
                            <button type="button" data-bs-target="#carouselExampleCaptions"
                                data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"
                                @if ($index === 0) aria-current="true" @endif
                                aria-label="Slide {{ $index + 1 }}"></button>
                        @endforeach
                    </div>
                @endif

                {{-- Slides --}}
                <div class="carousel-inner">
                    @if (count($slides) > 0)
                        @foreach ($slides as $index => $slide)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ isset($slide['image']) ? site_image($slide['image']) : '' }}"
                                    class="img-fluid" style="width: 100%; height: auto; object-fit: cover;"
                                    alt="{{ $slide['caption'] ?? 'Slide ' . ($index + 1) }}">
                                @if (!empty($slide['caption']))
                                    <div class="carousel-caption d-none d-md-block">
                                        <h2 class="display-4 fw-bold">{{ $slide['caption'] }}</h2>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        {{-- Fallback static slides --}}
                        <div class="carousel-item active">
                            <img src="{{ asset('images/slide/img0.jpg') }}" class="img-fluid" alt="Slide 1">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('images/slide/img1.jpg') }}" class="img-fluid" alt="Slide 2">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('images/slide/img2.jpg') }}" class="img-fluid" alt="Slide 3">
                        </div>
                    @endif
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </section>


        {{-- =========================================================
             FEATURES / SERVICES
        ========================================================== --}}
        <section id="features">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="title">
                            @if ($siteData?->about_title)
                                <h6 class="text-success">{{ $siteData->about_title }}</h6>
                            @else
                                <h6 class="text-success">Welcome to
                                    {{ siteUrlSettings('portal_name') ?? siteUrlSettings('site_name') }}</h6>
                            @endif
                            <h2>{{ $siteData?->hero_title ?? 'We are always Faster & Reliable' }}</h2>
                            @if ($siteData?->about_body)
                                <p>{!! nl2br(e($siteData->about_body)) !!}</p>
                            @elseif($siteData?->hero_subtitle)
                                <p>{{ $siteData->hero_subtitle }}</p>
                            @endif
                            <p>Our Services are</p>
                        </div>
                    </div>
                </div>

                {{-- Service Cards --}}
                @php $services = $siteData?->services ?? []; @endphp
                @if (count($services) > 0)
                    <div class="row">
                        @foreach ($services as $service)
                            <div class="col-md-4 col-xs-6 col-sm-6">
                                <div class="feature-block text-center">
                                    <div class="icon-box">
                                        <i class="{{ $service['icon'] ?? 'bi bi-wifi' }}"></i>
                                    </div>
                                    <h4 class="wow fadeInUp" data-wow-delay=".3s">{{ $service['title'] ?? '' }}</h4>
                                    <p class="wow fadeInUp" data-wow-delay=".5s">{{ $service['description'] ?? '' }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    {{-- Fallback static services --}}
                    <div class="row">
                        <div class="col-md-4 col-xs-6 col-sm-6">
                            <div class="feature-block text-center">
                                <div class="icon-box"><i class="bi bi-house-fill"></i></div>
                                <h4 class="wow fadeInUp" data-wow-delay=".3s">Home Internet</h4>
                                <p class="wow fadeInUp" data-wow-delay=".5s">High-speed broadband internet for your
                                    home. Unlimited data, 24/7 uptime.</p>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-6 col-sm-6">
                            <div class="feature-block text-center">
                                <div class="icon-box"><i class="bi bi-building-fill-check"></i></div>
                                <h4 class="wow fadeInUp" data-wow-delay=".3s">Corporate Internet</h4>
                                <p class="wow fadeInUp" data-wow-delay=".5s">Dedicated business-grade connectivity
                                    with SLA guarantees and priority support.</p>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-6 col-sm-6">
                            <div class="feature-block text-center">
                                <div class="icon-box"><i class="bi bi-hdd-network-fill"></i></div>
                                <h4 class="wow fadeInUp" data-wow-delay=".3s">Data Connectivity</h4>
                                <p class="wow fadeInUp" data-wow-delay=".5s">Fiber optic point-to-point links for
                                    enterprise and campus connectivity needs.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>

        {{-- =========================================================
            Valuable Clint
        ========================================================== --}}
        <section id="client-logo">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="title p-0">
                            <h2 class="text-success">Our Valuable Clients</h2>
                        </div>
                    </div>
                </div>
                @php
                    $clients = $siteData?->valuable_clients ?? [];
                    if (count($clients) === 0) {
                        $clients = [
                            ['name' => 'Google'],
                            ['name' => 'Microsoft'],
                            ['name' => 'Amazon'],
                            ['name' => 'Facebook'],
                            ['name' => 'Twitter'],
                            ['name' => 'Apple'],
                            ['name' => 'Intel'],
                            ['name' => 'IBM'],
                            ['name' => 'Oracle'],
                        ];
                    }
                @endphp
                <div class="marquee-container">
                    <div class="marquee-content">
                        @foreach ($clients as $client)
                            <div class="client-item-marquee">
                                @if (!empty($client['link']))
                                    <a href="{{ $client['link'] }}" target="_blank" title="{{ $client['name'] }}">
                                @endif

                                @if (!empty($client['logo']))
                                    <img class="client-logo-img" src="{{ site_image($client['logo']) }}" alt="{{ $client['name'] }}">
                                @else
                                    <div class="client-name-design">
                                        <span>{{ $client['name'] }}</span>
                                    </div>
                                @endif

                                @if (!empty($client['link']))
                                    </a>
                                @endif
                            </div>
                        @endforeach
                        {{-- Duplicate items for infinite scroll effect --}}
                        @foreach ($clients as $client)
                            <div class="client-item-marquee">
                                @if (!empty($client['link']))
                                    <a href="{{ $client['link'] }}" target="_blank" title="{{ $client['name'] }}">
                                @endif

                                @if (!empty($client['logo']))
                                    <img class="client-logo-img" src="{{ site_image($client['logo']) }}" alt="{{ $client['name'] }}">
                                @else
                                    <div class="client-name-design">
                                        <span>{{ $client['name'] }}</span>
                                    </div>
                                @endif

                                @if (!empty($client['link']))
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- =========================================================
             GALLERY
        ========================================================== --}}
        <section id="gallery" class="bg-success">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="title p-0">
                            <h2>LATEST WORKS</h2>
                        </div>
                        <div x-data="{ filter: 'all' }">
                            <div class="recent-work-mixMenu">
                                <ul>
                                    <li><button type="button" @click="filter='all'" :class="{ 'active': filter === 'all' }">All</button></li>
                                    @php $cats = $siteData?->gallery_categories ?? []; @endphp
                                    @if(count($cats) > 0)
                                        @foreach($cats as $cat)
                                            @php $catKey = $cat['key'] ?? ($cat['label'] ?? ''); @endphp
                                            <li><button type="button" @click="filter='{{ $catKey }}'" :class="{ 'active': filter === '{{ $catKey }}' }">{{ $cat['label'] ?? $cat['key'] ?? '' }}</button></li>
                                        @endforeach
                                    @else
                                        <li><button type="button" @click="filter='category-1'" :class="{ 'active': filter === 'category-1' }">Equipment</button></li>
                                        <li><button type="button" @click="filter='category-2'" :class="{ 'active': filter === 'category-2' }">SERVER</button></li>
                                        <li><button type="button" @click="filter='category-3'" :class="{ 'active': filter === 'category-3' }">Illustration</button></li>
                                        <li><button type="button" @click="filter='category-4'" :class="{ 'active': filter === 'category-4' }">Media</button></li>
                                    @endif
                                </ul>
                            </div>

                            <div class="recent-work-pic container">
                                <ul id="gallery-images" class="row">
                                    @php $galleryItems = $siteData?->gallery_items ?? []; @endphp
                                    @if (count($galleryItems) > 0)
                                        @foreach ($galleryItems as $index => $item)
                                            @php $itemCat = $item['category'] ?? 'category-1'; @endphp
                                            <li x-cloak
                                                x-show="filter === 'all' || filter === '{{ $itemCat }}'"
                                                x-transition:enter="transition ease-out duration-300"
                                                x-transition:enter-start="opacity-0 scale-90"
                                                x-transition:enter-end="opacity-100 scale-100"
                                                x-transition:leave="transition ease-in duration-200"
                                                x-transition:leave-start="opacity-100 scale-100"
                                                x-transition:leave-end="opacity-0 scale-90"
                                                class="mix {{ $itemCat }} col-md-2 col-sm-3 col-6 position-relative"
                                                data-my-order="{{ $index + 1 }}">
                                                <div class="gallery-item-wrapper position-relative">
                                                    <a class="gallery-items-link d-block" href="{{ site_image($item['image']) }}"
                                                        data-lightbox="gallery-set"
                                                        data-title="{{ $item['caption'] ?? '' }}">
                                                        <img class="img-thumbnail" src="{{ site_image($item['image']) }}"
                                                            alt="{{ $item['caption'] ?? '' }}">
                                                        <div class="overlay">
                                                            <h3>{{ $item['caption'] ?? 'View' }}</h3>
                                                            <i class="bi bi-diagram-3-fill"></i>
                                                        </div>
                                                    </a>
                                                </div>
                                            </li>
                                        @endforeach
                                    @else
                                        {{-- Fallback static gallery --}}
                                        <li x-cloak
                                            x-show="filter === 'all' || filter === 'category-1'"
                                            x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0 scale-90"
                                            x-transition:enter-end="opacity-100 scale-100"
                                            x-transition:leave="transition ease-in duration-200"
                                            x-transition:leave-start="opacity-100 scale-100"
                                            x-transition:leave-end="opacity-0 scale-90"
                                            class="mix category-1 col-md-2 col-sm-3 col-6 position-relative">
                                            <div class="gallery-item-wrapper position-relative">
                                                <a class="gallery-items-link d-block" href="images/gallery/spliceing.jpg"
                                                    data-lightbox="gallery-set">
                                                    <img class="img-thumbnail" src="images/gallery/spliceing.jpg" alt="">
                                                    <div class="overlay">
                                                        <h3>Splicing</h3><i class="bi bi-diagram-3-fill"></i>
                                                    </div>
                                                </a>
                                            </div>
                                        </li>
                                        <li x-cloak
                                            x-show="filter === 'all' || filter === 'category-1'"
                                            x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0 scale-90"
                                            x-transition:enter-end="opacity-100 scale-100"
                                            x-transition:leave="transition ease-in duration-200"
                                            x-transition:leave-start="opacity-100 scale-100"
                                            x-transition:leave-end="opacity-0 scale-90"
                                            class="mix category-1 col-md-2 col-sm-3 col-6 position-relative">
                                            <div class="gallery-item-wrapper position-relative">
                                                <a class="gallery-items-link d-block" href="images/gallery/Clever.png"
                                                    data-lightbox="gallery-set">
                                                    <img class="img-thumbnail" src="images/gallery/Clever.png" alt="">
                                                    <div class="overlay">
                                                        <h3>Clever</h3><i class="bi bi-diagram-3-fill"></i>
                                                    </div>
                                                </a>
                                            </div>
                                        </li>
                                        <li x-cloak
                                            x-show="filter === 'all' || filter === 'category-1'"
                                            x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0 scale-90"
                                            x-transition:enter-end="opacity-100 scale-100"
                                            x-transition:leave="transition ease-in duration-200"
                                            x-transition:leave-start="opacity-100 scale-100"
                                            x-transition:leave-end="opacity-0 scale-90"
                                            class="mix category-1 col-md-2 col-sm-3 col-6 position-relative">
                                            <div class="gallery-item-wrapper position-relative">
                                                <a class="gallery-items-link d-block" href="images/gallery/crimping.jpg"
                                                    data-lightbox="gallery-set">
                                                    <img class="img-thumbnail" src="images/gallery/crimping.jpg" alt="">
                                                    <div class="overlay">
                                                        <h3>Crimping</h3><i class="bi bi-diagram-3-fill"></i>
                                                    </div>
                                                </a>
                                            </div>
                                        </li>
                                        <li x-cloak
                                            x-show="filter === 'all' || filter === 'category-2'"
                                            x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0 scale-90"
                                            x-transition:enter-end="opacity-100 scale-100"
                                            x-transition:leave="transition ease-in duration-200"
                                            x-transition:leave-start="opacity-100 scale-100"
                                            x-transition:leave-end="opacity-0 scale-90"
                                            class="mix category-2 col-md-2 col-sm-3 col-6 position-relative">
                                            <div class="gallery-item-wrapper position-relative">
                                                <a class="gallery-items-link d-block" href="images/gallery/server.jpg"
                                                    data-lightbox="gallery-set">
                                                    <img class="img-thumbnail" src="images/gallery/server.jpg" alt="">
                                                    <div class="overlay">
                                                        <h3>Server</h3><i class="bi bi-server"></i>
                                                    </div>
                                                </a>
                                            </div>
                                        </li>
                                        <li x-cloak
                                            x-show="filter === 'all' || filter === 'category-2'"
                                            x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0 scale-90"
                                            x-transition:enter-end="opacity-100 scale-100"
                                            x-transition:leave="transition ease-in duration-200"
                                            x-transition:leave-start="opacity-100 scale-100"
                                            x-transition:leave-end="opacity-0 scale-90"
                                            class="mix category-2 col-md-2 col-sm-3 col-6 position-relative">
                                            <div class="gallery-item-wrapper position-relative">
                                                <a class="gallery-items-link d-block" href="images/gallery/rack.jpg"
                                                    data-lightbox="gallery-set">
                                                    <img class="img-thumbnail" src="images/gallery/rack.jpg" alt="">
                                                    <div class="overlay">
                                                        <h3>Rack</h3><i class="bi bi-server"></i>
                                                    </div>
                                                </a>
                                            </div>
                                        </li>
                                        <li x-cloak
                                            x-show="filter === 'all' || filter === 'category-3'"
                                            x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0 scale-90"
                                            x-transition:enter-end="opacity-100 scale-100"
                                            x-transition:leave="transition ease-in duration-200"
                                            x-transition:leave-start="opacity-100 scale-100"
                                            x-transition:leave-end="opacity-0 scale-90"
                                            class="mix category-3 col-md-2 col-sm-3 col-6 position-relative">
                                            <div class="gallery-item-wrapper position-relative">
                                                <a class="gallery-items-link d-block" href="images/gallery/Patchcord.jpeg"
                                                    data-lightbox="gallery-set">
                                                    <img class="img-thumbnail" src="images/gallery/Patchcord.jpeg" alt="">
                                                    <div class="overlay">
                                                        <h3>Patchcord</h3><i class="bi bi-ethernet"></i>
                                                    </div>
                                                </a>
                                            </div>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
        </section>


        {{-- =========================================================
             PRICING TABLE
        ========================================================== --}}
        <section id="pricing-table">
            <div class="container">
                <div class="row">
                    <div class="title">
                        <h2>{{ $siteData?->packages_section_title ?? 'INTERNET PACKAGE PLAN' }}</h2>
                        <p class="text-success">
                            {{ $siteData?->packages_section_subtitle ?? 'We offer the best Internet Package Plan for You' }}
                        </p>
                    </div>
                </div>

                @php
                    $regLink = $siteData?->registration_link ?? '#contact-form';
                    $pkgColors = ['', 'pricing-box-2', 'pricing-box-3', ''];
                    $packageChunks = $packages->chunk(4);
                @endphp

                <div id="pricingCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="8000">
                    <div class="carousel-inner">
                        @if ($packages->count() > 0)
                            @foreach ($packageChunks as $chunkIndex => $chunk)
                                <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                                    <div class="row justify-content-center px-lg-5">
                                        @foreach ($chunk as $index => $package)
                                            @php $colorClass = $pkgColors[$index % 4]; @endphp
                                            <div class="col-xl-3 col-md-6 pb-1 d-flex">
                                                <div class="pricing-box {{ $colorClass }} mb-30 {{ $package->is_featured ? 'pricing-box-featured' : '' }} w-100">
                                                    <div class="pricing-head">
                                                        <h6>{{ strtoupper($package->plan_label ?? $package->package) }}</h6>
                                                        <div class="pricing-icon services-icon">
                                                            <i class="bi bi-wifi"></i>
                                                        </div>
                                                    </div>
                                                    <div class="pricing-lists mb-30">
                                                        @if ($package->speed)
                                                            <h5>{{ $package->speed }}</h5>
                                                        @endif
                                                        <ul class="mt-3">
                                                            @if ($package->features && count($package->features) > 0)
                                                                @foreach ($package->features as $feature)
                                                                    <li>{{ $feature['value'] ?? $feature }}</li>
                                                                @endforeach
                                                            @else
                                                                <li>24 HOURS UNLIMITED</li>
                                                                <li>Fiber Optics</li>
                                                                <li>24/7 Customer Care</li>
                                                                @if ($package->description)
                                                                    <li>{{ $package->description }}</li>
                                                                @endif
                                                            @endif
                                                        </ul>
                                                    </div>
                                                    <div class="price mb-20">
                                                        <h2>{{ number_format($package->price, 0) }}৳
                                                            <span>/MONTH</span>
                                                        </h2>
                                                    </div>
                                                    <div class="pricing-btn">
                                                        <a href="{{ $regLink }}" class="price-btn">
                                                            <span>+</span>Get Online Register
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="carousel-item active">
                                <div class="col-md-12 text-center py-5">
                                    <p class="text-muted">No packages available. Please add packages from the billing admin panel.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                    @if ($packageChunks->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#pricingCarousel" data-bs-slide="prev" style="width: 5%;">
                            <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: rgba(0,0,0,0.5); border-radius: 50%;"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#pricingCarousel" data-bs-slide="next" style="width: 5%;">
                            <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: rgba(0,0,0,0.5); border-radius: 50%;"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    @endif
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('all-packages') }}" class="btn btn-outline-primary btn-lg rounded-pill px-5 py-2 font-weight-bold" style="border: 2px solid; font-weight: 700; transition: all 0.3s ease;">
                        View All Packages
                    </a>
                </div>
            </div>
        </section>


        {{-- =========================================================
             TEAM
        ========================================================== --}}
        <section id="team" class="bg-success">
            <div class="container">
                <div class="row">
                    <div class="title">
                        <h2>{{ $siteData?->team_title ?? 'CREATIVE TEAM' }}</h2>
                        @if ($siteData?->team_subtitle)
                            <p>{!! nl2br(e($siteData->team_subtitle)) !!}</p>
                        @endif
                    </div>

                    <div class="col-md-12">
                        @php
                            $teamMembers = $siteData?->team_members ?? [];
                            if (count($teamMembers) === 0) {
                                $teamMembers = [
                                    ['name' => 'TEAM MEMBER 1', 'role' => 'Staff', 'bio' => 'Dedicated team member committed to providing excellent service.'],
                                    ['name' => 'TEAM MEMBER 2', 'role' => 'Staff', 'bio' => 'Dedicated team member committed to providing excellent service.'],
                                    ['name' => 'TEAM MEMBER 3', 'role' => 'Staff', 'bio' => 'Dedicated team member committed to providing excellent service.'],
                                    ['name' => 'TEAM MEMBER 4', 'role' => 'Staff', 'bio' => 'Dedicated team member committed to providing excellent service.'],
                                ];
                            }
                            $teamChunks = array_chunk($teamMembers, 3);
                        @endphp

                        <div id="teamCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="8500">
                            <div class="carousel-inner">
                                @foreach ($teamChunks as $chunkIndex => $chunk)
                                    <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                                        <div class="row justify-content-center px-lg-5">
                                            @foreach ($chunk as $member)
                                                <div class="col-md-4 d-flex">
                                                    <div class="block wow fadeInLeft w-100" data-wow-delay=".3s">
                                                        <img src="{{ isset($member['image']) && $member['image'] ? site_image($member['image']) : asset('img/team-demo.png') }}"
                                                            alt="{{ $member['name'] ?? '' }}">
                                                        <div class="team-overlay">
                                                            <h3>{{ strtoupper($member['name'] ?? '') }}
                                                                <span>{{ $member['role'] ?? '' }}</span>
                                                            </h3>
                                                            <span class="icon"><i class="bi bi-chat-quote"></i></span>
                                                            <p>{{ $member['bio'] ?? '' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if (count($teamChunks) > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#teamCarousel" data-bs-slide="prev" style="width: 5%;">
                                    <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: rgba(0,0,0,0.5); border-radius: 50%;"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#teamCarousel" data-bs-slide="next" style="width: 5%;">
                                    <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: rgba(0,0,0,0.5); border-radius: 50%;"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>


        {{-- =========================================================
             BLOG
        ========================================================== --}}
        <section id="blog">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="title">
                            <h2>{{ $siteData?->blog_title ?? 'Blog' }}</h2>
                            @if ($siteData?->blog_subtitle)
                                <p>{!! nl2br(e($siteData->blog_subtitle)) !!}</p>
                            @endif
                        </div>

                        @php
                            $blogPosts = $siteData?->blog_posts ?? [];
                            if (count($blogPosts) === 0) {
                                $blogPosts = [
                                    ['title' => 'Latest News & Updates 1', 'author' => 'Admin', 'excerpt' => 'Stay updated with the latest news, offers, and updates from our network team.'],
                                    ['title' => 'Latest News & Updates 2', 'author' => 'Admin', 'excerpt' => 'Stay updated with the latest news, offers, and updates from our network team.'],
                                    ['title' => 'Latest News & Updates 3', 'author' => 'Admin', 'excerpt' => 'Stay updated with the latest news, offers, and updates from our network team.'],
                                    ['title' => 'Latest News & Updates 4', 'author' => 'Admin', 'excerpt' => 'Stay updated with the latest news, offers, and updates from our network team.'],
                                ];
                            }
                            $blogChunks = array_chunk($blogPosts, 3);
                        @endphp

                        <div id="blogCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="9000">
                            <div class="carousel-inner">
                                @foreach ($blogChunks as $chunkIndex => $chunk)
                                    <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                                        <div class="row justify-content-center px-lg-5">
                                            @foreach ($chunk as $post)
                                                <div class="col-md-4 d-flex">
                                                    <div class="block w-100">
                                                        @if (!empty($post['image']))
                                                            <img src="{{ site_image($post['image']) }}"
                                                                alt="{{ $post['title'] ?? '' }}" class="img-thumbnail">
                                                        @else
                                                            <img src="{{ asset('img/blog/blog-1.jpg') }}"
                                                                alt="{{ $post['title'] ?? '' }}" class="img-thumbnail">
                                                        @endif
                                                        <div class="content">
                                                            <h4>
                                                                <a href="{{ $post['link'] ?? '#' }}">{{ $post['title'] ?? '' }}</a>
                                                            </h4>
                                                            <small>By {{ $post['author'] ?? 'Admin' }}
                                                                @if (!empty($post['date']))
                                                                    / {{ \Carbon\Carbon::parse($post['date'])->format('M d, Y') }}
                                                                @endif
                                                            </small>
                                                            <p>{{ $post['excerpt'] ?? '' }}</p>
                                                            <a href="{{ $post['link'] ?? '#' }}" class="btn btn-read">Read More</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if (count($blogChunks) > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#blogCarousel" data-bs-slide="prev" style="width: 5%;">
                                    <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: rgba(0,0,0,0.5); border-radius: 50%;"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#blogCarousel" data-bs-slide="next" style="width: 5%;">
                                    <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: rgba(0,0,0,0.5); border-radius: 50%;"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>


        {{-- =========================================================
             TESTIMONIALS
        ========================================================== --}}
        <section id="testimonial">
            <div class="container">
                <div class="row">
                    <div class="title">
                        <h2>{{ $siteData?->testimonial_title ?? 'TESTIMONIAL' }}</h2>
                        @if ($siteData?->testimonial_subtitle)
                            <p>{!! nl2br(e($siteData->testimonial_subtitle)) !!}</p>
                        @endif
                    </div>

                    @php $testimonials = $siteData?->testimonials ?? []; @endphp
                    @if (count($testimonials) > 0)
                        @foreach ($testimonials as $testimonial)
                            <div class="col col-md-6">
                                <div class="media wow fadeInLeft" data-wow-delay=".3s">
                                    <div class="media-left">
                                        <a href="#">
                                            <img src="{{ !empty($testimonial['image']) ? site_image($testimonial['image']) : asset('img/service-img.png') }}"
                                                alt="{{ $testimonial['name'] ?? '' }}">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <a href="#">
                                            <h4 class="media-heading">{{ $testimonial['name'] ?? '' }}</h4>
                                        </a>
                                        <p>{{ $testimonial['message'] ?? '' }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        {{-- Fallback testimonials --}}
                        @foreach (['Satisfied Customer', 'Happy Client', 'Regular User', 'Business Owner'] as $name)
                            <div class="col col-md-6">
                                <div class="media wow fadeInLeft" data-wow-delay=".3s">
                                    <div class="media-left">
                                        <a href="#"><img src="{{ asset('img/service-img.png') }}"
                                                alt=""></a>
                                    </div>
                                    <div class="media-body">
                                        <a href="#">
                                            <h4 class="media-heading">{{ $name }}</h4>
                                        </a>
                                        <p>Excellent internet service with fast speeds and reliable uptime. Customer
                                            support is always responsive and helpful. Highly recommended!</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </section>


        {{-- =========================================================
             CONTACT
        ========================================================== --}}
        <section id="contact-form">
            <div class="container">
                <div class="row">
                    <div class="title">
                        <h2>{{ $siteData?->contact_title ?? 'CONTACT US' }}</h2>
                        @if ($siteData?->contact_subtitle)
                            <p>{!! nl2br(e($siteData->contact_subtitle)) !!}</p>
                        @endif
                    </div>

                    <div class="col-md-6 col">
                        <div class="map">
                            <div id="googleMap">
                                @if ($siteData?->google_map_embed)
                                    <iframe src="{{ $siteData->google_map_embed }}" width="600" height="450"
                                        style="border:0;" allowfullscreen="" loading="lazy"
                                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                                @elseif(siteUrlSettings('site_map'))
                                    <iframe src="{{ siteUrlSettings('site_map') }}" width="600" height="450"
                                        style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                                @else
                                    <iframe
                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d97559.35009863286!2d90.89949961876307!3d24.672873925245927!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3756e83b9c19e2e5%3A0xa7695289d8c1a5c1!2sMadan%20Upazila!5e0!3m2!1sen!2sbd!4v1770660584969!5m2!1sen!2sbd"
                                        width="600" height="450" style="border:0;" allowfullscreen=""
                                        loading="lazy"></iframe>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <livewire:CommentSubmit />
                    </div>
                </div>
            </div>
        </section>


        {{-- =========================================================
             FOOTER
        ========================================================== --}}
        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div>
                            <a href="#top">
                                @if (siteUrlSettings('site_logo'))
                                    <img class="d-inline-block align-text-top mb-3" style="max-width: 190px;"
                                        src="{{ site_image(siteUrlSettings('site_logo')) }}" alt="logo" />
                                @else
                                    <h3 class="text-success fw-bold">
                                        {{ siteUrlSettings('site_name') ?? config('app.name') }}</h3>
                                @endif
                            </a>
                            <p class="mb-1"><i
                                    class="bi bi-geo-alt me-2"></i>{{ siteUrlSettings('site_address') ?? 'Our Head Office' }}
                            </p>
                            <p class="mb-1"><i
                                    class="bi bi-telephone me-2"></i>{{ siteUrlSettings('site_phone') ?? '01700000000' }}
                            </p>
                            <p class="mb-3"><i
                                    class="bi bi-envelope me-2"></i>{{ siteUrlSettings('site_email') ?? 'support@example.com' }}
                            </p>

                            <p class="text-muted small">
                                {{ $siteData?->footer_copyright ?? 'All rights reserved © ' . date('Y') }}</p>

                            {{-- Social Links --}}
                            @php
                                $fb = siteUrlSettings('site_facebook');
                                $tw = siteUrlSettings('site_twitter');
                                $ig = siteUrlSettings('site_instagram');
                                $yt = siteUrlSettings('site_youtube');
                                $wa = siteUrlSettings('site_whatsapp');
                            @endphp
                            <div class="mt-2 d-flex gap-3">
                                @if ($fb)
                                    <a href="{{ $fb }}" target="_blank" class="social-link"><i
                                            class="bi bi-facebook"></i></a>
                                @endif
                                @if ($tw)
                                    <a href="{{ $tw }}" target="_blank" class="social-link"><i
                                            class="bi bi-twitter-x"></i></a>
                                @endif
                                @if ($ig)
                                    <a href="{{ $ig }}" target="_blank" class="social-link"><i
                                            class="bi bi-instagram"></i></a>
                                @endif
                                @if ($yt)
                                    <a href="{{ $yt }}" target="_blank" class="social-link"><i
                                            class="bi bi-youtube"></i></a>
                                @endif
                                @if ($wa)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $wa) }}" target="_blank"
                                        class="social-link"><i class="bi bi-whatsapp"></i></a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="text-light">
                            <h6>Our Respective Visitor</h6>
                            <div class="p-2">
                                <img src="https://counter5.optistats.ovh/private/freecounterstat.php?c=yrq4zymn7m8gth9flg3rqlyl6b46p3yz"
                                    border="0" title="visitor counter" alt="visitor counter">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

    </div>{{-- end scrollspy wrapper --}}

    {{-- Back to Top --}}
    <button type="button" class="btn btn-floating" id="btn-back-to-top">
        <i class="bi bi-arrow-up-circle-fill text-success" style="font-size: 2.2rem; color: #06b6d4 !important;"></i>
    </button>

    {{-- Scripts --}}
    {{-- <script src="https://fcnetwork24.com/js/jquery.js"></script>
    <script src="https://fcnetwork24.com/js/bootstrap.js"></script>
    <script src="https://fcnetwork24.com/js/slick.js"></script>
    <script src="https://fcnetwork24.com/js/jquery.mixitup.js"></script>
    <script src="https://fcnetwork24.com/js/lightbox.js"></script>
    <script src="https://fcnetwork24.com/js/script.js"></script> --}}

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const themeToggle = document.getElementById('theme-toggle');
            const themeToggleIcon = document.getElementById('theme-toggle-icon');
            
            function updateToggleIcon(isLight) {
                if (isLight) {
                    themeToggleIcon.className = 'bi bi-sun';
                    themeToggle.classList.remove('text-light');
                    themeToggle.classList.add('text-dark');
                } else {
                    themeToggleIcon.className = 'bi bi-moon-stars';
                    themeToggle.classList.remove('text-dark');
                    themeToggle.classList.add('text-light');
                }
            }
            
            // Initial setup
            const isLight = document.documentElement.classList.contains('theme-light');
            updateToggleIcon(isLight);
            
            themeToggle.addEventListener('click', function () {
                const currentlyLight = document.documentElement.classList.toggle('theme-light');
                localStorage.setItem('site-theme', currentlyLight ? 'light' : 'dark');
                updateToggleIcon(currentlyLight);
            });
        });
    </script>
    <x-theme-customizer />
</body>

</html>
