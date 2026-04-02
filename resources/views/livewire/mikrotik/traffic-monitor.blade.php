<div class="zoom-in">
    <x-slot name="header">{{ __('Live Traffic Monitor') }}</x-slot>

    <div class="row g-3">
        <div class="col-lg-3">
            <div class="card h-100">
                <div class="card-header bg-primary text-white"><i class="bi bi-gear-fill me-1"></i>Settings</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">1. Select Router</label>
                        <select class="form-select" wire:model.live="selectedRouter">
                            <option value="">-- Choose Router --</option>
                            @foreach($routers as $r)
                                <option value="{{ $r->router_name }}">{{ $r->router_name }} ({{ $r->ip_address }})</option>
                            @endforeach
                        </select>
                    </div>

                    @if($selectedRouter)
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">2. Select Interface / User</label>
                        <select class="form-select shadow-sm" wire:model.live="selectedInterface">
                            <option value="">-- Choose Interface --</option>
                            @foreach($interfaces as $iface)
                                <option value="{{ $iface }}">
                                    @if(str_starts_with($iface, '<pppoe-'))
                                        👤 User: {{ str_replace(['<pppoe-', '>'], '', $iface) }}
                                    @elseif(str_starts_with($iface, 'ether'))
                                        🌐 Port: {{ $iface }}
                                    @else
                                        {{ $iface }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted mt-1 d-block"><i class="bi bi-info-circle"></i> Tip: Select a PPPoE interface to monitor a specific user's live traffic.</small>
                    </div>
                    @endif

                    @if($selectedInterface)
                    <div class="mt-4 pt-3 border-top">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-success"><i class="bi bi-arrow-down-circle-fill me-1"></i>Download</span>
                            <strong class="text-success fs-5" id="rx-label">0 Mbps</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-primary"><i class="bi bi-arrow-up-circle-fill me-1"></i>Upload</span>
                            <strong class="text-primary fs-5" id="tx-label">0 Mbps</strong>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-activity me-1"></i>Real-time Traffic Graph @if($selectedInterface) - <strong>{{ $selectedInterface }}</strong> @endif</span>
                    <span wire:loading wire:target="poll" class="spinner-grow spinner-grow-sm text-success" role="status"></span>
                </div>
                <div class="card-body">
                    @if(!$selectedRouter || !$selectedInterface)
                        <div class="alert alert-info d-flex align-items-center justify-content-center" style="height: 300px;">
                            <div><i class="bi bi-info-circle fs-3 d-block text-center mb-2"></i>Please select a router and interface to begin monitoring traffic.</div>
                        </div>
                    @else
                        <!-- Hidden div to trigger polling every 2 seconds -->
                        <div wire:poll.2000ms="poll" class="d-none"></div>
                        <!-- ApexChart container -->
                        <div wire:ignore>
                            <div id="traffic-chart" style="width: 100%; height: 350px;"></div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            let chart;
            let dataRx = [];
            let dataTx = [];
            let maxPoints = 900; // Cap at 30 minutes (30 * 60 / 2)
            let initialPoints = 60; // Start with 2 minutes history (2 * 60 / 2)

            function initChart() {
                if (chart) chart.destroy();
                
                dataRx = [];
                dataTx = [];
                let now = new Date().getTime();
                for(let i = initialPoints; i > 0; i--) {
                    let ts = now - (i * 2000); // Backfill initial 2 mins
                    dataRx.push([ts, 0]);
                    dataTx.push([ts, 0]);
                }

                var options = {
                    series: [
                        { name: 'Download', data: dataRx },
                        { name: 'Upload', data: dataTx }
                    ],
                    chart: {
                        type: 'area',
                        height: 350,
                        animations: { 
                            enabled: true, 
                            easing: 'linear', 
                            dynamicAnimation: { speed: 2000 } 
                        },
                        toolbar: { show: false },
                        zoom: { enabled: false }
                    },
                    colors: ['#198754', '#0d6efd'],
                    dataLabels: { enabled: false },
                    stroke: { curve: 'smooth', width: 2 },
                    fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 100] } },
                    xaxis: {
                        type: 'datetime',
                        // Range is intentionally omitted here so the graph auto-expands from 2 mins up to 30 mins
                        labels: { 
                            show: true,
                            datetimeUTC: false,
                            format: 'HH:mm:ss',
                            style: { colors: '#6c757d' }
                        },
                        axisBorder: { show: true, color: '#dee2e6' },
                        axisTicks: { show: true, color: '#dee2e6' }
                    },
                    yaxis: {
                        labels: { formatter: function (value) { return value.toFixed(2) + " Mbps"; } },
                        min: 0,
                        max: function(max) { return max < 10 ? 10 : max; } // Buffer the top
                    },
                    legend: { position: 'top', horizontalAlign: 'left' },
                    tooltip: {
                        x: { format: 'HH:mm:ss' },
                        y: { formatter: function (val) { return val.toFixed(2) + " Mbps" } }
                    }
                };

                // Assuming ApexCharts is globally available from app.js bundle
                chart = new window.ApexCharts(document.querySelector("#traffic-chart"), options);
                chart.render();
            }

            if (document.querySelector("#traffic-chart")) {
                initChart();
            }

            window.addEventListener('traffic-updated', (e) => {
                if (!chart) return;
                
                let evt = Array.isArray(e.detail) ? e.detail[0] : e.detail;
                
                let rxMbps = (evt.rx || 0) / 1048576;
                let txMbps = (evt.tx || 0) / 1048576;

                document.getElementById('rx-label').innerText = rxMbps.toFixed(2) + ' Mbps';
                document.getElementById('tx-label').innerText = txMbps.toFixed(2) + ' Mbps';

                let now = new Date().getTime();
                
                dataRx.push([now, rxMbps]);
                if (dataRx.length > maxPoints) dataRx.shift();

                dataTx.push([now, txMbps]);
                if (dataTx.length > maxPoints) dataTx.shift();

                chart.updateSeries([
                    { data: dataRx },
                    { data: dataTx }
                ]);
            });

            window.addEventListener('reset-chart', () => {
                setTimeout(() => { if (document.querySelector("#traffic-chart")) initChart(); }, 100);
            });
        });
    </script>
    @endpush
</div>
