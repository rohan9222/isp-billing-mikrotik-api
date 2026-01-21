<x-app-layout>
    <x-slot name="header">
        {{ __('Dashboard') }}
    </x-slot>

    <div class="row g-2">
        @foreach ($systemOverview as $routerName => $routerData)
            @php
                // If $routerData is nested (array of arrays), take the first one
                $info = is_array($routerData[0] ?? null) ? $routerData[0] : $routerData;

                $cpuLoad = (int) filter_var($info['cpu-load'] ?? 0, FILTER_SANITIZE_NUMBER_INT);
                $cpuColor = $cpuLoad > 80 ? 'bg-danger' : ($cpuLoad > 50 ? 'bg-warning' : 'bg-success');
            @endphp

            <div class="col-12 col-md-6 col-xl-3 mb-4">
                {{-- Router Card --}}
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden h-100">
                    {{-- Header --}}
                    <div class="card-header text-white text-center p-3"
                        style="background: linear-gradient(135deg, #00d27a, #10f2c1);">
                        <div class="d-flex flex-column align-items-center">
                            <div class="rounded-circle bg-white text-success d-flex align-items-center justify-content-center mb-3"
                                style="width:70px; height:70px;">
                                <i class="bi bi-hdd-network fs-2"></i>
                            </div>
                            <h4 class="fw-bold mb-0">{{ $info['board-name'] ?? $routerName }}</h4>
                            <small class="opacity-75 text-white">
                                Router • {{ $info['platform'] ?? 'N/A' }}
                            </small>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="card-body bg-light">
                        {{-- Summary --}}
                        <div class="row text-center mb-3">
                            <div class="col">
                                <h6 class="text-muted mb-1">Uptime</h6>
                                <h5 class="fw-semibold text-dark">{{ $info['uptime'] ?? 'N/A' }}</h5>
                            </div>
                            <div class="col">
                                <h6 class="text-muted mb-1">Version</h6>
                                <h5 class="fw-semibold text-dark">{{ $info['version'] ?? 'N/A' }}</h5>
                            </div>
                        </div>

                        {{-- CPU Load --}}
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small text-muted">
                                <span>CPU Usage</span>
                                <span>{{ $cpuLoad }}%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar {{ $cpuColor }}"
                                    role="progressbar"
                                    style="width: {{ $cpuLoad }}%;"></div>
                            </div>
                        </div>

                        <hr>

                        {{-- System Info --}}
                        <div class="row small text-dark">
                            <div class="col-6 mb-2">
                                <strong>CPU:</strong>
                                <div>{{ ($info['cpu-count'] ?? '?') . ' × ' . ($info['cpu-frequency'] ?? '?') }}</div>
                            </div>
                            <div class="col-6 mb-2">
                                <strong>Memory:</strong>
                                <div>{{ ($info['free-memory'] ?? '?') . ' / ' . ($info['total-memory'] ?? '?') }}</div>
                            </div>
                            <div class="col-6 mb-2">
                                <strong>HDD:</strong>
                                <div>{{ ($info['free-hdd-space'] ?? '?') . ' / ' . ($info['total-hdd-space'] ?? '?') }}</div>
                            </div>
                            <div class="col-6 mb-2">
                                <strong>Architecture:</strong>
                                <div>{{ $info['architecture-name'] ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <hr class="my-3">

                        {{-- Footer --}}
                        <div class="text-center small text-muted">
                            <div><strong>Build:</strong> {{ $info['build-time'] ?? 'N/A' }}</div>
                            <div><strong>Platform:</strong> {{ $info['platform'] ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="col-md-4 col-xxl-3">
            <div class="card" id="customers"></div>
        </div>
        <div class="col-md-5 col-xxl-3">
            <div class="card" id="billInformation"></div>
        </div>
        <div class="col-md-12 col-xxl-6">
            <div class="card" id="income_revenue"></div>
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
                            colors: ['#008FFB', '#00E396', '#FEB019', '#FF4560', '#2ed1f9', '#775DD0'],
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
                                    'Total Collection',
                                    'Today Collection',
                                    ['Total Due', '(Except Inactive Customers)']
                                ],
                                labels: {
                                    style: {
                                        fontSize: '12px',
                                        colors: ['#008FFB', '#00E396', '#FEB019', '#FF4560', '#2ed1f9', '#775DD0']
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
