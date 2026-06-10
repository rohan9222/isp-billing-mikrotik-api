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
<body class="container-fluid m-0 p-0 position-relative overflow-x-hidden">

    {{-- Background mesh gradient blobs --}}
    <div class="gradient-blob blob-1"></div>
    <div class="gradient-blob blob-2"></div>
    <div class="gradient-blob blob-3"></div>

    <header class="all-packages-header position-relative">
        <div class="container position-relative" style="z-index: 2;">
            <h1>Our Internet Packages</h1>
            <p class="fs-5 opacity-75">Find the perfect high-speed broadband plan matching your needs</p>
            <div class="back-home mt-3">
                <a href="{{ url('/') }}" class="btn btn-outline-light rounded-pill px-4" style="transition: all 0.3s ease;">
                    <i class="bi bi-arrow-left me-2"></i>Back to Home
                </a>
            </div>
        </div>
    </header>

    <section class="pricing-section position-relative" style="z-index: 2;" 
        x-data="{
            search: '',
            selectedCategory: 'all',
            packages: [
                @foreach($packages as $package)
                {
                    id: {{ $package->id }},
                    package: '{{ addslashes($package->package) }}',
                    plan_label: '{{ addslashes($package->plan_label ?? 'Standard') }}',
                    speed: '{{ addslashes($package->speed ?? '') }}',
                    price: {{ $package->price }},
                    is_featured: {{ $package->is_featured ? 'true' : 'false' }},
                    features: [
                        @if($package->features && count($package->features) > 0)
                            @foreach($package->features as $feature)
                                '{{ addslashes($feature['value'] ?? $feature) }}',
                            @endforeach
                        @else
                            '24 HOURS UNLIMITED',
                            'Fiber Optics Support',
                            '24/7 Priority Support'
                        @endif
                    ],
                    description: '{{ addslashes($package->description ?? '') }}'
                },
                @endforeach
            ],
            get filteredPackages() {
                return this.packages.filter(p => {
                    const matchesSearch = p.package.toLowerCase().includes(this.search.toLowerCase()) || 
                                          p.plan_label.toLowerCase().includes(this.search.toLowerCase()) ||
                                          p.speed.toLowerCase().includes(this.search.toLowerCase());
                    const matchesCategory = this.selectedCategory === 'all' || p.plan_label.toLowerCase() === this.selectedCategory.toLowerCase();
                    return matchesSearch && matchesCategory;
                });
            },
            get categories() {
                const labels = new Set(this.packages.map(p => p.plan_label));
                return ['all', ...Array.from(labels).filter(Boolean)];
            }
        }">
        
        <div class="container">
            <!-- Search and Filter Bar -->
            <div class="row justify-content-center mb-5">
                <div class="col-md-6 text-center">
                    <div class="position-relative mb-4">
                        <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted">
                            <i class="bi bi-search fs-5"></i>
                        </span>
                        <input x-model="search" type="text" class="form-control form-control-lg ps-5 rounded-pill border-0 shadow-lg text-white" 
                               placeholder="Search packages (e.g. 20 Mbps, Home)..." 
                               style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); border: 1px solid var(--glass-border) !important;">
                    </div>
                    
                    <!-- Dynamic Category Pills -->
                    <div class="d-flex justify-content-center flex-wrap gap-2">
                        <template x-for="cat in categories" :key="cat">
                            <button @click="selectedCategory = cat" 
                                    :class="selectedCategory === cat ? 'active-pill' : 'inactive-pill'" 
                                    class="btn rounded-pill px-4 py-2 font-weight-bold text-uppercase fs-11"
                                    x-text="cat === 'all' ? 'All Plans' : cat">
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Dynamic Package List -->
            <div class="row justify-content-center">
                <template x-for="(package, index) in filteredPackages" :key="package.id">
                    <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-6 d-flex mb-4">
                        <div class="pricing-box w-100" :class="package.is_featured ? 'pricing-box-featured' : ''">
                            <div class="pricing-head text-center">
                                <h6 x-text="package.plan_label"></h6>
                                <div class="pricing-speed-badge">
                                    <i class="bi bi-speedometer2"></i>
                                    <span x-text="package.speed"></span>
                                </div>
                            </div>
                            
                            <div class="pricing-lists mb-4">
                                <h5 class="text-center" x-text="package.package"></h5>
                                <ul class="mt-3">
                                    <template x-for="feature in package.features">
                                        <li>
                                            <i class="bi bi-check-circle-fill"></i>
                                            <span x-text="feature"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                            
                            <div class="price mb-4 text-center">
                                <h2>
                                    <span x-text="Number(package.price).toLocaleString() + '৳'"></span>
                                    <span>/MONTH</span>
                                </h2>
                            </div>
                            
                            <div class="pricing-btn text-center">
                                <a href="javascript:void(0)" @click="$dispatch('open-purchase-modal', { packageName: package.package, price: package.price })" class="price-btn">
                                    <span>+</span>Buy Package
                                </a>
                            </div>
                        </div>
                    </div>
                </template>
                
                <!-- No Results State -->
                <div class="col-md-12 text-center py-5" x-show="filteredPackages.length === 0" x-cloak>
                    <div class="display-1 text-muted mb-3"><i class="bi bi-slash-circle"></i></div>
                    <h4 class="text-muted">No Packages Found</h4>
                    <p class="text-muted">Try adjusting your search terms or category filters.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="mt-5">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <p class="mb-0">&copy; {{ date('Y') }} {{ siteUrlSettings('site_name') ?? config('app.name') }}. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0 small text-muted">
                        <i class="bi bi-telephone-fill me-1 text-success"></i>{{ siteUrlSettings('site_phone') }} 
                        <span class="mx-2">|</span> 
                        <i class="bi bi-envelope-fill me-1 text-success"></i>{{ siteUrlSettings('site_email') }}
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <x-theme-customizer />
    
    <livewire:package-purchase-form />
</body>
</html>
