@extends('admin.layouts.app')

@section('panel')
<div class="row gy-4">

    <!-- Top Hero Banner -->
    <div class="col-12">
        <div class="card border-0 shadow-sm ak23-info-hero">
            <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-dark border border-gold mb-2">
                        <span class="pulse-indicator"></span>
                        <span class="text-gold fw-bold fs-11 text-uppercase letter-spacing-1">System Architecture Overview</span>
                    </div>
                    <h3 class="text-white mb-1">Maarifa23 Enterprise Core Environment</h3>
                    <p class="text-muted mb-0 fs-13">
                        Deep operational telemetry, server runtime metrics, database sizing, and storage health.
                    </p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('admin.system.server.info') }}" class="btn btn-outline-gold btn-sm">
                        <i class="las la-server"></i> @lang('Raw Server Info')
                    </a>
                    <a href="{{ route('admin.system.update') }}" class="btn btn-gold btn-sm">
                        <i class="las la-rocket"></i> @lang('System Upgrader')
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 4 Native Compact Metric Cards -->
    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm ak23-metric-card h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="metric-label">Framework Core</span>
                        <h4 class="metric-val text-white mt-1">Laravel {{ $laravelVersion }}</h4>
                        <span class="badge bg-dark text-gold mt-1">App Core v{{ systemDetails()['version'] }}</span>
                    </div>
                    <div class="metric-icon-wrap gold">
                        <i class="lab la-laravel"></i>
                    </div>
                </div>
                <div class="metric-footer mt-2 pt-2 border-top border-dark-subtle d-flex justify-content-between align-items-center fs-11 text-muted">
                    <span>Environment: <strong class="text-white">{{ strtoupper($appEnv) }}</strong></span>
                    <span>Debug: <strong class="{{ $appDebug ? 'text-warning' : 'text-success' }}">{{ $appDebug ? 'Enabled' : 'Disabled' }}</strong></span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm ak23-metric-card h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="metric-label">Database Storage</span>
                        <h4 class="metric-val text-white mt-1">{{ $dbSizeMb }} <small class="fs-13 text-muted">MB</small></h4>
                        <span class="badge bg-success mt-1">{{ $tableCount }} Tables Active</span>
                    </div>
                    <div class="metric-icon-wrap green">
                        <i class="las la-database"></i>
                    </div>
                </div>
                <div class="metric-footer mt-2 pt-2 border-top border-dark-subtle d-flex justify-content-between align-items-center fs-11 text-muted">
                    <span>Engine: <strong class="text-white">{{ strtoupper($dbDriver) }}</strong></span>
                    <span>Version: <strong class="text-white">v{{ Str::limit($dbVersion, 8, '') }}</strong></span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm ak23-metric-card h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="metric-label">PHP Runtime</span>
                        <h4 class="metric-val text-white mt-1">PHP {{ $currentPHP }}</h4>
                        <span class="badge bg-info mt-1">{{ $phpSapi }}</span>
                    </div>
                    <div class="metric-icon-wrap blue">
                        <i class="lab la-php"></i>
                    </div>
                </div>
                <div class="metric-footer mt-2 pt-2 border-top border-dark-subtle d-flex justify-content-between align-items-center fs-11 text-muted">
                    <span>Memory Limit: <strong class="text-white">{{ $memoryLimit }}</strong></span>
                    <span>Max Exec: <strong class="text-white">{{ $maxExecutionTime }}s</strong></span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm ak23-metric-card h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="metric-label">Filesystem &amp; Cache</span>
                        <h4 class="metric-val text-white mt-1">{{ $storageWritable ? 'Writable' : 'Protected' }}</h4>
                        <span class="badge {{ $storageWritable ? 'bg-success' : 'bg-danger' }} mt-1">
                            <i class="las {{ $storageWritable ? 'la-check' : 'la-times' }}"></i> Storage Verified
                        </span>
                    </div>
                    <div class="metric-icon-wrap purple">
                        <i class="las la-hdd"></i>
                    </div>
                </div>
                <div class="metric-footer mt-2 pt-2 border-top border-dark-subtle d-flex justify-content-between align-items-center fs-11 text-muted">
                    <span>Bootstrap: <strong class="{{ $bootstrapWritable ? 'text-success' : 'text-danger' }}">{{ $bootstrapWritable ? 'OK' : 'Error' }}</strong></span>
                    <span>OPcache: <strong class="{{ $opcacheEnabled ? 'text-success' : 'text-muted' }}">{{ $opcacheEnabled ? 'Active' : 'Off' }}</strong></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Core Database Tables Metrics -->
    <div class="col-lg-7 col-md-12">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center border-bottom">
                <h6 class="card-title mb-0 d-flex align-items-center gap-2">
                    <i class="las la-table text-gold"></i> @lang('Core Database Tables & Sizing')
                </h6>
                <span class="badge bg-dark text-gold fs-11">Database: {{ $dbName }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>@lang('Table Name')</th>
                                <th>@lang('Total Rows')</th>
                                <th>@lang('Allocated Size')</th>
                                <th>@lang('Engine / Status')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tablesSummary as $table)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="las la-database text-gold"></i>
                                            <code class="fw-bold fs-13">{{ $table['name'] }}</code>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-dark text-white fw-bold">{{ number_format($table['rows']) }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted fs-12">{{ $table['size'] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success fs-11">
                                            <i class="las la-check"></i> InnoDB Active
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">@lang('No core table metrics available.')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Configuration & Extensions Grid -->
    <div class="col-lg-5 col-md-12">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center border-bottom">
                <h6 class="card-title mb-0 d-flex align-items-center gap-2">
                    <i class="las la-cogs text-gold"></i> @lang('Server Parameters & Environment')
                </h6>
                <span class="badge bg-dark text-white fs-11">Timezone: {{ $timeZone }}</span>
            </div>
            <div class="card-body p-3">
                <ul class="list-group list-group-flush mb-3">
                    <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 py-2 border-dark-subtle">
                        <span class="text-muted fs-13">Web Server Software</span>
                        <span class="text-white fw-semibold fs-12">{{ Str::limit($serverSoftware, 32) }}</span>
                    </li>
                    <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 py-2 border-dark-subtle">
                        <span class="text-muted fs-13">Server Host / IP</span>
                        <span class="text-white fw-semibold fs-12">{{ $serverIp }}</span>
                    </li>
                    <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 py-2 border-dark-subtle">
                        <span class="text-muted fs-13">Post Max Size</span>
                        <span class="badge bg-dark text-gold">{{ $postMaxSize }}</span>
                    </li>
                    <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 py-2 border-dark-subtle">
                        <span class="text-muted fs-13">Upload Max File Size</span>
                        <span class="badge bg-dark text-gold">{{ $uploadMaxFilesize }}</span>
                    </li>
                    <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 py-2 border-0">
                        <span class="text-muted fs-13">ViserAdmin Core Build</span>
                        <span class="badge bg-dark text-white">{{ systemDetails()['build_version'] }}</span>
                    </li>
                </ul>

                <h6 class="text-gold fs-12 text-uppercase letter-spacing-1 mb-2">
                    <i class="las la-cubes"></i> @lang('Loaded PHP Extensions') ({{ count($extensions) }})
                </h6>
                <div class="d-flex flex-wrap gap-1" style="max-height: 140px; overflow-y: auto;">
                    @foreach($extensions as $ext)
                        <span class="badge bg-dark text-muted fw-normal" style="font-size: 11px;">{{ $ext }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.system.update') }}" class="btn btn-outline--primary btn-sm"><i class="las la-rocket"></i> @lang('System Upgrader')</a>
    <a href="{{ route('admin.system.optimize') }}" class="btn btn-outline--secondary btn-sm"><i class="las la-broom"></i> @lang('Clear Cache')</a>
@endpush

@push('style')
<style>
.border-gold {
    border-color: rgba(251, 175, 67, 0.35) !important;
}
.ak23-info-hero {
    background: linear-gradient(135deg, #181a20 0%, #1e222d 50%, #151821 100%);
    border: 1px solid rgba(251, 175, 67, 0.2);
    border-radius: 12px;
}
.ak23-metric-card {
    background: #181a20;
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 10px;
    transition: transform 0.2s ease, border-color 0.2s ease;
}
.ak23-metric-card:hover {
    transform: translateY(-2px);
    border-color: rgba(251, 175, 67, 0.3);
}
.metric-label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #a0aec0;
    font-weight: 600;
}
.metric-val {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 0;
    color: #ffffff;
}
.metric-icon-wrap {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}
.metric-icon-wrap.gold {
    background: rgba(251, 175, 67, 0.12);
    color: #FBAF43;
}
.metric-icon-wrap.green {
    background: rgba(56, 161, 105, 0.12);
    color: #38a169;
}
.metric-icon-wrap.blue {
    background: rgba(49, 130, 206, 0.12);
    color: #3182ce;
}
.metric-icon-wrap.purple {
    background: rgba(128, 90, 213, 0.12);
    color: #805ad5;
}
.letter-spacing-1 {
    letter-spacing: 0.8px;
}
.pulse-indicator {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #FBAF43;
    box-shadow: 0 0 6px #FBAF43;
}
</style>
@endpush
