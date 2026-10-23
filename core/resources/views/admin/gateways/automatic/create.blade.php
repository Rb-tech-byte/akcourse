@extends('admin.layouts.app')

@section('panel')
    @push('topBar')
        @include('admin.gateways.top_bar')
    @endpush

    <form action="{{ route('admin.gateway.automatic.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row gy-4">
            <!-- Left Column: Gateway Details & Dynamic Credentials -->
            <div class="col-xl-8 col-lg-7">
                <div class="card b-radius--10 mb-4">
                    <div class="card-header bg--primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title text-white mb-0">
                            <i class="las la-plus-circle me-1"></i> @lang('Add New Gateway')
                        </h5>
                        <span class="badge badge--warning">@lang('Automated Integration')</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="fw-bold">@lang('Gateway Name') <span class="text--danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="gatewayName" placeholder="e.g. Pesapal, Airtel Money, Lemonsqueezy" value="{{ old('name') }}" required>
                                <small class="text-muted">@lang('Display name shown to customers on checkout')</small>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="fw-bold">@lang('Gateway Alias / Slug') <span class="text--danger">*</span></label>
                                <input type="text" class="form-control" name="alias" id="gatewayAlias" placeholder="e.g. pesapal, airtel_money" value="{{ old('alias') }}" required>
                                <small class="text-muted">@lang('Unique identifier (lowercase, alphanumeric, underscores)')</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="fw-bold">@lang('Checkout Instructions / Description')</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="@lang('Optional payment instructions, account notes, or USSD steps to display to the user.')">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Credentials Parameters Builder -->
                <div class="card b-radius--10 mb-4">
                    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div>
                            <h5 class="card-title mb-0">
                                <i class="las la-key me-1"></i> @lang('Dynamic Gateway Credentials & Parameters')
                            </h5>
                            <small class="text-muted">@lang('Define the API keys, secrets, or configuration fields required by this gateway.')</small>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline--primary" id="addCredentialBtn">
                            <i class="las la-plus"></i> @lang('Add Credential Field')
                        </button>
                    </div>

                    <!-- Quick Preset Templates Bar -->
                    <div class="card-body border-bottom py-2" style="background: rgba(255,255,255,0.02);">
                        <span class="small fw-bold text-muted me-2">@lang('Quick Presets:'):</span>
                        <button type="button" class="btn btn-xs btn-outline-info me-1 preset-btn" data-preset="api">
                            @lang('API Key & Secret')
                        </button>
                        <button type="button" class="btn btn-xs btn-outline-info me-1 preset-btn" data-preset="merchant">
                            @lang('Merchant & App ID')
                        </button>
                        <button type="button" class="btn btn-xs btn-outline-info me-1 preset-btn" data-preset="mobile">
                            @lang('Mobile Money (Till / Paybill)')
                        </button>
                        <button type="button" class="btn btn-xs btn-outline-info preset-btn" data-preset="redirect">
                            @lang('Hosted Redirect URL')
                        </button>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table--light style--two" id="credentialsTable">
                                <thead>
                                    <tr>
                                        <th style="width: 25%;">@lang('Parameter Key') <span class="text--danger">*</span></th>
                                        <th style="width: 25%;">@lang('Display Title') <span class="text--danger">*</span></th>
                                        <th style="width: 35%;">@lang('Default Value')</th>
                                        <th style="width: 10%; text-align: center;">@lang('Global')</th>
                                        <th style="width: 5%;"></th>
                                    </tr>
                                </thead>
                                <tbody id="credentialsList">
                                    <!-- Default Row 1: API Key -->
                                    <tr class="credential-row">
                                        <td>
                                            <input type="text" name="credentials[0][key]" class="form-control form-control-sm" placeholder="api_key" value="api_key" required>
                                        </td>
                                        <td>
                                            <input type="text" name="credentials[0][title]" class="form-control form-control-sm" placeholder="API Key" value="API Key" required>
                                        </td>
                                        <td>
                                            <input type="text" name="credentials[0][value]" class="form-control form-control-sm" placeholder="Enter API Key / Public Key">
                                        </td>
                                        <td class="text-center align-middle">
                                            <input type="checkbox" name="credentials[0][global]" value="1" checked title="@lang('Global setting applies to all currencies')">
                                        </td>
                                        <td class="text-center align-middle">
                                            <button type="button" class="btn btn-sm btn-outline--danger remove-row-btn"><i class="la la-trash"></i></button>
                                        </td>
                                    </tr>
                                    <!-- Default Row 2: Secret Key -->
                                    <tr class="credential-row">
                                        <td>
                                            <input type="text" name="credentials[1][key]" class="form-control form-control-sm" placeholder="secret_key" value="secret_key" required>
                                        </td>
                                        <td>
                                            <input type="text" name="credentials[1][title]" class="form-control form-control-sm" placeholder="Secret Key" value="Secret Key" required>
                                        </td>
                                        <td>
                                            <input type="text" name="credentials[1][value]" class="form-control form-control-sm" placeholder="Enter Secret Key / Private Key">
                                        </td>
                                        <td class="text-center align-middle">
                                            <input type="checkbox" name="credentials[1][global]" value="1" checked title="@lang('Global setting applies to all currencies')">
                                        </td>
                                        <td class="text-center align-middle">
                                            <button type="button" class="btn btn-sm btn-outline--danger remove-row-btn"><i class="la la-trash"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Initial Currency Configuration -->
                <div class="card b-radius--10 mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="las la-money-bill-wave me-1"></i> @lang('Initial Currency & Processing Limit')
                        </h5>
                        <small class="text-muted">@lang('Configure the primary currency for this gateway. Additional currencies can be added anytime.')</small>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label class="fw-bold">@lang('Currency Code')</label>
                                <input type="text" class="form-control text-uppercase" name="init_currency" id="initCurrency" placeholder="e.g. USD, TZS" value="USD">
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="fw-bold">@lang('Currency Symbol')</label>
                                <input type="text" class="form-control" name="init_symbol" id="initSymbol" placeholder="e.g. $, TSh" value="$">
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="fw-bold">@lang('Exchange Rate')</label>
                                <div class="input-group">
                                    <span class="input-group-text">1 {{ gs('cur_text') }} =</span>
                                    <input type="number" step="any" class="form-control" name="init_rate" value="1" required>
                                </div>
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="fw-bold">@lang('Fixed Charge')</label>
                                <div class="input-group">
                                    <input type="number" step="any" class="form-control" name="init_fixed_charge" value="0">
                                    <span class="input-group-text">{{ gs('cur_text') }}</span>
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <label class="fw-bold">@lang('Minimum Amount')</label>
                                <div class="input-group">
                                    <input type="number" step="any" class="form-control" name="init_min_amount" value="1">
                                    <span class="input-group-text">{{ gs('cur_text') }}</span>
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <label class="fw-bold">@lang('Maximum Amount')</label>
                                <div class="input-group">
                                    <input type="number" step="any" class="form-control" name="init_max_amount" value="1000000">
                                    <span class="input-group-text">{{ gs('cur_text') }}</span>
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <label class="fw-bold">@lang('Percent Charge')</label>
                                <div class="input-group">
                                    <input type="number" step="any" class="form-control" name="init_percent_charge" value="0">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Library Dependencies & Custom Database Tables -->
                <div class="card b-radius--10 mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="las la-cubes me-1"></i> @lang('Library Dependencies & Custom Tables')
                        </h5>
                        <small class="text-muted">@lang('Optional: Track composer packages or tables so they are cleanly deleted if this gateway is removed.')</small>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label class="fw-bold">@lang('Composer Package Dependencies')</label>
                            <input type="text" name="packages" class="form-control" placeholder="e.g. mollie/laravel-mollie, pesapal/pesapal-php (comma-separated)">
                            <small class="text-muted">@lang('Packages entered here will be tracked and cleanly uninstalled from vendor/ and composer.json if this gateway is deleted.')</small>
                        </div>
                        <div class="form-group mb-0">
                            <label class="fw-bold">@lang('Associated Custom Database Tables')</label>
                            <input type="text" name="custom_tables" class="form-control" placeholder="e.g. pesapal_ipn_logs, selcom_transactions (comma-separated)">
                            <small class="text-muted">@lang('Custom tables created for this gateway that should be safely dropped when deleted (core tables are strictly protected).')</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Logo & Supported Currencies -->
            <div class="col-xl-4 col-lg-5">
                <!-- Gateway Logo -->
                <div class="card b-radius--10 mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="las la-image me-1"></i> @lang('Gateway Logo / Icon')</h5>
                    </div>
                    <div class="card-body">
                        <div class="image-upload">
                            <div class="thumb">
                                <div class="avatar-preview">
                                    <div class="profilePicPreview" id="logoPreview" style="background-image: url('{{ getImage('/', getFileSize('gateway')) }}'); height: 160px; background-size: contain; background-repeat: no-repeat; background-position: center; border: 1px dashed rgba(255,255,255,0.2); border-radius: 8px;">
                                    </div>
                                </div>
                                <div class="avatar-edit mt-3">
                                    <input type="file" class="form-control" name="image" id="gatewayLogoInput" accept=".png, .jpg, .jpeg">
                                    <small class="text-muted d-block mt-1">@lang('Supported formats: png, jpg, jpeg. Recommended size: 400x400px.')</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Supported Currencies -->
                <div class="card b-radius--10 mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="las la-coins me-1"></i> @lang('Supported Currencies')</h5>
                        <small class="text-muted">@lang('Click badges to toggle currencies allowed for this gateway.')</small>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @foreach($defaultCurrencies as $cur => $sym)
                                <button type="button" class="btn btn-sm btn-outline--light currency-toggle-btn @if(in_array($cur, ['USD', 'TZS'])) active btn--primary @endif" data-cur="{{ $cur }}" data-sym="{{ $sym }}">
                                    {{ $cur }} ({{ $sym }})
                                </button>
                            @endforeach
                        </div>
                        <div id="hiddenCurrencies">
                            <input type="hidden" name="supported_currencies[]" value="USD" id="cur_USD">
                            <input type="hidden" name="currency_symbols[USD]" value="$" id="sym_USD">
                            <input type="hidden" name="supported_currencies[]" value="TZS" id="cur_TZS">
                            <input type="hidden" name="currency_symbols[TZS]" value="TSh" id="sym_TZS">
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="card b-radius--10">
                    <div class="card-body">
                        <button type="submit" class="btn btn--primary w-100 py-3 fw-bold">
                            <i class="la la-check-circle me-1"></i> @lang('Save & Create Gateway')
                        </button>
                        <a href="{{ route('admin.gateway.automatic.index') }}" class="btn btn-outline--dark w-100 mt-2">
                            @lang('Cancel')
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.gateway.automatic.index') }}" class="btn btn-sm btn-outline--primary">
        <i class="la la-undo"></i> @lang('Back to Gateways')
    </a>
@endpush

@push('script')
<script>
    (function($) {
        "use strict";

        // Auto slug alias from Name
        $('#gatewayName').on('input', function() {
            var val = $(this).val();
            var slug = val.toLowerCase().replace(/[^a-z0-9]/g, '_').replace(/_+/g, '_');
            $('#gatewayAlias').val(slug);
        });

        // Logo image preview
        $("#gatewayLogoInput").change(function() {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#logoPreview').css('background-image', 'url(' + e.target.result + ')');
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

        // Currency Toggle
        $('.currency-toggle-btn').on('click', function() {
            var cur = $(this).data('cur');
            var sym = $(this).data('sym');
            if ($(this).hasClass('active')) {
                $(this).removeClass('active btn--primary').addClass('btn-outline--light');
                $('#cur_' + cur).remove();
                $('#sym_' + cur).remove();
            } else {
                $(this).addClass('active btn--primary').removeClass('btn-outline--light');
                $('#hiddenCurrencies').append('<input type="hidden" name="supported_currencies[]" value="' + cur + '" id="cur_' + cur + '">');
                $('#hiddenCurrencies').append('<input type="hidden" name="currency_symbols[' + cur + ']" value="' + sym + '" id="sym_' + cur + '">');
            }
        });

        // Dynamic Credentials Repeater
        var rowIndex = 2;

        function addCredentialRow(key, title, val, isGlobal) {
            key = key || '';
            title = title || '';
            val = val || '';
            var checked = (isGlobal !== false) ? 'checked' : '';

            var html = `
                <tr class="credential-row">
                    <td>
                        <input type="text" name="credentials[${rowIndex}][key]" class="form-control form-control-sm" placeholder="e.g. public_key" value="${key}" required>
                    </td>
                    <td>
                        <input type="text" name="credentials[${rowIndex}][title]" class="form-control form-control-sm" placeholder="e.g. Public Key" value="${title}" required>
                    </td>
                    <td>
                        <input type="text" name="credentials[${rowIndex}][value]" class="form-control form-control-sm" placeholder="Default / Config value" value="${val}">
                    </td>
                    <td class="text-center align-middle">
                        <input type="checkbox" name="credentials[${rowIndex}][global]" value="1" ${checked}>
                    </td>
                    <td class="text-center align-middle">
                        <button type="button" class="btn btn-sm btn-outline--danger remove-row-btn"><i class="la la-trash"></i></button>
                    </td>
                </tr>
            `;
            $('#credentialsList').append(html);
            rowIndex++;
        }

        $('#addCredentialBtn').on('click', function() {
            addCredentialRow('', '', '', true);
        });

        $(document).on('click', '.remove-row-btn', function() {
            if ($('.credential-row').length > 1) {
                $(this).closest('tr').remove();
            } else {
                notify('error', '@lang("At least one credential field is recommended")');
            }
        });

        // Preset Templates
        $('.preset-btn').on('click', function() {
            var preset = $(this).data('preset');
            $('#credentialsList').empty();
            rowIndex = 0;

            if (preset === 'api') {
                addCredentialRow('api_key', 'API Key', '', true);
                addCredentialRow('secret_key', 'Secret Key', '', true);
                addCredentialRow('webhook_secret', 'Webhook Secret', '', true);
            } else if (preset === 'merchant') {
                addCredentialRow('merchant_id', 'Merchant ID', '', true);
                addCredentialRow('app_id', 'App ID / Client ID', '', true);
                addCredentialRow('api_key', 'API Key', '', true);
                addCredentialRow('secret_key', 'Secret Key', '', true);
            } else if (preset === 'mobile') {
                addCredentialRow('till_number', 'Till / Paybill Number', '', true);
                addCredentialRow('merchant_code', 'Merchant Code', '', true);
                addCredentialRow('passkey', 'API Passkey', '', true);
                addCredentialRow('webhook_secret', 'Webhook Secret', '', true);
            } else if (preset === 'redirect') {
                addCredentialRow('checkout_url', 'Checkout / Redirect URL', 'https://', true);
                addCredentialRow('merchant_id', 'Merchant Account', '', true);
                addCredentialRow('secret_key', 'Secret / Hash Key', '', true);
            }
        });

    })(jQuery);
</script>
@endpush
