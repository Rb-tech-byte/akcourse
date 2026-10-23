@extends('admin.layouts.app')

@section('panel')
<div class="ak23-analytics-container">
    {{-- Header --}}
    <div class="ak23-analytics-header">
        <div>
            <h1 class="ak23-analytics-title">Sales Analytics</h1>
            <p class="ak23-analytics-sub">Track course sales, software fulfillment, and student access.</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-outline--secondary btn-sm" data-bs-toggle="modal" data-bs-target="#cronModal">
                <i class="las la-server"></i> @lang('Cron Setup')
            </button>
            <a href="{{ route('home') }}" target="_blank" class="ak23-btn-storefront">
                View storefront <i class="las la-arrow-right"></i>
            </a>
        </div>
    </div>

    {{-- Top 4 Metric Tiles --}}
    <div class="ak23-analytics-stats-grid">
        {{-- Total Revenue --}}
        <div class="ak23-stat-tile">
            <div class="ak23-stat-tile-top">
                <div>
                    <div class="ak23-stat-tile-label">Total revenue</div>
                    <div class="ak23-stat-tile-val">{{ $ak23Stats['total_revenue'] }}</div>
                </div>
                <div class="ak23-stat-tile-icon green">
                    <i class="las la-credit-card"></i>
                </div>
            </div>
            <div class="ak23-stat-tile-bottom">
                <span class="ak23-pill-trend green">+18.2%</span>
                <svg class="ak23-sparkline-svg" viewBox="0 0 100 24" preserveAspectRatio="none">
                    <path d="M0,20 Q25,18 40,21 T70,8 T100,5" fill="none" stroke="#FBAF43" stroke-width="2.5" stroke-linecap="round"/>
                </svg>
            </div>
        </div>

        {{-- Paid Orders --}}
        <div class="ak23-stat-tile">
            <div class="ak23-stat-tile-top">
                <div>
                    <div class="ak23-stat-tile-label">Paid orders</div>
                    <div class="ak23-stat-tile-val">{{ number_format($ak23Stats['paid_orders']) }}</div>
                </div>
                <div class="ak23-stat-tile-icon green">
                    <i class="las la-check"></i>
                </div>
            </div>
            <div class="ak23-stat-tile-bottom">
                <span class="ak23-pill-trend green">+8.4%</span>
                <svg class="ak23-sparkline-svg" viewBox="0 0 100 24" preserveAspectRatio="none">
                    <path d="M0,18 Q30,15 50,19 T80,10 T100,6" fill="none" stroke="#14B8A6" stroke-width="2.5" stroke-linecap="round"/>
                </svg>
            </div>
        </div>

        {{-- Active Students --}}
        <div class="ak23-stat-tile">
            <div class="ak23-stat-tile-top">
                <div>
                    <div class="ak23-stat-tile-label">Active students</div>
                    <div class="ak23-stat-tile-val">{{ number_format($ak23Stats['active_students']) }}</div>
                </div>
                <div class="ak23-stat-tile-icon blue">
                    <i class="las la-user-friends"></i>
                </div>
            </div>
            <div class="ak23-stat-tile-bottom">
                <span class="ak23-pill-trend green">+12.7%</span>
                <svg class="ak23-sparkline-svg" viewBox="0 0 100 24" preserveAspectRatio="none">
                    <path d="M0,22 Q20,20 45,18 T75,9 T100,8" fill="none" stroke="#FBAF43" stroke-width="2.5" stroke-linecap="round"/>
                </svg>
            </div>
        </div>

        {{-- Licenses Pending --}}
        <div class="ak23-stat-tile">
            <div class="ak23-stat-tile-top">
                <div>
                    <div class="ak23-stat-tile-label">Licenses pending</div>
                    <div class="ak23-stat-tile-val">{{ $ak23Stats['licenses_pending'] }}</div>
                </div>
                <div class="ak23-stat-tile-icon amber">
                    <i class="las la-shield-alt"></i>
                </div>
            </div>
            <div class="ak23-stat-tile-bottom">
                <span class="ak23-pill-trend amber">-6 this week</span>
                <svg class="ak23-sparkline-svg" viewBox="0 0 100 24" preserveAspectRatio="none">
                    <path d="M0,15 Q30,12 55,16 T85,12 T100,10" fill="none" stroke="#F59E0B" stroke-width="2.5" stroke-linecap="round"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Row 2: Revenue Overview & Order Status --}}
    <div class="row g-4 mb-4">
        {{-- Revenue Overview Spline Chart --}}
        <div class="col-xl-8">
            <div class="ak23-card">
                <div class="ak23-card-header">
                    <div>
                        <h2 class="ak23-card-title">Revenue Overview</h2>
                        <p class="ak23-card-sub">Monthly performance across all academy products</p>
                    </div>
                    <div class="ak23-chart-tabs">
                        <button type="button" class="ak23-chart-tab active" data-type="revenue">Revenue</button>
                        <button type="button" class="ak23-chart-tab" data-type="orders">Orders</button>
                        <button type="button" class="ak23-chart-tab" data-type="students">Students</button>
                    </div>
                </div>

                <div id="ak23RevenueSplineArea" class="ak23-spline-chart-box"></div>
            </div>
        </div>

        {{-- Order Status Donut Chart --}}
        <div class="col-xl-4">
            <div class="ak23-card">
                <div class="ak23-card-header">
                    <div>
                        <h2 class="ak23-card-title">Order Status</h2>
                        <p class="ak23-card-sub">Current Selcom payment states</p>
                    </div>
                </div>

                <div class="ak23-donut-container">
                    <div class="ak23-donut-relative">
                        <div id="ak23OrderStatusDonut" style="height: 170px;"></div>
                        <div class="ak23-donut-center">
                            <div class="ak23-donut-number">1,284</div>
                            <div class="ak23-donut-sub">Orders</div>
                        </div>
                    </div>

                    <div class="ak23-donut-legend">
                        <div class="ak23-legend-item">
                            <div class="ak23-legend-left">
                                <span class="ak23-legend-dot green"></span>
                                <span>Paid</span>
                            </div>
                            <span class="ak23-legend-val">984</span>
                        </div>
                        <div class="ak23-legend-item">
                            <div class="ak23-legend-left">
                                <span class="ak23-legend-dot teal"></span>
                                <span>Processing</span>
                            </div>
                            <span class="ak23-legend-val">176</span>
                        </div>
                        <div class="ak23-legend-item">
                            <div class="ak23-legend-left">
                                <span class="ak23-legend-dot blue"></span>
                                <span>Pending</span>
                            </div>
                            <span class="ak23-legend-val">89</span>
                        </div>
                        <div class="ak23-legend-item">
                            <div class="ak23-legend-left">
                                <span class="ak23-legend-dot red"></span>
                                <span>Failed</span>
                            </div>
                            <span class="ak23-legend-val">35</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 3: Top-Selling Courses & Revenue by Series --}}
    <div class="row g-4 mb-4">
        {{-- Top-Selling Courses --}}
        <div class="col-xl-8">
            <div class="ak23-card">
                <div class="ak23-card-header">
                    <div>
                        <h2 class="ak23-card-title">Top-Selling Courses</h2>
                        <p class="ak23-card-sub">Best performers this month</p>
                    </div>
                    <a href="{{ route('admin.product.all') }}" class="ak23-btn-storefront">
                        View all <i class="las la-arrow-right"></i>
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="ak23-admin-table">
                        <thead>
                            <tr>
                                <th style="width: 40px;">#</th>
                                <th>Course or series</th>
                                <th class="text-center" style="width: 90px;">Sold</th>
                                <th class="text-end" style="width: 140px;">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ak23TopCourses as $idx => $course)
                            <tr>
                                <td class="ak23-table-seq">{{ $idx + 1 }}</td>
                                <td>
                                    <div class="ak23-course-row-title">{{ $course['name'] }}</div>
                                    <div class="ak23-course-row-price">TSH {{ number_format($course['price']) }}</div>
                                </td>
                                <td class="text-center ak23-table-bold">{{ $course['sold'] }}</td>
                                <td class="text-end ak23-table-bold">{{ $course['revenue'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Revenue by Series --}}
        <div class="col-xl-4">
            <div class="ak23-card">
                <div class="ak23-card-header">
                    <div>
                        <h2 class="ak23-card-title">Revenue by Series</h2>
                        <p class="ak23-card-sub">Distribution across learning paths</p>
                    </div>
                </div>

                <div class="ak23-series-bars-list">
                    @foreach($ak23SeriesRevenue as $series)
                    <div class="ak23-series-bar-item">
                        <div class="ak23-series-bar-meta">
                            <span>{{ $series['name'] }}</span>
                            <span class="text-muted" style="font-size: 11px;">{{ $series['percent'] }}%</span>
                        </div>
                        <div class="ak23-series-track">
                            <div class="ak23-series-fill" style="width: {{ $series['percent'] }}%;"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Row 4: Recent Selcom Transactions & Fulfillment Health --}}
    <div class="row g-4 mb-4">
        {{-- Recent Selcom Transactions --}}
        <div class="col-xl-8">
            <div class="ak23-card">
                <div class="ak23-card-header">
                    <div>
                        <h2 class="ak23-card-title">Recent Selcom Transactions</h2>
                        <p class="ak23-card-sub">Latest payment activity and fulfillment status</p>
                    </div>
                    <a href="{{ route('admin.report.transaction') }}" class="ak23-btn-storefront">
                        View all <i class="las la-arrow-right"></i>
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="ak23-admin-table">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Product</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th class="text-end">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ak23Transactions as $t)
                            <tr>
                                <td>
                                    <div class="ak23-customer-col">
                                        <div class="ak23-customer-avatar">{{ $t->initials ?? 'AK' }}</div>
                                        <span class="fw-bold text-dark">{{ $t->customer_name }}</span>
                                    </div>
                                </td>
                                <td>{{ $t->product_name }}</td>
                                <td class="fw-bold text-dark">TSH {{ number_format($t->total_minor) }}</td>
                                <td>
                                    <span class="ak23-badge-status {{ strtolower($t->status) }}">
                                        {{ ucfirst($t->status) }}
                                    </span>
                                </td>
                                <td class="text-end text-muted" style="font-size: 12px;">
                                    {{ \Carbon\Carbon::parse($t->created_at)->diffForHumans() }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Fulfillment Health --}}
        <div class="col-xl-4">
            <div class="ak23-card">
                <div class="ak23-card-header">
                    <div>
                        <h2 class="ak23-card-title">Fulfillment Health</h2>
                        <p class="ak23-card-sub">Current operational targets</p>
                    </div>
                </div>

                <div class="ak23-health-list">
                    @foreach($ak23Fulfillment as $f)
                    <div class="ak23-health-item">
                        <div class="ak23-health-top">
                            <span class="ak23-health-name">{{ $f['name'] }}</span>
                            <span class="ak23-health-percent">{{ $f['percent'] }}%</span>
                        </div>
                        <div class="ak23-health-track">
                            <div class="ak23-health-fill" style="width: {{ $f['percent'] }}%; background: {{ $f['color'] }};"></div>
                        </div>
                        <div class="ak23-health-counts">
                            {{ number_format($f['current']) }} / {{ number_format($f['total']) }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- System Core Admin Section (Full Stack Platform Monitoring) --}}
    <div class="mt-5 pt-3 border-top">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fw-bold text-muted text-uppercase" style="font-size: 13px; letter-spacing: 0.05em;">
                Platform Operations & System Widgets
            </h5>
            <button class="btn btn-outline--primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#coreAdminWidgets" aria-expanded="false">
                <i class="las la-sliders-h me-1"></i> Toggle Platform Widgets
            </button>
        </div>

        <div class="collapse" id="coreAdminWidgets">
            <div class="row gy-4 mb-4">
                <div class="col-xxl-3 col-sm-6">
                    <x-widget style="6" link="{{ route('admin.users.all') }}" icon="las la-users" title="Total Users"
                              value="{{ $widget['total_users'] }}" bg="primary" />
                </div>
                <div class="col-xxl-3 col-sm-6">
                    <x-widget style="6" link="{{ route('admin.users.active') }}" icon="las la-user-check" title="Active Users"
                              value="{{ $widget['verified_users'] }}" bg="success" />
                </div>
                <div class="col-xxl-3 col-sm-6">
                    <x-widget style="6" link="{{ route('admin.users.email.unverified') }}" icon="lar la-envelope"
                              title="Email Unverified" value="{{ $widget['email_unverified_users'] }}" bg="danger" />
                </div>
                <div class="col-xxl-3 col-sm-6">
                    <x-widget style="6" link="{{ route('admin.users.mobile.unverified') }}" icon="las la-comment-slash"
                              title="Mobile Unverified" value="{{ $widget['mobile_unverified_users'] }}" bg="warning" />
                </div>
            </div>

            <div class="row gy-4 mb-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-title mb-3">Deposits Overview</h6>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Total Deposited</span>
                                <span class="fw-bold">{{ showAmount($deposit['total_deposit_amount']) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Pending Deposits</span>
                                <span class="badge bg-warning">{{ $deposit['total_deposit_pending'] }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Deposited Charge</span>
                                <span class="fw-bold">{{ showAmount($deposit['total_deposit_charge']) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-title mb-3">Sales & Net Revenue Overview</h6>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Gross Sales Volume</span>
                                <span class="fw-bold">{{ showAmount($widget['total_sale_amount']) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Completed Orders</span>
                                <span class="badge bg-success">{{ $widget['total_sale'] }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Refunded Volume</span>
                                <span class="fw-bold text-muted">{{ showAmount($widget['total_refunded']) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.partials.cron_modal')
@endsection

@push('breadcrumb-plugins')
    <button class="btn btn-outline--primary btn-sm" data-bs-toggle="modal" data-bs-target="#cronModal">
        <i class="las la-server"></i>@lang('Cron Setup')
    </button>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/apexcharts.min.js') }}"></script>
@endpush

@push('script')
<script>
(function($) {
    "use strict";

    // Revenue Spline Area Chart (ApexCharts)
    var splineOptions = {
        series: [{
            name: 'Revenue',
            data: [3000000, 6800000, 3800000, 4900000, 9200000, 10800000]
        }],
        chart: {
            type: 'area',
            height: 250,
            sparkline: { enabled: false },
            toolbar: { show: false },
            zoom: { enabled: false }
        },
        dataLabels: { enabled: false },
        stroke: {
            curve: 'smooth',
            width: 3,
            colors: ['#FBAF43']
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.35,
                opacityTo: 0.05,
                stops: [0, 90, 100],
                colorStops: [
                    { offset: 0, color: '#FBAF43', opacity: 0.35 },
                    { offset: 100, color: '#FBAF43', opacity: 0.0 }
                ]
            }
        },
        xaxis: {
            categories: ['Mar 1', 'Mar 6', 'Mar 11', 'Mar 16', 'Mar 21', 'Mar 26'],
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: {
                style: { colors: '#94A3B8', fontSize: '11px', fontFamily: 'inherit' }
            }
        },
        yaxis: {
            labels: {
                formatter: function (val) {
                    if (val === 0) return 'TSH 0';
                    return 'TSH ' + (val / 1000000).toFixed(0) + 'M';
                },
                style: { colors: '#94A3B8', fontSize: '11px', fontFamily: 'inherit' }
            }
        },
        grid: {
            borderColor: '#F1F5F9',
            strokeDashArray: 3,
            xaxis: { lines: { show: false } },
            yaxis: { lines: { show: true } }
        },
        tooltip: {
            theme: 'dark',
            y: {
                formatter: function(val) {
                    return 'TSH ' + val.toLocaleString();
                }
            }
        }
    };

    var splineChart = new ApexCharts(document.querySelector("#ak23RevenueSplineArea"), splineOptions);
    splineChart.render();

    // Chart Tabs interactive toggle
    $('.ak23-chart-tab').on('click', function() {
        $('.ak23-chart-tab').removeClass('active');
        $(this).addClass('active');
        var type = $(this).data('type');

        if (type === 'orders') {
            splineChart.updateSeries([{
                name: 'Orders',
                data: [45, 112, 60, 85, 195, 230]
            }]);
            splineChart.updateOptions({
                yaxis: {
                    labels: {
                        formatter: function(val) { return val + ' orders'; }
                    }
                }
            });
        } else if (type === 'students') {
            splineChart.updateSeries([{
                name: 'New Students',
                data: [30, 85, 45, 65, 150, 180]
            }]);
            splineChart.updateOptions({
                yaxis: {
                    labels: {
                        formatter: function(val) { return val + ' students'; }
                    }
                }
            });
        } else {
            splineChart.updateSeries([{
                name: 'Revenue',
                data: [3000000, 6800000, 3800000, 4900000, 9200000, 10800000]
            }]);
            splineChart.updateOptions({
                yaxis: {
                    labels: {
                        formatter: function(val) {
                            if (val === 0) return 'TSH 0';
                            return 'TSH ' + (val / 1000000).toFixed(0) + 'M';
                        }
                    }
                }
            });
        }
    });

    // Order Status Donut Chart (ApexCharts)
    var donutOptions = {
        series: [984, 176, 89, 35],
        chart: {
            type: 'donut',
            height: 170
        },
        labels: ['Paid', 'Processing', 'Pending', 'Failed'],
        colors: ['#FBAF43', '#3A3A3C', '#64748B', '#EF4444'],
        dataLabels: { enabled: false },
        legend: { show: false },
        stroke: { width: 3, colors: ['#FFFFFF'] },
        plotOptions: {
            pie: {
                donut: {
                    size: '76%',
                    labels: { show: false }
                }
            }
        },
        tooltip: {
            theme: 'dark',
            y: {
                formatter: function(val) { return val + ' orders'; }
            }
        }
    };

    var donutChart = new ApexCharts(document.querySelector("#ak23OrderStatusDonut"), donutOptions);
    donutChart.render();

})(jQuery);
</script>
@endpush
