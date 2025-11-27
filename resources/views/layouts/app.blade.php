<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#ffffff">
    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title>{{ siteUrlSettings('site_title') ?? config('app.name') }}</title>

    <link rel="shortcut icon" href="{{ siteUrlSettings('site_favicon') ?? asset('images/favicon.png') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- ===============================================-->
    <!--    Favicon-->
    <!-- ===============================================-->
    {{-- <link rel="apple-touch-icon" sizes="180x180" href="{{asset('/images/favicons/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('/images/favicons/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('/images/favicons/favicon-16x16.png')}}">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('/images/favicons/favicon.ico')}}">
    <meta name="msapplication-TileImage" content="{{asset('/images/favicons/mstile-150x150.png')}}"> --}}

    @vite(['resources/sass/app.scss', 'resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
    @livewireStyles
    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <script>
        var isRTL = JSON.parse(localStorage.getItem('isRTL'));
        if (isRTL) {
            document.querySelector('html').setAttribute('dir', 'rtl');
        } else {
            document.querySelector('html').setAttribute('dir', 'ltr');
        }

        // for sidebar collapse and expand
        if (JSON.parse(localStorage.getItem("isNavbarVerticalCollapsed"))) {
            document.documentElement.classList.add("navbar-vertical-collapsed");
        }else {
            document.documentElement.classList.remove("navbar-vertical-collapsed");
        }

        // for theme dark and light
        (() => {
            const isTheme = localStorage.getItem('theme') || 'auto';
            if (isTheme === 'auto') {
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                document.documentElement.setAttribute('data-bs-theme', prefersDark ? 'dark' : 'light');
            } else {
                document.documentElement.setAttribute('data-bs-theme', isTheme);
            }
        })();

    </script>
</head>

<body class="font-sans antialiased">
    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">
        <div class="container-fluid" data-layout="container">
        <script>
            var isFluid = JSON.parse(localStorage.getItem('isFluid'));
            if (isFluid==null || isFluid==false) {
                var container = document.querySelector('[data-layout="container"]');
                if (container) {
                    container.classList.remove('container-fluid');
                    container.classList.add('container');
                }
            }
        </script>
            @include('layouts.partials.sidenav')
            <div class="content">
                @include('layouts.partials.mobile-button-nav')

                @include('layouts.partials.topnav')
                @if (isset($header))
                    <div class="card mb-3">
                        <div class="bg-holder d-none d-lg-block bg-card" style="background-image:url({{asset('images/corner-4.png')}});">
                        </div><!--/.bg-holder-->
                        <div class="card-body position-relative">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="text-center">{{ $header }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <script>
                    var navbarPosition = localStorage.getItem('navbarPosition');
                    var navbarVertical = document.querySelector('.navbar-vertical');
                    var navbarTopVertical = document.querySelector('.content .navbar-top');
                    var navbarTop = document.querySelector('[data-layout] .navbar-top:not([data-double-top-nav');
                    var navbarDoubleTop = document.querySelector('[data-double-top-nav]');
                    var navbarTopCombo = document.querySelector('.content [data-navbar-top="combo"]');

                    if (localStorage.getItem('navbarPosition') === 'double-top') {
                        document.documentElement.classList.toggle('double-top-nav-layout');
                    }

                    if (navbarPosition === 'top') {
                        navbarTop.removeAttribute('style');
                        navbarTopVertical.remove(navbarTopVertical);
                        navbarVertical.remove(navbarVertical);
                        navbarTopCombo.remove(navbarTopCombo);
                        navbarDoubleTop.remove(navbarDoubleTop);
                    } else if (navbarPosition === 'combo') {
                        navbarVertical.removeAttribute('style');
                        navbarTopCombo.removeAttribute('style');
                        navbarTop.remove(navbarTop);
                        navbarTopVertical.remove(navbarTopVertical);
                        navbarDoubleTop.remove(navbarDoubleTop);
                    } else if (navbarPosition === 'double-top') {
                        navbarDoubleTop.removeAttribute('style');
                        navbarTopVertical.remove(navbarTopVertical);
                        navbarVertical.remove(navbarVertical);
                        navbarTop.remove(navbarTop);
                        navbarTopCombo.remove(navbarTopCombo);
                    } else {
                        navbarVertical.removeAttribute('style');
                        navbarTopVertical.removeAttribute('style');
                        navbarTop.remove(navbarTop);
                        navbarDoubleTop.remove(navbarDoubleTop);
                        navbarTopCombo.remove(navbarTopCombo);
                    }
                </script>
                    {{ $slot }}
                <footer class="footer mb-3">
                    <div class="row g-0 justify-content-between fs-10 mt-4 mb-3">
                        <div class="col-12 col-sm-auto text-center">
                            <p class="mb-0 text-600">Thank you for stay with us <span class="d-none d-sm-inline-block">| </span><br class="d-sm-none" /> 2024 &copy; <a href="https://github.com/rohan9222">Rohan</a></p>
                        </div>
                        <div class="col-12 col-sm-auto text-center">
                            <p class="mb-0 text-600">v3.22.0</p>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </main>

    <!-- ===============================================-->
    <!--    End of Main Content-->
    <!-- ===============================================-->
    @include('layouts.partials.customize')

    @stack('modals')
    @livewireScripts
</body>
    <script>
        function handleNavbarHover() {
            const navbarCollapse = document.getElementById('navbarVerticalCollapse');
                const localStorageCollapsed = localStorage.getItem('isNavbarVerticalCollapsed');

                if (navbarCollapse && localStorageCollapsed === 'true') {
                    // Remove previous listeners to avoid duplication
                    navbarCollapse.removeEventListener('mouseenter', addHoverClass);
                navbarCollapse.removeEventListener('mouseleave', removeHoverClass);

                navbarCollapse.addEventListener('mouseenter', addHoverClass);
                navbarCollapse.addEventListener('mouseleave', removeHoverClass);
            }

                    function addHoverClass() {
                        document.documentElement.classList.add('navbar-vertical-collapsed-hover');
            }

                    function removeHoverClass() {
                        document.documentElement.classList.remove('navbar-vertical-collapsed-hover');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // remove invalid feedback and error
            $('input, textarea, select').on('focus', function () {
                $(this).removeClass('is-invalid'); // remove invalid class
                $(this).nextAll('.invalid-feedback').remove(); // remove invalid feedback
            });

            handleNavbarHover();
        });

        // Run on Livewire navigate
        document.addEventListener('livewire:navigated', handleNavbarHover);

        // For Livewire SPA support
        function toggleSidebar() {
            return {
                toggleSidebar: JSON.parse(localStorage.getItem('isNavbarVerticalCollapsed')) || false,

                init() {
                    this.toggleSidebar = JSON.parse(localStorage.getItem('isNavbarVerticalCollapsed')) || false;
                    document.documentElement.classList.toggle('navbar-vertical-collapsed', this.toggleSidebar);
                    // For Livewire SPA support
                    window.addEventListener('livewire:navigated', () => {
                        this.toggleSidebar = JSON.parse(localStorage.getItem('isNavbarVerticalCollapsed')) || false;
                        document.documentElement.classList.toggle('navbar-vertical-collapsed', this.toggleSidebar);
                    });
                },

                toggle() {
                    this.toggleSidebar = !this.toggleSidebar;
                    localStorage.setItem('isNavbarVerticalCollapsed', this.toggleSidebar);
                    document.documentElement.classList.toggle('navbar-vertical-collapsed', this.toggleSidebar);
                }
            }
        }

        // For theme toggle
        function themeToggle() {
            return {
                theme: localStorage.getItem('theme') || 'auto',

                init() {
                    this.applyTheme(this.theme);

                    // For Livewire SPA support
                    window.addEventListener('livewire:navigated', () => {
                        this.applyTheme(this.theme);
                    });
                },

                setTheme(theme) {
                    this.theme = theme;
                    localStorage.setItem('theme', theme);
                    this.applyTheme(theme);
                },

                applyTheme(theme) {
                    let applied = theme;
                    if (theme === 'auto') {
                        const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                        applied = isDark ? 'dark' : 'light';
                    }
                    document.documentElement.setAttribute('data-bs-theme', applied);
                }
            }
        }

        function layoutController() {
            return {
                isFluid: JSON.parse(localStorage.getItem('isFluid')), // default is container

                initLayout() {
                    this.applyLayout();

                    // Livewire wire:navigate support
                    window.addEventListener('livewire:navigated', () => {
                        this.applyLayout();
                    });
                },

                toggleLayout() {
                    this.isFluid = !this.isFluid;
                    localStorage.setItem('isFluid', this.isFluid);
                    this.applyLayout();
                },

                applyLayout() {
                    const container = document.querySelector('[data-layout]');
                    if (container) {
                        container.classList.toggle('container', !this.isFluid);
                        container.classList.toggle('container-fluid', this.isFluid);
                    }
                }
            }
        }

        // For side nav style
        function verticalNavbarStyle() {
            return {
                isNavbarStyle: localStorage.getItem('navbarStyle') || 'transparent',

                initNavStyle() {
                    this.applyNavbarStyle(this.isNavbarStyle);

                    // For Livewire SPA support
                    window.addEventListener('livewire:navigated', () => {
                        this.applyNavbarStyle(this.isNavbarStyle);
                    });
                },

                setNavbarStyle(style) {
                    this.isNavbarStyle = style;
                    localStorage.setItem('navbarStyle', style);
                    this.applyNavbarStyle(style);
                },

                applyNavbarStyle(style) {
                    const navbarVertical = document.querySelector('.navbar-vertical');
                        // Remove previous navbar-* class
                    navbarVertical.classList.forEach((cls) => {
                        if (cls.startsWith('navbar-') && cls !== 'navbar' && cls !== 'navbar-light' && cls !== 'navbar-vertical' && cls !== 'navbar-expand-xl') {
                            navbarVertical.classList.remove(cls);
                        }
                    });

                    // Add the new class
                    if (style && style !== 'transparent') {
                        navbarVertical.classList.add(`navbar-${style}`);
                    } else {
                        // If style is 'transparent', remove all specific styles
                        navbarVertical.classList.remove('navbar-inverted', 'navbar-card', 'navbar-vibrant');
                    }
                }
            }
        }

        // For navbar position

        function navbarPosition() {
            return {
                isNavbarPosition: 'vertical', // default is vertical
                initNavPosition() {
                    this.isNavbarPosition = localStorage.getItem('navbarPosition') || 'vertical';

                    // Livewire SPA support
                    window.addEventListener('livewire:navigated', () => {
                        this.isNavbarPosition = localStorage.getItem('navbarPosition') || 'vertical';
                    });
                },

                setNavbarPosition(position) {
                    localStorage.setItem('navbarPosition', position);
                    location.reload();
                }
            }
        }

        // For RTL toggle
        function rtlController() {
            return {
                isRTL: JSON.parse(localStorage.getItem('isRTL')) || false,

                initRTL() {
                    this.applyRTL();

                    // Livewire SPA support
                    window.addEventListener('livewire:navigated', () => {
                        this.applyRTL();
                    });
                },

                toggleRTL() {
                    this.isRTL = !this.isRTL;
                    localStorage.setItem('isRTL', this.isRTL);
                    this.applyRTL();
                },

                applyRTL() {
                    document.querySelector('html').setAttribute('dir', this.isRTL ? 'rtl' : 'ltr');
                }
            }
        }

    </script>

    @stack('scripts')
</html>
