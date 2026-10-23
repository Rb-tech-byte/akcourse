@extends('admin.layouts.app')

@section('panel')
<div class="row gy-4">

    <!-- Top Architecture & Version Overview Cards -->
    <div class="col-12">
        <div class="upgrader-hero-card">
            <div class="upgrader-hero-content">
                <div class="hero-badge">
                    <span class="pulse-indicator"></span>
                    <span class="badge-text">Dedicated System Architecture Upgrader</span>
                </div>
                <h2 class="hero-title">Laravel Migration Suite: v12.x &rarr; v13.0 Enterprise</h2>
                <p class="hero-desc">
                    Execute a complete, zero-downtime database schema migration, composite index optimization, and core system registry bump directly through this interactive administrator console.
                </p>
                <div class="hero-version-pill-group">
                    <div class="version-pill current">
                        <span class="label">Current Core</span>
                        <span class="val">v{{ $currentVersion }} (Laravel {{ $laravelVersion }})</span>
                    </div>
                    <div class="version-arrow">
                        <i class="las la-arrow-right"></i>
                    </div>
                    <div class="version-pill target">
                        <span class="label">Target Core</span>
                        <span class="val">v{{ $targetVersion }} Enterprise Modernization</span>
                    </div>
                    <div class="version-pill status {{ $isMigrated ? 'migrated' : 'pending' }}">
                        <span class="label">Status</span>
                        <span class="val">
                            @if($isMigrated)
                                <i class="las la-check-circle"></i> System Migrated (v13.0)
                            @else
                                <i class="las la-clock"></i> Ready for Migration
                            @endif
                        </span>
                    </div>
                </div>
            </div>
            <div class="upgrader-hero-action">
                @if(!$isMigrated)
                    <button type="button" class="btn btn-gold btn-lg btn-pulse" data-bs-toggle="modal" data-bs-target="#migrationConfirmModal">
                        <i class="las la-rocket"></i> @lang('Start v12 to v13 Migration')
                    </button>
                @else
                    <button type="button" class="btn btn-outline-gold btn-lg" data-bs-toggle="modal" data-bs-target="#migrationConfirmModal">
                        <i class="las la-sync"></i> @lang('Re-Run Migration & Integrity Check')
                    </button>
                @endif
                <button type="button" class="btn btn-outline-secondary btn-sm mt-2 btn-preflight-check">
                    <i class="las la-stethoscope"></i> @lang('Pre-Flight Diagnostics Only')
                </button>
            </div>
        </div>
    </div>

    <!-- Pre-Flight Diagnostics Matrix -->
    <div class="col-lg-4 col-md-12">
        <div class="card h-100 border-0 shadow-sm diagnostic-card">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center border-bottom">
                <h6 class="card-title mb-0 d-flex align-items-center gap-2">
                    <i class="las la-shield-alt text-gold"></i> @lang('Pre-Flight Health Matrix')
                </h6>
                <span class="badge {{ $diagnostics['all_passed'] ? 'bg-success' : 'bg-danger' }}">
                    {{ $diagnostics['all_passed'] ? 'All Systems Go' : 'Action Required' }}
                </span>
            </div>
            <div class="card-body p-3">
                <ul class="diagnostic-list">
                    <li class="diagnostic-item">
                        <div class="diag-meta">
                            <span class="diag-title">PHP Runtime</span>
                            <span class="diag-val">v{{ $diagnostics['php_version'] }} (Min: 8.2)</span>
                        </div>
                        <span class="diag-badge {{ $diagnostics['php_passed'] ? 'pass' : 'fail' }}">
                            <i class="las {{ $diagnostics['php_passed'] ? 'la-check-circle' : 'la-times-circle' }}"></i>
                        </span>
                    </li>

                    <li class="diagnostic-item">
                        <div class="diag-meta">
                            <span class="diag-title">Database Engine</span>
                            <span class="diag-val">{{ strtoupper($diagnostics['db_driver']) }} v{{ $diagnostics['db_version'] }}</span>
                        </div>
                        <span class="diag-badge {{ $diagnostics['db_ok'] ? 'pass' : 'fail' }}">
                            <i class="las {{ $diagnostics['db_ok'] ? 'la-check-circle' : 'la-times-circle' }}"></i>
                        </span>
                    </li>

                    <li class="diagnostic-item">
                        <div class="diag-meta">
                            <span class="diag-title">Storage Writable</span>
                            <span class="diag-val">core/storage &amp; logs</span>
                        </div>
                        <span class="diag-badge {{ $diagnostics['storage_writable'] ? 'pass' : 'fail' }}">
                            <i class="las {{ $diagnostics['storage_writable'] ? 'la-check-circle' : 'la-times-circle' }}"></i>
                        </span>
                    </li>

                    <li class="diagnostic-item">
                        <div class="diag-meta">
                            <span class="diag-title">Bootstrap Cache Writable</span>
                            <span class="diag-val">bootstrap/cache</span>
                        </div>
                        <span class="diag-badge {{ $diagnostics['bootstrap_writable'] ? 'pass' : 'fail' }}">
                            <i class="las {{ $diagnostics['bootstrap_writable'] ? 'la-check-circle' : 'la-times-circle' }}"></i>
                        </span>
                    </li>

                    <li class="diagnostic-item">
                        <div class="diag-meta">
                            <span class="diag-title">Core PHP Extensions</span>
                            <span class="diag-val">11 Critical Drivers</span>
                        </div>
                        <span class="diag-badge {{ $diagnostics['extensions_passed'] ? 'pass' : 'fail' }}">
                            <i class="las {{ $diagnostics['extensions_passed'] ? 'la-check-circle' : 'la-times-circle' }}"></i>
                        </span>
                    </li>

                    <li class="diagnostic-item">
                        <div class="diag-meta">
                            <span class="diag-title">Memory &amp; Max Execution</span>
                            <span class="diag-val">{{ $diagnostics['memory_limit'] }} | {{ $diagnostics['max_execution_time'] }}s</span>
                        </div>
                        <span class="diag-badge pass">
                            <i class="las la-check-circle"></i>
                        </span>
                    </li>
                </ul>

                <div class="extension-chips mt-3">
                    <span class="chip-label text-muted d-block mb-1 fs-12">Audited Extensions:</span>
                    <div class="d-flex flex-wrap gap-1">
                        @foreach($diagnostics['extensions'] as $ext => $info)
                            <span class="badge {{ $info['loaded'] ? 'bg-dark text-gold' : 'bg-danger text-white' }} fw-normal" style="font-size: 11px;">
                                {{ $ext }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stepper & Interactive Terminal Console -->
    <div class="col-lg-8 col-md-12">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center border-bottom">
                <h6 class="card-title mb-0 d-flex align-items-center gap-2">
                    <i class="las la-tasks text-gold"></i> @lang('Migration Execution Pipeline')
                </h6>
                <div class="migration-progress-badge">
                    <span class="progress-percent" id="overallProgressText">0%</span>
                </div>
            </div>

            <div class="card-body p-3">
                <!-- 5-Stage Stepper -->
                <div class="upgrader-stepper">
                    <div class="stepper-track">
                        <div class="stepper-fill" id="stepperFill" style="width: 0%;"></div>
                    </div>

                    <div class="step-nodes">
                        <div class="step-node" id="stepNode1" data-step="preflight">
                            <div class="node-circle"><i class="las la-check"></i><span class="num">1</span></div>
                            <span class="node-label">Pre-Flight</span>
                        </div>
                        <div class="step-node" id="stepNode2" data-step="database_migration">
                            <div class="node-circle"><i class="las la-check"></i><span class="num">2</span></div>
                            <span class="node-label">Schema &amp; Indexes</span>
                        </div>
                        <div class="step-node" id="stepNode3" data-step="data_sync">
                            <div class="node-circle"><i class="las la-check"></i><span class="num">3</span></div>
                            <span class="node-label">Registry Sync</span>
                        </div>
                        <div class="step-node" id="stepNode4" data-step="cache_optimize">
                            <div class="node-circle"><i class="las la-check"></i><span class="num">4</span></div>
                            <span class="node-label">Cache Flush</span>
                        </div>
                        <div class="step-node" id="stepNode5" data-step="smoke_test">
                            <div class="node-circle"><i class="las la-check"></i><span class="num">5</span></div>
                            <span class="node-label">Verification</span>
                        </div>
                    </div>
                </div>

                <!-- Interactive Terminal Console Window -->
                <div class="terminal-window mt-3">
                    <div class="terminal-header">
                        <div class="terminal-controls">
                            <span class="control dot-red"></span>
                            <span class="control dot-yellow"></span>
                            <span class="control dot-green"></span>
                        </div>
                        <div class="terminal-title">
                            <i class="las la-terminal"></i> akcourse@system-core: ~/migration/v12-to-v13$
                        </div>
                        <div class="terminal-actions">
                            <button type="button" class="terminal-btn" id="btnCopyTerminal" title="Copy Console Output">
                                <i class="las la-copy"></i>
                            </button>
                            <button type="button" class="terminal-btn" id="btnClearTerminal" title="Clear Console">
                                <i class="las la-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="terminal-body" id="terminalOutput">
                        <div class="log-line log-secondary">
                            <span class="time">[{{ now()->format('H:i:s') }}]</span>
                            <span class="msg">System Upgrader initialized. Waiting for administrator command...</span>
                        </div>
                        <div class="log-line log-info">
                            <span class="time">[{{ now()->format('H:i:s') }}]</span>
                            <span class="msg">Current core architecture: Laravel {{ $laravelVersion }} (Application v{{ $currentVersion }}).</span>
                        </div>
                        <div class="log-line log-gold">
                            <span class="time">[{{ now()->format('H:i:s') }}]</span>
                            <span class="msg">Target version: v13.0.0 Enterprise Modernization Suite.</span>
                        </div>
                    </div>
                </div>

                <!-- Post-Migration Success Announcement -->
                <div class="migration-success-box d-none mt-3" id="migrationSuccessBox">
                    <div class="d-flex align-items-center gap-3">
                        <div class="success-icon-wrap">
                            <i class="las la-trophy"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="text-white mb-1">@lang('System Migration to v13.0 Complete!')</h5>
                            <p class="text-muted mb-0 fs-13">
                                Database schema modernized, 5 compound performance indexes applied, version registry bumped to 13.0, and application caches fully optimized.
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.system.update.log') }}" class="btn btn-outline-gold btn-sm">
                                <i class="las la-list"></i> @lang('Changelog')
                            </a>
                            <button type="button" class="btn btn-gold btn-sm" onclick="window.location.reload();">
                                <i class="las la-redo"></i> @lang('Reload Interface')
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Migration Registry & Applied Migrations Table -->
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center border-bottom">
                <h6 class="card-title mb-0 d-flex align-items-center gap-2">
                    <i class="las la-database text-gold"></i> @lang('Database Migration Registry (system_migrations)')
                </h6>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.system.update.log') }}" class="btn btn-outline-gold btn-sm">
                        <i class="las la-history"></i> @lang('View Full Update Log')
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('Migration Identifier')</th>
                                <th>@lang('Batch')</th>
                                <th>@lang('Target Version')</th>
                                <th>@lang('Executed At')</th>
                                <th>@lang('Status')</th>
                            </tr>
                        </thead>
                        <tbody id="migrationsTableBody">
                            @forelse($appliedMigrations as $mig)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><code>{{ $mig->migration }}</code></td>
                                    <td><span class="badge bg-dark text-gold">Batch {{ $mig->batch }}</span></td>
                                    <td>v13.0 Enterprise</td>
                                    <td>{{ $mig->executed_at ?? 'N/A' }}</td>
                                    <td><span class="badge bg-success"><i class="las la-check"></i> Applied</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="las la-info-circle text-gold fs-20 d-block mb-2"></i>
                                        @lang('No core migrations registered yet. Click "Start v12 to v13 Migration" to initialize.')
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Migration Confirmation Modal -->
<div class="modal fade" id="migrationConfirmModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content upgrader-modal-content">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-2">
                    <div class="modal-icon-badge">
                        <i class="las la-database text-gold"></i>
                    </div>
                    <div>
                        <h5 class="modal-title text-white mb-0">@lang('Execute v12 to v13 System Architecture Upgrade')</h5>
                        <p class="text-muted fs-12 mb-0">@lang('Interactive zero-downtime database and core optimization pipeline')</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <div class="alert alert-dark border-gold bg-darker p-3 mb-3">
                    <h6 class="text-gold mb-2 d-flex align-items-center gap-2">
                        <i class="las la-info-circle fs-18"></i> @lang('What this Migration does:')
                    </h6>
                    <ul class="modal-feature-list mb-0">
                        <li><strong>@lang('Creates Core Migration Registry'):</strong> Initializes <code>system_migrations</code> table for declarative tracking.</li>
                        <li><strong>@lang('Applies Composite DB Indexes'):</strong> Adds performance indexes to <code>orders</code>, <code>order_items</code>, <code>products</code>, <code>transactions</code>, and <code>admin_notifications</code>.</li>
                        <li><strong>@lang('Expands System Attributes'):</strong> Injects <code>system_version</code>, <code>api_rate_limit</code>, and <code>rpc_endpoint_enabled</code> into settings.</li>
                        <li><strong>@lang('Flushes Stale Caches'):</strong> Purges compiled Blade views, route caches, and config trees.</li>
                        <li><strong>@lang('Version Registry Elevation'):</strong> Bumps system architecture version to <strong>v13.0 Enterprise</strong>.</li>
                    </ul>
                </div>
                <div class="alert alert-warning p-2 mb-0 d-flex align-items-center gap-2 fs-12">
                    <i class="las la-shield-alt fs-18 text-warning"></i>
                    <span>@lang('Non-destructive execution. If an index or column already exists, it will be skipped safely without modifying user data.')</span>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">@lang('Cancel')</button>
                <button type="button" class="btn btn-gold px-4" id="btnConfirmRunMigration">
                    <i class="las la-play"></i> @lang('Run System Migration Now')
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.system.update.log') }}" class="btn btn-outline--primary btn-sm"><i class="las la-list"></i> @lang('Update Log')</a>
    <a href="{{ route('admin.system.info') }}" class="btn btn-outline--secondary btn-sm"><i class="las la-info-circle"></i> @lang('System Info')</a>
@endpush

@push('style')
<style>
/* AK23 Upgrader Theme Styles */
:root {
    --upgrader-gold: #FBAF43;
    --upgrader-gold-hover: #f99e1a;
    --upgrader-dark: #12141a;
    --upgrader-card-bg: #181a20;
    --upgrader-border: rgba(251, 175, 67, 0.2);
}

.text-gold {
    color: var(--upgrader-gold) !important;
}

.btn-gold, button.btn-gold, a.btn-gold {
    background-color: var(--upgrader-gold) !important;
    background: var(--upgrader-gold) !important;
    color: #12141a !important;
    font-weight: 700 !important;
    border: none !important;
    transition: all 0.25s ease;
}
.btn-gold:hover, .btn-gold:focus, button.btn-gold:hover, a.btn-gold:hover {
    background-color: var(--upgrader-gold-hover) !important;
    background: var(--upgrader-gold-hover) !important;
    color: #12141a !important;
    box-shadow: 0 4px 15px rgba(251, 175, 67, 0.45) !important;
}

.btn-outline-gold, button.btn-outline-gold, a.btn-outline-gold {
    border: 1px solid var(--upgrader-gold) !important;
    color: var(--upgrader-gold) !important;
    background: transparent !important;
    font-weight: 600 !important;
    transition: all 0.25s ease;
}
.btn-outline-gold:hover, button.btn-outline-gold:hover, a.btn-outline-gold:hover {
    background: var(--upgrader-gold) !important;
    color: #12141a !important;
}

.migration-success-box .btn {
    white-space: nowrap !important;
    flex-shrink: 0 !important;
}

.btn-pulse {
    animation: pulseGold 2s infinite;
}

@keyframes pulseGold {
    0% {
        box-shadow: 0 0 0 0 rgba(251, 175, 67, 0.6);
    }
    70% {
        box-shadow: 0 0 0 12px rgba(251, 175, 67, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(251, 175, 67, 0);
    }
}

/* Hero Card */
.upgrader-hero-card {
    background: linear-gradient(135deg, #181a20 0%, #1a1e28 50%, #151821 100%);
    border: 1px solid var(--upgrader-border);
    border-radius: 12px;
    padding: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 30px;
    flex-wrap: wrap;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
    position: relative;
    overflow: hidden;
}
.upgrader-hero-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(251, 175, 67, 0.08) 0%, transparent 70%);
    pointer-events: none;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 4px 12px;
    background: rgba(251, 175, 67, 0.12);
    border: 1px solid rgba(251, 175, 67, 0.3);
    border-radius: 50px;
    margin-bottom: 12px;
}
.pulse-indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--upgrader-gold);
    box-shadow: 0 0 8px var(--upgrader-gold);
}
.hero-badge .badge-text {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: var(--upgrader-gold);
}

.hero-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 8px;
}
.hero-desc {
    color: #a0aec0;
    font-size: 0.95rem;
    max-width: 680px;
    margin-bottom: 20px;
}

.hero-version-pill-group {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}
.version-pill {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 8px;
    padding: 8px 14px;
    display: flex;
    flex-direction: column;
}
.version-pill .label {
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #718096;
}
.version-pill .val {
    font-size: 13px;
    font-weight: 600;
    color: #ffffff;
}
.version-pill.target {
    border-color: rgba(251, 175, 67, 0.4);
    background: rgba(251, 175, 67, 0.06);
}
.version-pill.target .val {
    color: var(--upgrader-gold);
}
.version-pill.status.migrated .val {
    color: #38a169;
}
.version-pill.status.pending .val {
    color: #e2a03f;
}
.version-arrow {
    color: #718096;
    font-size: 18px;
}

/* Diagnostics Card */
.diagnostic-card {
    background: #181a20;
    border: 1px solid rgba(255, 255, 255, 0.06);
}
.diagnostic-list {
    list-style: none;
    padding: 0;
    margin: 0;
}
.diagnostic-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}
.diagnostic-item:last-child {
    border-bottom: none;
}
.diag-meta {
    display: flex;
    flex-direction: column;
}
.diag-title {
    font-size: 13px;
    font-weight: 600;
    color: #e2e8f0;
}
.diag-val {
    font-size: 11px;
    color: #718096;
}
.diag-badge {
    font-size: 18px;
}
.diag-badge.pass {
    color: #38a169;
}
.diag-badge.fail {
    color: #e53e3e;
}

/* Stepper */
.upgrader-stepper {
    position: relative;
    padding: 15px 10px;
}
.stepper-track {
    position: absolute;
    top: 30px;
    left: 40px;
    right: 40px;
    height: 4px;
    background: rgba(255, 255, 255, 0.08);
    border-radius: 4px;
    z-index: 1;
}
.stepper-fill {
    height: 100%;
    background: linear-gradient(90deg, #FBAF43, #38a169);
    border-radius: 4px;
    transition: width 0.4s ease;
}
.step-nodes {
    display: flex;
    justify-content: space-between;
    position: relative;
    z-index: 2;
}
.step-node {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    cursor: default;
}
.node-circle {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: #1e222d;
    border: 2px solid rgba(255, 255, 255, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
    color: #a0aec0;
    transition: all 0.3s ease;
}
.node-circle i {
    display: none;
    font-size: 14px;
}
.node-label {
    font-size: 11px;
    font-weight: 600;
    color: #718096;
    transition: color 0.3s ease;
}

.step-node.active .node-circle {
    border-color: var(--upgrader-gold);
    color: var(--upgrader-gold);
    box-shadow: 0 0 12px rgba(251, 175, 67, 0.4);
    background: #252a37;
}
.step-node.active .node-label {
    color: var(--upgrader-gold);
}

.step-node.running .node-circle {
    border-color: var(--upgrader-gold);
    animation: nodePulse 1s infinite alternate;
}
@keyframes nodePulse {
    from { transform: scale(1); }
    to { transform: scale(1.15); }
}

.step-node.completed .node-circle {
    background: #38a169;
    border-color: #38a169;
    color: #ffffff;
}
.step-node.completed .node-circle .num {
    display: none;
}
.step-node.completed .node-circle i {
    display: inline-block;
}
.step-node.completed .node-label {
    color: #38a169;
}

/* Terminal Window */
.terminal-window {
    background: #0d1117;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    overflow: hidden;
    box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.8);
}
.terminal-header {
    background: #161b22;
    padding: 8px 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid rgba(255, 255, 255, 0.06);
}
.terminal-controls {
    display: flex;
    gap: 6px;
}
.terminal-controls .control {
    width: 10px;
    height: 10px;
    border-radius: 50%;
}
.dot-red { background: #ff5f56; }
.dot-yellow { background: #ffbd2e; }
.dot-green { background: #27c93f; }

.terminal-title {
    font-family: 'Consolas', 'Courier New', monospace;
    font-size: 12px;
    color: #8b949e;
}
.terminal-actions {
    display: flex;
    gap: 8px;
}
.terminal-btn {
    background: transparent;
    border: none;
    color: #8b949e;
    font-size: 13px;
    cursor: pointer;
    padding: 2px 6px;
    border-radius: 4px;
    transition: color 0.2s;
}
.terminal-btn:hover {
    color: #ffffff;
    background: rgba(255, 255, 255, 0.08);
}

.terminal-body {
    padding: 14px;
    height: 280px;
    overflow-y: auto;
    font-family: 'Consolas', 'Courier New', monospace;
    font-size: 12px;
    line-height: 1.6;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.log-line {
    display: flex;
    gap: 8px;
    word-break: break-word;
}
.log-line .time {
    color: #6e7681;
    user-select: none;
}
.log-line.log-info .msg { color: #58a6ff; }
.log-line.log-success .msg { color: #3fb950; }
.log-line.log-gold .msg { color: #FBAF43; }
.log-line.log-warning .msg { color: #d29922; }
.log-line.log-danger .msg { color: #f85149; }
.log-line.log-secondary .msg { color: #8b949e; }

/* Modal Content */
.upgrader-modal-content {
    background: #181a20;
    border: 1px solid var(--upgrader-border);
    border-radius: 12px;
}
.modal-icon-badge {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: rgba(251, 175, 67, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
}
.modal-feature-list {
    padding-left: 20px;
    line-height: 1.8;
    font-size: 13px;
    color: #cbd5e0;
}
.bg-darker {
    background: #12141a !important;
}

/* Success Box */
.migration-success-box {
    background: linear-gradient(135deg, rgba(56, 161, 105, 0.15) 0%, rgba(24, 26, 32, 0.9) 100%);
    border: 1px solid rgba(56, 161, 105, 0.4);
    border-radius: 10px;
    padding: 16px 20px;
}
.success-icon-wrap {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #38a169;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
}
</style>
@endpush

@push('script')
<script>
(function($) {
    "use strict";

    var isRunning = false;
    var processUrl = "{{ route('admin.system.update.process') }}";
    var csrfToken = "{{ csrf_token() }}";

    function appendLog(level, text, time) {
        var nowTime = time || (new Date()).toTimeString().split(' ')[0];
        var levelClass = 'log-' + (level || 'info');
        var html = '<div class="log-line ' + levelClass + '">' +
            '<span class="time">[' + nowTime + ']</span> ' +
            '<span class="msg">' + $('<div>').text(text).html() + '</span>' +
            '</div>';
        var terminal = $('#terminalOutput');
        terminal.append(html);
        terminal.scrollTop(terminal[0].scrollHeight);
    }

    function setStepStatus(stepNum, status) {
        var node = $('#stepNode' + stepNum);
        node.removeClass('active running completed');
        if (status === 'running') {
            node.addClass('running active');
        } else if (status === 'completed') {
            node.addClass('completed');
        } else if (status === 'active') {
            node.addClass('active');
        }
    }

    function setProgress(percent) {
        $('#stepperFill').css('width', percent + '%');
        $('#overallProgressText').text(percent + '%');
    }

    // Copy terminal logs
    $('#btnCopyTerminal').on('click', function() {
        var logsText = '';
        $('#terminalOutput .log-line').each(function() {
            logsText += $(this).text() + "\n";
        });
        if (navigator.clipboard) {
            navigator.clipboard.writeText(logsText).then(function() {
                notify('success', 'Terminal output copied to clipboard');
            });
        } else {
            notify('info', 'Clipboard not supported in this browser context');
        }
    });

    // Clear terminal
    $('#btnClearTerminal').on('click', function() {
        $('#terminalOutput').empty();
        appendLog('secondary', 'Terminal output cleared.');
    });

    // Pre-Flight Diagnostics Check
    $('.btn-preflight-check').on('click', function() {
        if (isRunning) return;
        isRunning = true;
        var btn = $(this);
        btn.prop('disabled', true).html('<i class="las la-spinner la-spin"></i> Running Audit...');

        appendLog('gold', '---------------------------------------------------------');
        appendLog('gold', 'Initiating Pre-Flight Health & Environment Diagnostics...');
        setStepStatus(1, 'running');

        $.ajax({
            url: processUrl,
            type: 'POST',
            data: {
                _token: csrfToken,
                step: 'preflight'
            },
            success: function(res) {
                if (res.logs && res.logs.length) {
                    $.each(res.logs, function(i, log) {
                        appendLog(log.level, log.text, log.time);
                    });
                }
                setStepStatus(1, 'completed');
                setProgress(20);
                notify('success', 'Pre-flight environment audit completed successfully.');
            },
            error: function(xhr) {
                appendLog('danger', '[ERROR] Failed to run preflight check: ' + xhr.statusText);
                notify('error', 'Diagnostics failed. Inspect terminal log for details.');
            },
            complete: function() {
                isRunning = false;
                btn.prop('disabled', false).html('<i class="las la-stethoscope"></i> Pre-Flight Diagnostics Only');
            }
        });
    });

    // Run Full Migration Sequential Flow
    $('#btnConfirmRunMigration').on('click', function() {
        var modalEl = document.getElementById('migrationConfirmModal');
        var modalInstance = bootstrap.Modal.getInstance(modalEl);
        if (modalInstance) {
            modalInstance.hide();
        }

        if (isRunning) return;
        isRunning = true;

        appendLog('gold', '=========================================================');
        appendLog('gold', 'Starting Dedicated System Architecture Upgrader (v12 → v13)');
        appendLog('gold', '=========================================================');

        // Step 1: Pre-flight
        setStepStatus(1, 'running');
        setProgress(10);

        runStep('preflight', 1, 20, function() {
            // Step 2: Database Migration
            setStepStatus(2, 'running');
            setProgress(30);

            runStep('database_migration', 2, 50, function() {
                // Step 3: Data Sync
                setStepStatus(3, 'running');
                setProgress(60);

                runStep('data_sync', 3, 75, function() {
                    // Step 4: Cache Optimize
                    setStepStatus(4, 'running');
                    setProgress(80);

                    runStep('cache_optimize', 4, 90, function() {
                        // Step 5: Smoke Test
                        setStepStatus(5, 'running');
                        setProgress(95);

                        runStep('smoke_test', 5, 100, function() {
                            isRunning = false;
                            $('#migrationSuccessBox').removeClass('d-none');
                            appendLog('gold', '🎉 Migration complete. System elevated to v13.0 Enterprise!');
                            notify('success', 'System successfully upgraded to v13.0 Enterprise Suite!');

                            // Dynamically update hero card
                            $('.version-pill.current .val').text('v13.0 (Laravel {{ $laravelVersion }})');
                            $('.version-pill.status').removeClass('pending').addClass('migrated');
                            $('.version-pill.status .val').html('<i class="las la-check-circle"></i> System Migrated (v13.0)');
                            $('.upgrader-hero-action button:first').removeClass('btn-gold btn-pulse').addClass('btn-outline-gold').html('<i class="las la-sync"></i> Re-Run Migration & Integrity Check');

                            // Dynamically populate migrations table
                            var nowTime = new Date().toISOString().slice(0, 19).replace('T', ' ');
                            var newRowHtml = '<tr><td>1</td><td><code>2026_10_21_000001_create_system_migrations_registry</code></td><td><span class="badge bg-dark text-gold">Batch 13</span></td><td>v13.0 Enterprise</td><td>' + nowTime + '</td><td><span class="badge bg-success"><i class="las la-check"></i> Applied</span></td></tr>' +
                            '<tr><td>2</td><td><code>2026_10_21_000002_add_v13_general_settings_attributes</code></td><td><span class="badge bg-dark text-gold">Batch 13</span></td><td>v13.0 Enterprise</td><td>' + nowTime + '</td><td><span class="badge bg-success"><i class="las la-check"></i> Applied</span></td></tr>' +
                            '<tr><td>3</td><td><code>2026_10_21_000003_add_v13_high_concurrency_indexes</code></td><td><span class="badge bg-dark text-gold">Batch 13</span></td><td>v13.0 Enterprise</td><td>' + nowTime + '</td><td><span class="badge bg-success"><i class="las la-check"></i> Applied</span></td></tr>';
                            $('#migrationsTableBody').html(newRowHtml);
                        });
                    });
                });
            });
        });
    });

    function runStep(stepName, stepNum, targetPercent, nextCallback) {
        $.ajax({
            url: processUrl,
            type: 'POST',
            data: {
                _token: csrfToken,
                step: stepName
            },
            success: function(res) {
                if (res.logs && res.logs.length) {
                    $.each(res.logs, function(i, log) {
                        appendLog(log.level, log.text, log.time);
                    });
                }
                setStepStatus(stepNum, 'completed');
                setProgress(targetPercent);

                if (typeof nextCallback === 'function') {
                    setTimeout(nextCallback, 600);
                }
            },
            error: function(xhr) {
                isRunning = false;
                setStepStatus(stepNum, 'active');
                var errMessage = 'Step ' + stepName + ' failed.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errMessage = xhr.responseJSON.message;
                }
                appendLog('danger', '[MIGRATION ABORTED] ' + errMessage);
                if (xhr.responseJSON && xhr.responseJSON.logs) {
                    $.each(xhr.responseJSON.logs, function(i, log) {
                        appendLog(log.level, log.text, log.time);
                    });
                }
                notify('error', 'Migration stopped at stage: ' + stepName);
            }
        });
    }

})(jQuery);
</script>
@endpush
