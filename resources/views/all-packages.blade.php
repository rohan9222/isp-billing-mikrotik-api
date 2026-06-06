<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Packages - {{ siteUrlSettings('site_name') ?? config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ site_image(siteUrlSettings('site_favicon')) }}" type="image/x-icon">
    
    @vite(['resources/sass/main-site.scss', 'resources/js/main-site.js'])

    <x-portal-dynamic-theme />
</head>
<body class="container-fluid m-0 p-0">

    <header class="all-packages-header">
        <div class="container">
            <h1>Our Internet Packages</h1>
            <p>Choose the best plan that fits your needs</p>
            <div class="back-home">
                <a href="{{ url('/') }}" style="color: #fff; text-decoration: underline;">&larr; Back to Home</a>
            </div>
        </div>
    </header>

    <section class="pricing-section">
        <div class="container">
            @php
                $regLink = $siteData?->registration_link ?? '#';
                $pkgColors = ['', 'pricing-box-2', 'pricing-box-3', ''];
            @endphp

            <div class="row justify-content-center">
                @forelse($packages as $index => $package)
                    @php $colorClass = $pkgColors[$index % 4]; @endphp
                    <div class="col-xxl-3 col-md-4 col-sm-6 d-flex">
                        <div class="pricing-box {{ $colorClass }} {{ $package->is_featured ? 'pricing-box-featured' : '' }} w-100">
                            <div class="pricing-head text-center">
                                <h6>{{ strtoupper($package->plan_label ?? $package->package) }}</h6>
                                <div class="pricing-icon mb-3">
                                    <i class="bi bi-wifi fs-2 text-primary"></i>
                                </div>
                            </div>
                            <div class="pricing-lists mb-4">
                                @if($package->speed)
                                    <h5 class="text-center">{{ $package->speed }}</h5>
                                @endif
                                <ul class="mt-3">
                                    @if($package->features && count($package->features) > 0)
                                        @foreach($package->features as $feature)
                                            <li><i class="bi bi-check-circle-fill text-success me-2"></i>{{ $feature['value'] ?? $feature }}</li>
                                        @endforeach
                                    @else
                                        <li><i class="bi bi-check-circle-fill text-success me-2"></i>24 HOURS UNLIMITED</li>
                                        <li><i class="bi bi-check-circle-fill text-success me-2"></i>Fiber Optics</li>
                                        @if($package->description) <li><i class="bi bi-check-circle-fill text-success me-2"></i>{{ $package->description }}</li> @endif
                                    @endif
                                </ul>
                            </div>
                            <div class="price mb-4 text-center">
                                <h2>{{ number_format($package->price, 0) }}৳<small class="fs-6 text-muted">/MO</small></h2>
                            </div>
                            <div class="pricing-btn text-center">
                                <a href="{{ $regLink }}" class="price-btn">Buy Now</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-md-12 text-center py-5">
                        <p class="text-muted">No packages available at the moment.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} {{ siteUrlSettings('site_name') ?? config('app.name') }}. All rights reserved.</p>
            <p class="small">{{ siteUrlSettings('site_phone') }} | {{ siteUrlSettings('site_email') }}</p>
        </div>
    </footer>

    <x-theme-customizer />
</body>
</html>
