<x-app-layout>
    <x-slot name="header">
        {{ __('Dashboard') }}
    </x-slot>

    {{-- Modern Stat Cards Row --}}
    <div class="row g-3 mb-4">
        <!-- Card 1: Active Users -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm overflow-hidden h-100" style="background: linear-gradient(135deg, #4f46e5, #7c3aed); color: #fff; border-radius: 12px; transition: transform 0.3s ease;">
                <div class="card-body position-relative z-1">
                    <div class="d-flex justify-content-between align-items-sm-center">
                        <div>
                            <h6 class="text-uppercase fw-bold mb-1" style="font-size: 0.72rem; letter-spacing: 1px; opacity: 0.85;">Active PPPoE Users</h6>
                            <h3 class="fw-bold mb-0">{{ $customersData['active'] ?? 0 }} <span style="font-size: 0.85rem; font-weight: normal; opacity: 0.75;">/ {{ $customersData['total'] ?? 0 }}</span></h3>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(255,255,255,0.2);">
                            <i class="bi bi-people-fill fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-3 pt-2 border-top border-white border-opacity-25">
                        <span class="small fw-medium" style="opacity: 0.9;"><i class="bi bi-person-plus-fill me-1"></i>{{ $customersData['recent'] ?? 0 }} New this month</span>
                        <span class="small float-end" style="opacity: 0.9;"><i class="bi bi-x-circle me-1 text-light"></i>{{ $customersData['inactive'] ?? 0 }} Inactive</span>
                    </div>
                </div>
                <div class="position-absolute end-0 bottom-0" style="opacity: 0.15; transform: translate(10%, 10%); z-index: 0; pointer-events: none;">
                    <i class="bi bi-person-check-fill" style="font-size: 5.5rem;"></i>
                </div>
            </div>
        </div>

        <!-- Card 2: Today PPPoE Collection -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm overflow-hidden h-100" style="background: linear-gradient(135deg, #0ea5e9, #0284c7); color: #fff; border-radius: 12px; transition: transform 0.3s ease;">
                <div class="card-body position-relative z-1">
                    <div class="d-flex justify-content-between align-items-sm-center">
                        <div>
                            <h6 class="text-uppercase fw-bold mb-1" style="font-size: 0.72rem; letter-spacing: 1px; opacity: 0.85;">Today's PPPoE Sales</h6>
                            <h3 class="fw-bold mb-0">৳{{ number_format($billInformationData['today_paid_amount'] ?? 0, 0) }}</h3>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(255,255,255,0.2);">
                            <i class="bi bi-wallet2 fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-3 pt-2 border-top border-white border-opacity-25">
                        <span class="small fw-medium" style="opacity: 0.9;"><i class="bi bi-calendar3 me-1"></i>Total Mo: ৳{{ number_format($billInformationData['paid_amount'] ?? 0, 0) }}</span>
                        <span class="small float-end" style="opacity: 0.9;"><i class="bi bi-arrow-up-right me-1"></i>PPPoE Only</span>
                    </div>
                </div>
                <div class="position-absolute end-0 bottom-0" style="opacity: 0.15; transform: translate(10%, 10%); z-index: 0; pointer-events: none;">
                    <i class="bi bi-cash-stack" style="font-size: 5.5rem;"></i>
                </div>
            </div>
        </div>

        <!-- Card 3: Today Hotspot Sales -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm overflow-hidden h-100" style="background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; border-radius: 12px; transition: transform 0.3s ease;">
                <div class="card-body position-relative z-1">
                    <div class="d-flex justify-content-between align-items-sm-center">
                        <div>
                            <h6 class="text-uppercase fw-bold mb-1" style="font-size: 0.72rem; letter-spacing: 1px; opacity: 0.85;">Today's Hotspot Sales</h6>
                            <h3 class="fw-bold mb-0">৳{{ number_format($billInformationData['hotspot_today'] ?? 0, 0) }}</h3>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(255,255,255,0.2);">
                            <i class="bi bi-wifi fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-3 pt-2 border-top border-white border-opacity-25">
                        <span class="small fw-medium" style="opacity: 0.9;"><i class="bi bi-calendar3 me-1"></i>Total Mo: ৳{{ number_format($billInformationData['hotspot_total'] ?? 0, 0) }}</span>
                        <span class="small float-end" style="opacity: 0.9;"><i class="bi bi-arrow-up-right me-1"></i>Hotspot Only</span>
                    </div>
                </div>
                <div class="position-absolute end-0 bottom-0" style="opacity: 0.15; transform: translate(10%, 10%); z-index: 0; pointer-events: none;">
                    <i class="bi bi-router-fill" style="font-size: 5.5rem;"></i>
                </div>
            </div>
        </div>

        <!-- Card 4: Total Revenue YTD -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm overflow-hidden h-100" style="background: linear-gradient(135deg, #10b981, #059669); color: #fff; border-radius: 12px; transition: transform 0.3s ease;">
                <div class="card-body position-relative z-1">
                    <div class="d-flex justify-content-between align-items-sm-center">
                        <div>
                            <h6 class="text-uppercase fw-bold mb-1" style="font-size: 0.72rem; letter-spacing: 1px; opacity: 0.85;">Total Global Revenue</h6>
                            <h3 class="fw-bold mb-0">৳{{ number_format(($billInformationData['paid_amount'] ?? 0) + ($billInformationData['hotspot_total'] ?? 0), 0) }}</h3>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(255,255,255,0.2);">
                            <i class="bi bi-graph-up-arrow fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-3 pt-2 border-top border-white border-opacity-25">
                        <span class="small fw-semibold text-warning" style="opacity: 1;"><i class="bi bi-exclamation-triangle-fill me-1"></i>Pending Due: ৳{{ number_format($billInformationData['due_amount'] ?? 0, 0) }}</span>
                        <span class="small float-end" style="opacity: 0.9;">Total Arrears</span>
                    </div>
                </div>
                <div class="position-absolute end-0 bottom-0" style="opacity: 0.15; transform: translate(10%, 10%); z-index: 0; pointer-events: none;">
                    <i class="bi bi-bank2" style="font-size: 5.5rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        {{-- Unified Row for Routers and Graphs to allow dynamic "weight" adjustment --}}
        @foreach ($systemOverview as $routerName => $routerData)
            @php
                if (! ($routerData['status'] ?? false)) {
                    continue;
                }

                $info = $routerData['data'][0] ?? $routerData['data'] ?? [];

                $cpuLoad = (int) filter_var($info['cpu-load'] ?? 0, FILTER_SANITIZE_NUMBER_INT);
                $cpuColor = $cpuLoad > 80 ? 'bg-danger' : ($cpuLoad > 50 ? 'bg-warning' : 'bg-success');
                
                // Memory Math
                $memTotal = (int)($info['total-memory'] ?? 1);
                $memFree = (int)($info['free-memory'] ?? 0);
                $memUsed = $memTotal - $memFree;
                $memPct = $memTotal > 0 ? round(($memUsed / $memTotal) * 100) : 0;

                // HDD Math
                $hddTotal = (int)($info['total-hdd-space'] ?? 1);
                $hddFree = (int)($info['free-hdd-space'] ?? 0);
                $hddUsed = $hddTotal - $hddFree;
                $hddPct = $hddTotal > 0 ? round(($hddUsed / $hddTotal) * 100) : 0;

                $cardId = 'router_' . \Illuminate\Support\Str::slug($routerName);

                if (!function_exists('formatRouterBytesLg')) {
                    function formatRouterBytesLg($bytes) {
                        if ($bytes == 0) return '0 B';
                        $k = 1024;
                        $sizes = ['B', 'KiB', 'MiB', 'GiB', 'TiB'];
                        $i = floor(log($bytes) / log($k));
                        return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
                    }
                }
            @endphp

            <div class="col-12 col-md-6 col-lg-4 col-xxl-4 d-flex flex-fill">
                {{-- Refined Router Card with Full Details --}}
                <div class="card border-0 shadow-sm rounded-4 w-100 overflow-hidden d-flex flex-column" style="min-height: 460px; background: #ffffff; border: 1px solid rgba(0,0,0,0.08);">
                    <div class="px-3 py-2" style="background: linear-gradient(135deg, #0f172a, #1e293b); color: white;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-hdd-network fs-2 text-info me-3" style="font-size: 1.5rem;"></i>
                            <div class="flex-grow-1 overflow-hidden">
                                <h5 class="fw-bold mb-0 text-white text-truncate">{{ $info['board-name'] ?? $routerName }}</h5>
                                <div class="text-white-50 mt-1 d-flex gap-2 flex-wrap align-items-center" style="font-size: 0.7rem;">
                                    <span class="badge bg-info bg-opacity-25 text-info border border-info border-opacity-25 px-2 py-1">{{ strtoupper($info['platform'] ?? 'N/A') }}</span>
                                    <span>•</span>
                                    <span class="text-white text-opacity-75">{{ $info['architecture-name'] ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body bg-light p-0 d-flex flex-column h-100">
                        <div class="px-4 py-2 bg-white border-bottom">
                            <div class="row text-center mb-4">
                                <div class="col">
                                    <small class="text-muted d-block mb-1 fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">Uptime</small>
                                    <span class="fw-bold text-dark uptime-clock" 
                                          style="font-size: 0.85rem;" 
                                          data-uptime="{{ $info['uptime'] ?? '0s' }}">
                                        {{ str_replace(['w', 'd', 'h', 'm', 's'], ['w ', 'd ', 'h ', 'm ', 's '], $info['uptime'] ?? 'N/A') }}
                                    </span>
                                </div>
                                <div class="col border-start">
                                    <small class="text-muted d-block mb-1 fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">Version</small>
                                    <span class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $info['version'] ?? 'N/A' }}</span>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between text-dark mb-1" style="font-size:0.75rem; font-weight:700;">
                                        <span>CPU Usage</span>
                                        <span class="text-info fw-bold">{{ ($info['cpu-count'] ?? '?') . ' × ' . ($info['cpu-frequency'] ?? '?') }} <span class="text-muted fw-normal">({{ $cpuLoad }}%)</span></span>
                                    </div>
                                    <div class="progress" style="height: 6px; border-radius:10px; background: rgba(0,0,0,0.05);">
                                        <div class="progress-bar {{ $cpuColor }}" role="progressbar" style="width: {{ $cpuLoad }}%;"></div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between text-dark mb-1" style="font-size:0.75rem; font-weight:700;">
                                        <span>Memory Usage</span>
                                        <span>{{ formatRouterBytesLg($memUsed) }} / {{ formatRouterBytesLg($memTotal) }} <span class="text-muted fw-normal ms-1">({{ $memPct }}%)</span></span>
                                    </div>
                                    <div class="progress" style="height: 6px; border-radius:10px; background: rgba(0,0,0,0.05);">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $memPct }}%;"></div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between text-dark mb-1" style="font-size:0.75rem; font-weight:700;">
                                        <span>Storage Usage</span>
                                        <span>{{ formatRouterBytesLg($hddUsed) }} / {{ formatRouterBytesLg($hddTotal) }} <span class="text-muted fw-normal ms-1">({{ $hddPct }}%)</span></span>
                                    </div>
                                    <div class="progress" style="height: 6px; border-radius:10px; background: rgba(0,0,0,0.05);">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $hddPct }}%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex-grow-1 overflow-auto">
                            <div class="accordion accordion-flush" id="acc_{{ $cardId }}">
                                
                                {{-- Part 1: Platform & Architecture --}}
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed py-3 px-4 fw-bold bg-white text-info" type="button" data-bs-toggle="collapse" data-bs-target="#plat_{{ $cardId }}" style="font-size: 0.72rem;">
                                            <i class="bi bi-info-square me-2"></i> Platform & Architecture
                                        </button>
                                    </h2>
                                    <div id="plat_{{ $cardId }}" class="accordion-collapse collapse" data-bs-parent="#acc_{{ $cardId }}">
                                        <div class="accordion-body p-3 bg-white border-top border-light">
                                            <div class="d-flex flex-column gap-2" style="font-size: 0.72rem;">
                                                <div class="d-flex justify-content-between"><span>Board Name</span><span class="fw-bold">{{ $info['board-name'] ?? 'N/A' }}</span></div>
                                                <div class="d-flex justify-content-between"><span>Platform</span><span class="fw-bold">{{ $info['platform'] ?? 'N/A' }}</span></div>
                                                <div class="d-flex justify-content-between"><span>Architecture</span><span class="fw-bold text-info">{{ $info['architecture-name'] ?? 'N/A' }}</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Part 2: System & Software --}}
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed py-3 px-4 fw-bold bg-white text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#sys_{{ $cardId }}" style="font-size: 0.72rem;">
                                            <i class="bi bi-gear-wide-connected me-2"></i> System Diagnostics & Build
                                        </button>
                                    </h2>
                                    <div id="sys_{{ $cardId }}" class="accordion-collapse collapse" data-bs-parent="#acc_{{ $cardId }}">
                                        <div class="accordion-body p-3 bg-white border-top border-light">
                                            <div class="d-flex flex-column gap-2" style="font-size: 0.72rem;">
                                                <div class="d-flex justify-content-between"><span>OS Version</span><span class="fw-bold text-primary">{{ $info['version'] ?? 'N/A' }}</span></div>
                                                <div class="d-flex justify-content-between"><span>Factory OS</span><span class="fw-bold">{{ $info['factory-software'] ?? 'N/A' }}</span></div>
                                                <div class="d-flex justify-content-between"><span>Build Timestamp</span><span class="fw-bold">{{ $info['build-time'] ?? 'N/A' }}</span></div>
                                                <div class="d-flex justify-content-between"><span>Uptime</span><span class="fw-bold text-dark">{{ $info['uptime'] ?? 'N/A' }}</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Part 3: Hardware Information --}}
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed py-3 px-4 fw-bold bg-white text-warning" type="button" data-bs-toggle="collapse" data-bs-target="#hw_{{ $cardId }}" style="font-size: 0.72rem;">
                                            <i class="bi bi-gpu-card me-2"></i> Hardware Information
                                        </button>
                                    </h2>
                                    <div id="hw_{{ $cardId }}" class="accordion-collapse collapse" data-bs-parent="#acc_{{ $cardId }}">
                                        <div class="accordion-body p-3 bg-white border-top border-light">
                                            <div class="d-flex flex-column gap-2" style="font-size: 0.72rem;">
                                                <div class="d-flex justify-content-between"><span>CPU</span><span class="fw-bold">{{ $info['cpu'] ?? 'N/A' }}</span></div>
                                                <div class="d-flex justify-content-between"><span>CPU count/freq/load</span><span class="fw-bold text-info">{{ ($info['cpu-count'] ?? '?') }} / {{ ($info['cpu-frequency'] ?? '?') }} / {{ $info['cpu-load'] ?? '0' }}%</span></div>
                                                <div class="d-flex justify-content-between"><span>Hdd</span><span class="fw-bold text-dark">{{ formatRouterBytesLg($hddUsed) }} / {{ formatRouterBytesLg($hddTotal) }}</span></div>
                                                <div class="d-flex justify-content-between"><span>Write Total</span><span class="fw-bold text-warning">{{ $info['write-sect-total'] ?? '0' }}</span></div>
                                                <div class="d-flex justify-content-between"><span>Write Since Reboot</span><span class="fw-bold text-warning">{{ $info['write-sect-since-reboot'] ?? '0' }}</span></div>
                                                <div class="d-flex justify-content-between"><span>Temp / Voltage</span><span class="fw-bold text-dark"><span class="text-danger">{{ isset($info['temperature']) ? $info['temperature'].'°C' : 'N/A' }}</span> | <span class="text-primary">{{ isset($info['voltage']) ? $info['voltage'].'V' : 'N/A' }}</span></span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        {{-- Analytical Graphs Section --}}
        <div class="col-12 col-md-6 col-lg-4 col-xxl-4 d-flex flex-fill">
            <div class="card border-0 shadow-sm rounded-4 w-100 overflow-hidden d-flex flex-column" style="min-height: 460px; border: 1px solid rgba(0,0,0,0.08);">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-people-fill text-primary me-2"></i>Customer Segmentation</h6>
                </div>
                <div class="card-body p-0 d-flex align-items-center justify-content-center" id="customers"></div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4 col-xxl-4 d-flex flex-fill">
            <div class="card border-0 shadow-sm rounded-4 w-100 overflow-hidden d-flex flex-column" style="min-height: 460px; border: 1px solid rgba(0,0,0,0.08);">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-cash-stack text-success me-2"></i>Billing Overview</h6>
                </div>
                <div class="card-body p-2 d-flex flex-column justify-content-center" id="billInformation"></div>
            </div>
        </div>

        <div class="col-12 col-md-12 col-lg-12 col-xxxl-12 d-flex flex-fill">
            <div class="card border-0 shadow-sm rounded-4 w-100 overflow-hidden d-flex flex-column" style="min-height: 460px; border: 1px solid rgba(0,0,0,0.08);">
                <div class="card-header bg-white py-3 border-0 text-center">
                    <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-graph-up-arrow text-danger me-2"></i>Income & Revenue Overview</h6>
                </div>
                <div class="card-body p-3" id="income_revenue"></div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('livewire:navigated', function () {
                requestAnimationFrame(() => {
                    // for destroying existing charts
                    if (window.chart1) chart1.destroy();
                    if (window.chart2) chart2.destroy();
                    if (window.chart3) chart3.destroy();

                    // ✅ 1st chart: customers
                    const customersEl = document.querySelector("#customers");
                    if (customersEl) {
                        const customersData = @json(array_values($customersData));
                        const customers = {
                            series: customersData,
                            chart: {
                                height: 360,
                                type: 'radialBar',
                            },
                            plotOptions: {
                                radialBar: {
                                    offsetY: 0,
                                    startAngle: 0,
                                    endAngle: 270,
                                    hollow: {
                                        margin: 5,
                                        size: '30%',
                                        background: 'transparent',
                                    },
                                    dataLabels: {
                                        name: { show: false },
                                        value: { show: false }
                                    },
                                    barLabels: {
                                        enabled: true,
                                        useSeriesColors: true,
                                        offsetX: -8,
                                        fontSize: '16px',
                                        formatter: function (seriesName, opts) {
                                            return seriesName + ":  " + opts.w.globals.series[opts.seriesIndex]
                                        },
                                    },
                                }
                            },
                            labels: ['Total', 'Active', 'Pending', 'Free', 'Temporary Disable', 'Inactive', 'Recent']
                        };
                        window.chart1 = new ApexCharts(customersEl, customers);
                        chart1.render();
                    }

                    // ✅ 2nd chart: billInformation
                    const billEl = document.querySelector("#billInformation");
                    if (billEl) {
                        const billInformationData = {!! json_encode(array_values($billInformationData)) !!};
                        const billInformation = {
                            series: [{
                                name: 'Amount',
                                data: billInformationData,
                            }],
                            chart: {
                                type: 'bar',
                                height: 350,
                            },
                            plotOptions: {
                                bar: {
                                    distributed: true,
                                    columnWidth: '50%',
                                    enableShades: false,
                                    dataLabels: {
                                        position: 'top'
                                    }
                                }
                            },
                            legend: { show: false },
                            colors: ['#008FFB', '#00E396', '#FEB019', '#FF4560', '#2ed1f9', '#FF69B4', '#1E90FF', '#775DD0'],
                            dataLabels: {
                                enabled: true,
                                formatter: function (val) {
                                    return val.toFixed(2);
                                },
                                style: {
                                    colors: ['#000'],
                                },
                            },
                            yaxis: {
                                title: {
                                    text: 'Amount (in Currency)',
                                },
                                labels: {
                                    formatter: function (y) {
                                        return y.toFixed(2);
                                    }
                                }
                            },
                                xaxis: {
                                categories: [
                                    'Monthly Rent',
                                    'Previous Due',
                                    'Advance',
                                    'Total PPPoE Collection',
                                    'Today PPPoE Collection',
                                    'Total Hotspot Sales',
                                    'Today Hotspot Sales',
                                    ['Total Due', '(Except Inactive)']
                                ],
                                labels: {
                                    style: {
                                        fontSize: '12px',
                                        colors: ['#008FFB', '#00E396', '#FEB019', '#FF4560', '#2ed1f9', '#FF69B4', '#1E90FF', '#775DD0']
                                    }
                                }
                            }
                        };
                        window.chart2 = new ApexCharts(billEl, billInformation);
                        chart2.render();
                    }

                    // ✅ 3rd chart: income_revenue
                    const revenueEl = document.querySelector("#income_revenue");
                    if (revenueEl) {
                        const chartData = {!! json_encode($results) !!};
                        const cashflowData = Object.values(chartData).map(item => item.cashflow_previous_year);
                        const incomeData = Object.values(chartData).map(item => item.income_current_year);
                        const revenueData = Object.values(chartData).map(item => item.revenue_difference);

                        const income_revenue = {
                            chart: { height: 350, type: "line", stacked: false },
                            dataLabels: { enabled: false },
                            stroke: { width: [1, 1, 4] },
                            title: {
                                text: 'Income Revenue Analysis ({{ now()->subYear()->year }} - {{ now()->year }})',
                                align: 'left',
                                offsetX: 60
                            },
                            series: [
                                { name: 'Income In Previous Year', type: 'column', data: cashflowData },
                                { name: "Income In Current Year", type: 'column', data: incomeData },
                                { name: "Revenue Difference", type: 'line', data: revenueData },
                            ],
                            xaxis: {
                                categories: [
                                    "January", "February", "March", "April", "May", "June",
                                    "July", "August", "September", "October", "November", "December"
                                ]
                            },
                            yaxis: [
                                {
                                    seriesName: 'Income In Current Year',
                                    axisTicks: { show: true },
                                    axisBorder: { show: true, color: '#008FFB' },
                                    labels: { style: { colors: '#008FFB' } },
                                    title: {
                                        text: "Income In Year {{ now()->subYear()->year }}",
                                        style: { color: '#008FFB' }
                                    }
                                },
                                {
                                    seriesName: 'Income In Previous Year',
                                    opposite: true,
                                    axisTicks: { show: true },
                                    axisBorder: { show: true, color: '#00E396' },
                                    labels: { style: { colors: '#00E396' } },
                                    title: {
                                        text: "Income In Year {{ now()->year }}",
                                        style: { color: '#00E396' }
                                    }
                                },
                                {
                                    opposite: true,
                                    seriesName: 'Revenue Difference',
                                    axisTicks: { show: true },
                                    axisBorder: { show: true, color: '#FEB019' },
                                    labels: { style: { colors: '#FEB019' } },
                                    title: {
                                        text: "Revenue Difference",
                                        style: { color: '#FEB019' }
                                    }
                                }
                            ],
                            tooltip: {
                                shared: false,
                                intersect: true,
                                x: { show: false }
                            },
                            legend: {
                                horizontalAlign: "center",
                                offsetX: 40
                            }
                        };
                        window.chart3 = new ApexCharts(revenueEl, income_revenue);
                        chart3.render();
                    }

                    // ✅ 4th: initUptimeClocks
                    if (window.uptimeInterval) clearInterval(window.uptimeInterval);
                    window.uptimeInterval = setInterval(() => {
                        document.querySelectorAll('.uptime-clock').forEach(clock => {
                            let uptime = clock.getAttribute('data-uptime');
                            if (!uptime || uptime === 'N/A') return;

                            // Parse MikroTik uptime (e.g., 1w2d3h4m5s)
                            const regex = /(?:(\d+)w)?(?:(\d+)d)?(?:(\d+)h)?(?:(\d+)m)?(?:(\d+)s)?/;
                            const matches = uptime.match(regex);
                            
                            let w = parseInt(matches[1]) || 0;
                            let d = parseInt(matches[2]) || 0;
                            let h = parseInt(matches[3]) || 0;
                            let m = parseInt(matches[4]) || 0;
                            let s = parseInt(matches[5]) || 0;

                            s++;
                            if (s >= 60) { s = 0; m++; }
                            if (m >= 60) { m = 0; h++; }
                            if (h >= 24) { h = 0; d++; }
                            if (d >= 7) { d = 0; w++; }

                            // Rebuild raw data
                            let newRaw = (w ? w+'w' : '') + (d ? d+'d' : '') + (h ? h+'h' : '') + (m ? m+'m' : '') + s + 's';
                            clock.setAttribute('data-uptime', newRaw);

                            // Rebuild display string
                            let display = (w ? w+'w ' : '') + (d ? d+'d ' : '') + (h ? h+'h ' : '') + (m ? m+'m ' : '') + s + 's';
                            clock.innerText = display;
                        });
                    }, 1000);
                });
            });
        </script>
    @endpush

    @push('styles')
        <style>
            .card:hover {
                transform: translateY(-5px);
                transition: all 0.3s ease;
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            }
        </style>
    @endpush

</x-app-layout>
