@extends('admin.layouts.app')
@section('panel')
    @push('topBar')
        @include('admin.gateways.top_bar')
    @endpush
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('admin.gateway.automatic.update', $gateway->code) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="alias" value="{{ $gateway->alias }}">

                    <div class="card-body">
                        <div class="payment-method-item block-item">
                            <div class="row align-items-center gy-1 mb-3">
                                <div class="col-lg-8 col-sm-6">
                                    <div class="row g-2">
                                        <div class="col-md-7">
                                            <label class="fw-bold small text-muted">@lang('Gateway Name')</label>
                                            <input type="text" name="name" class="form-control fw-bold" value="{{ $gateway->name }}" required>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="fw-bold small text-muted">@lang('Alias / Slug')</label>
                                            <input type="text" name="alias" class="form-control" value="{{ $gateway->alias }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6">
                                    @if (count($supportedCurrencies) > 0)
                                        <div class="input-group d-flex flex-nowrap justify-content-sm-end">
                                            <select class="newCurrencyVal ">
                                                <option value="">@lang('Select currency')</option>
                                                @forelse($supportedCurrencies as $currency => $symbol)
                                                    <option value="{{ $currency }}" data-symbol="{{ $symbol }}">
                                                        {{ __($currency) }} </option>
                                                @empty
                                                    <option value="">@lang('No available currency support')</option>
                                                @endforelse

                                            </select>
                                            <button type="button" class="btn btn--primary input-group-text newCurrencyBtn"
                                                data-crypto="{{ $gateway->crypto }}"
                                                data-name="{{ $gateway->name }}">@lang('Add new')</button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="gateway-body">
                                <div class="gateway-thumb">
                                    <div class="thumb">
                                        <x-image-uploader image="{{ $gateway->image }}" class="w-100" type="gateway"
                                            :required=false />
                                    </div>
                                </div>
                                <div class="gateway-content">
                                    @if ($gateway->code < 1000 && $gateway->extra)
                                        <div class="payment-method-body mt-2">
                                            <h4 class="mb-3 payment-method-body-title">@lang('Configurations')</h4>
                                            <div class="row">
                                                @foreach ($gateway->extra as $key => $param)
                                                    <div class="form-group col-lg-6">
                                                        <label>{{ __(@$param->title) }}</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control"
                                                                value="{{ route($param->value) }}" readonly>
                                                            <button type="button" class="copyInput input-group-text"
                                                                title="@lang('Copy')"><i
                                                                    class="fas fa-copy"></i></button>
                                                        </div>
                                                        @if ($key == 'cron')
                                                            <small><i class="las la-info-circle"></i>
                                                                @lang('Set the URL to your server\'s cron job to validate the payment.')</small>
                                                        @endif

                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    <div class="payment-method-body mt-2">
                                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                                            <h4 class="payment-method-body-title mb-0">
                                                <i class="las la-key me-1"></i> @lang('Global Credentials & Parameters')
                                            </h4>
                                            <button type="button" class="btn btn-sm btn-outline--primary" id="addEditCredentialBtn">
                                                <i class="las la-plus"></i> @lang('Add Credential Field')
                                            </button>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="small text-muted fw-bold">@lang('Checkout Instructions / Description')</label>
                                            <textarea name="description" class="form-control" rows="2" placeholder="@lang('Instructions or notes displayed to customers...')">{{ $gateway->description }}</textarea>
                                        </div>

                                        <div class="row" id="editCredentialsContainer">
                                            @php $cIdx = 0; @endphp
                                            @foreach ($parameters->where('global', true) as $key => $param)
                                                <div class="form-group col-xl-6 col-lg-12 col-md-6 edit-cred-item">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <label class="mb-0 fw-bold">{{ __(is_object($param) ? @$param->title : ucwords(str_replace('_', ' ', $key))) }}</label>
                                                        <button type="button" class="btn btn-xs btn-outline-danger remove-edit-cred-btn" title="@lang('Remove parameter')">
                                                            <i class="la la-trash"></i>
                                                        </button>
                                                    </div>
                                                    <input type="hidden" name="credentials[{{ $cIdx }}][key]" value="{{ $key }}">
                                                    <input type="hidden" name="credentials[{{ $cIdx }}][title]" value="{{ is_object($param) ? @$param->title : ucwords(str_replace('_', ' ', $key)) }}">
                                                    <input type="hidden" name="credentials[{{ $cIdx }}][global]" value="1">
                                                    <input type="text" class="form-control"
                                                        name="credentials[{{ $cIdx }}][value]" value="{{ is_object($param) ? @$param->value : $param }}"
                                                        required>
                                                </div>
                                                @php $cIdx++; @endphp
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="payment-method-body mt-4 pt-3 border-top" style="border-color: rgba(255,255,255,0.07) !important;">
                                        <h4 class="payment-method-body-title mb-1">
                                            <i class="las la-cubes me-1"></i> @lang('Library Dependencies & Custom Database Tables')
                                        </h4>
                                        <small class="text-muted d-block mb-3">@lang('Track composer packages and database tables associated with this gateway so they can be cleaned automatically if deleted.')</small>
                                        
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label class="fw-bold">@lang('Composer Package Dependencies')</label>
                                                <input type="text" name="packages" class="form-control" 
                                                       placeholder="e.g. mollie/laravel-mollie, stripe/stripe-php (comma-separated)" 
                                                       value="{{ implode(', ', $gateway->getPackages()) }}">
                                                <small class="text-muted">@lang('Packages uninstalled from composer.json and vendor/ upon gateway deletion.')</small>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label class="fw-bold">@lang('Associated Custom Database Tables')</label>
                                                <input type="text" name="custom_tables" class="form-control" 
                                                       placeholder="e.g. selcom_transactions, pesapal_logs (comma-separated)" 
                                                       value="{{ implode(', ', $gateway->getCustomTables()) }}">
                                                <small class="text-muted">@lang('Custom tables safely dropped upon gateway deletion (core tables strictly protected).')</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- payment-method-item start -->

                        @isset($gateway->currencies)
                            @foreach ($gateway->currencies as $gatewayCurrency)
                                <input type="hidden" name="currency[{{ $currencyIndex }}][symbol]"
                                    value="{{ $gatewayCurrency->symbol }}">
                                <div class="payment-method-item block-item child--item">
                                    <div class="payment-method-header">
                                        <div class="content">
                                            <div class="d-flex justify-content-between">
                                                <h4 class="mb-3">{{ __($gateway->name) }} -
                                                    {{ __($gatewayCurrency->currency) }}</h4>
                                                <div class="remove-btn">
                                                    <button type="button" class="btn btn--danger confirmationBtn"
                                                        data-question="@lang('Are you sure to delete this gateway currency?')"
                                                        data-action="{{ route('admin.gateway.automatic.remove', $gatewayCurrency->id) }}">
                                                        <i class="la la-trash-o me-2"></i>@lang('Remove')
                                                    </button>
                                                </div>


                                            </div>
                                            <div class="form-group payment-method-title-input">
                                                <input type="text" class="form-control"
                                                    name="currency[{{ $currencyIndex }}][name]"
                                                    value="{{ $gatewayCurrency->name }}" required>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="payment-method-body">
                                        <div class="row g-4">
                                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                                <div class="card border border--primary">
                                                    <h5 class="card-header bg--primary">@lang('Range')</h5>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label>@lang('Minimum Amount')</label>
                                                            <div class="input-group">
                                                                <input type="number" step="any"
                                                                    class="form-control minAmount"
                                                                    name="currency[{{ $currencyIndex }}][min_amount]"
                                                                    value="{{ getAmount($gatewayCurrency->min_amount) }}"
                                                                    required>
                                                                <div class="input-group-text">{{ __(gs('cur_text')) }}</div>
                                                            </div>
                                                            <span class="min-amount-error-message text--danger"></span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>@lang('Maximum Amount')</label>
                                                            <div class="input-group">
                                                                <input type="number" step="any"
                                                                    class="form-control maxAmount"
                                                                    name="currency[{{ $currencyIndex }}][max_amount]"
                                                                    value="{{ getAmount($gatewayCurrency->max_amount) }}"
                                                                    required>
                                                                <div class="input-group-text">{{ __(gs('cur_text')) }}</div>
                                                            </div>
                                                            <span class="max-amount-error-message text--danger"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                                <div class="card border border--primary">
                                                    <h5 class="card-header bg--primary">@lang('Charge')</h5>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label>@lang('Fixed Charge')</label>
                                                            <div class="input-group">
                                                                <input type="number" step="any" class="form-control"
                                                                    name="currency[{{ $currencyIndex }}][fixed_charge]"
                                                                    value="{{ getAmount($gatewayCurrency->fixed_charge) }}"
                                                                    required>
                                                                <div class="input-group-text">{{ __(gs('cur_text')) }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>@lang('Percent Charge')</label>
                                                            <div class="input-group">
                                                                <input type="number" step="any" class="form-control"
                                                                    name="currency[{{ $currencyIndex }}][percent_charge]"
                                                                    value="{{ getAmount($gatewayCurrency->percent_charge) }}"
                                                                    required>
                                                                <div class="input-group-text">%</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                                <div class="card border border--primary">
                                                    <h5 class="card-header bg--primary">@lang('Currency')</h5>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>@lang('Currency')</label>
                                                                    <input type="text"
                                                                        name="currency[{{ $currencyIndex }}][currency]"
                                                                        class="form-control border-radius-5 "
                                                                        value="{{ $gatewayCurrency->currency }}" readonly>
                                                                </div>

                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>@lang('Symbol')</label>
                                                                    <input type="text"
                                                                        name="currency[{{ $currencyIndex }}][symbol]"
                                                                        class="form-control border-radius-5 symbl"
                                                                        value="{{ $gatewayCurrency->symbol }}"
                                                                        data-crypto="{{ $gateway->crypto }}" required>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>@lang('Rate')</label>
                                                            <div class="input-group">
                                                                <div class="input-group-text">1 {{ gs('cur_text') }} =</div>
                                                                <input type="number" step="any" class="form-control"
                                                                    name="currency[{{ $currencyIndex }}][rate]"
                                                                    value="{{ getAmount($gatewayCurrency->rate) }}" required>
                                                                <div class="input-group-text"><span
                                                                        class="currency_symbol">{{ __($gatewayCurrency->baseSymbol()) }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            @if ($parameters->where('global', false)->count() != 0)
                                                @php
                                                    $globalParameters = json_decode(
                                                        $gatewayCurrency->gateway_parameter,
                                                    );
                                                @endphp
                                                <div class="col-lg-12">
                                                    <div class="card border border--dark mt-4">
                                                        <h5 class="card-header bg--dark">@lang('Configuration')</h5>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                @foreach ($parameters->where('global', false) as $key => $param)
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>{{ __(is_object($param) ? @$param->title : ucwords(str_replace('_', ' ', $key))) }}</label>
                                                                            <input type="text" class="form-control"
                                                                                name="currency[{{ $currencyIndex }}][param][{{ $key }}]"
                                                                                value="{{ is_object($globalParameters) ? @$globalParameters->$key : '' }}"
                                                                                required>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @php $currencyIndex++ @endphp
                            @endforeach
                        @endisset

                        <!-- payment-method-item end -->


                        <!-- **new payment-method-item start -->
                        <div class="payment-method-item child--item newMethodCurrency d-none">
                            <input disabled type="hidden" name="currency[{{ $currencyIndex }}][symbol]"
                                class="currencySymbol">
                            <div class="payment-method-header">
                                <div class="content">
                                    <div class="d-flex justify-content-between">
                                        <div class="form-group">
                                            <h4 class="mb-3" id="payment_currency_name">@lang('Name')</h4>
                                            <input disabled type="text" class="form-control"
                                                name="currency[{{ $currencyIndex }}][name]" required>
                                        </div>
                                        <div class="remove-btn">
                                            <button type="button" class="btn btn-danger newCurrencyRemove">
                                                <i class="la la-trash-o me-2"></i>@lang('Remove')
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="payment-method-body">
                                <div class="row g-4">
                                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                        <div class="card border border--primary">
                                            <h5 class="card-header bg--primary">@lang('Range')</h5>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label>@lang('Minimum Amount')</label>
                                                    <div class="input-group">
                                                        <div class="input-group-text">{{ __(gs('cur_text')) }}</div>
                                                        <input disabled type="number" step="any"
                                                            class="form-control minAmount"
                                                            name="currency[{{ $currencyIndex }}][min_amount]" required>
                                                    </div>
                                                    <span class="min-amount-error-message text--danger"></span>
                                                </div>

                                                <div class="form-group">
                                                    <label>@lang('Maximum Amount')</label>
                                                    <div class="input-group">
                                                        <div class="input-group-text">{{ __(gs('cur_text')) }}</div>
                                                        <input disabled type="number" step="any"
                                                            class="form-control maxAmount"
                                                            name="currency[{{ $currencyIndex }}][max_amount]" required>
                                                    </div>
                                                    <span class="max-amount-error-message text--danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                        <div class="card border border--primary">
                                            <h5 class="card-header bg--primary">@lang('Charge')</h5>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label>@lang('Fixed Charge')</label>
                                                    <div class="input-group">
                                                        <div class="input-group-text">{{ __(gs('cur_text')) }}</div>
                                                        <input disabled type="number" step="any"
                                                            class="form-control"
                                                            name="currency[{{ $currencyIndex }}][fixed_charge]" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>@lang('Percent Charge')</label>
                                                    <div class="input-group">
                                                        <div class="input-group-text">%</div>
                                                        <input disabled type="number" step="any"
                                                            class="form-control"
                                                            name="currency[{{ $currencyIndex }}][percent_charge]"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                        <div class="card border border--primary">
                                            <h5 class="card-header bg--primary">@lang('Currency')</h5>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>@lang('Currency')</label>
                                                            <input disabled type="step"
                                                                class="form-control currencyText border-radius-5"
                                                                name="currency[{{ $currencyIndex }}][currency]" readonly>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>@lang('Symbol')</label>
                                                            <input disabled type="text"
                                                                name="currency[{{ $currencyIndex }}][symbol]"
                                                                class="form-control border-radius-5 symbl"
                                                                ata-crypto="{{ $gateway->crypto }}" disabled>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label>@lang('Rate')</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">
                                                            1 {{ __(gs('cur_text')) }} =
                                                        </span>
                                                        <input disabled type="number" step="any"
                                                            class="form-control"
                                                            name="currency[{{ $currencyIndex }}][rate]" required>
                                                        <div class="input-group-text"><span
                                                                class="currency_symbol"></span></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($parameters->where('global', false)->count() != 0)
                                        <div class="col-lg-12">
                                            <div class="card border border--dark mt-4">
                                                <h5 class="card-header bg--dark">@lang('Configuration')</h5>
                                                <div class="card-body">
                                                    <div class="row">
                                                        @foreach ($parameters->where('global', false) as $key => $param)
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>{{ __(is_object($param) ? @$param->title : ucwords(str_replace('_', ' ', $key))) }}</label>
                                                                    <input disabled type="text" class="form-control"
                                                                        name="currency[{{ $currencyIndex }}][param][{{ $key }}]"
                                                                        required>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                        <!-- **new payment-method-item end -->
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">
                            @lang('Submit')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <x-confirmation-modal />

    <!-- Advanced Codebase Cleanup Deletion Modal -->
    <div id="advancedDeleteModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="background: #17181F; border: 1px solid rgba(255, 75, 75, 0.3); border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.6);">
                <div class="modal-header border-bottom" style="border-color: rgba(255,255,255,0.08) !important;">
                    <h5 class="modal-title text-white">
                        <i class="las la-trash-alt text-danger me-2"></i>@lang('Delete Gateway & Clean Codebase')
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" id="advancedDeleteForm">
                    @csrf
                    <div class="modal-body text-start">
                        <div class="alert alert-danger p-3 mb-3" style="background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; border-radius: 8px;">
                            <i class="las la-exclamation-triangle me-1"></i>
                            @lang('You are deleting') <strong class="text-white" id="delGatewayName"></strong> (<span id="delGatewayAlias" class="font-monospace"></span>).
                            @lang('Select components to clean so no orphaned files or packages remain in your codebase:')
                        </div>

                        <!-- Cleanup Checklist -->
                        <div class="p-3 mb-2 rounded-3" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07);">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" checked disabled>
                                <label class="form-check-label text-white small fw-bold">
                                    @lang('Gateway Record &') <span id="delCurrenciesCount">0</span> @lang('Configured Currency Settings (Always removed)')
                                </label>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="remove_files" value="1" checked id="delFilesCheck">
                                <label class="form-check-label text-white small fw-bold" for="delFilesCheck">
                                    @lang('Codebase Files (Controller Directory, Payment Views & Logo)')
                                </label>
                                <small class="text-muted d-block ps-4 font-monospace" style="font-size: 11px;">
                                    app/Http/Controllers/Gateway/<span id="delControllerDir"></span>
                                </small>
                            </div>

                            <div class="form-check mb-3" id="delPackagesGroup">
                                <input class="form-check-input" type="checkbox" name="remove_packages" value="1" checked id="delPackagesCheck">
                                <label class="form-check-label text-white small fw-bold" for="delPackagesCheck">
                                    @lang('Composer Package Dependencies'):
                                    <span class="badge badge--danger text-white ms-1" id="delPackagesBadge">None</span>
                                </label>
                                <small class="text-muted d-block ps-4" style="font-size: 11px;">
                                    @lang('Uninstalls package from composer.json and vendor/ to keep codebase lean.')
                                </small>
                            </div>

                            <div class="form-check" id="delTablesGroup">
                                <input class="form-check-input" type="checkbox" name="remove_tables" value="1" checked id="delTablesCheck">
                                <label class="form-check-label text-white small fw-bold" for="delTablesCheck">
                                    @lang('Associated Custom Database Tables'):
                                    <span class="badge badge--warning text-dark ms-1" id="delTablesBadge">None</span>
                                </label>
                                <small class="text-muted d-block ps-4" style="font-size: 11px;">
                                    @lang('Safely drops gateway-specific tables (core system tables are strictly protected).')
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top" style="border-color: rgba(255,255,255,0.08) !important;">
                        <button type="button" class="btn btn-sm btn--dark" data-bs-dismiss="modal">@lang('Cancel')</button>
                        <button type="submit" class="btn btn-sm btn--danger">
                            <i class="las la-trash"></i> @lang('Delete & Clean Everything')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection





@push('breadcrumb-plugins')
    @php
        $packages = $gateway->getPackages();
        $customTables = $gateway->getCustomTables();
    @endphp
    <button type="button" class="btn btn-sm btn-outline--danger advancedDeleteBtn me-2" 
            data-id="{{ $gateway->id }}"
            data-name="{{ $gateway->name }}"
            data-alias="{{ $gateway->alias }}"
            data-code="{{ $gateway->code }}"
            data-currencies="{{ $gateway->currencies->count() }}"
            data-packages="{{ implode(', ', $packages) }}"
            data-tables="{{ implode(', ', $customTables) }}"
            data-action="{{ route('admin.gateway.automatic.delete', $gateway->id) }}">
        <i class="la la-trash"></i> @lang('Delete Gateway & Clean Codebase')
    </button>
    <x-back route="{{ route('admin.gateway.automatic.index') }}" />
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            $('.newCurrencyBtn').on('click', function() {
                var form = $('.newMethodCurrency');
                var getCurrencySelected = $('.newCurrencyVal').find(':selected').val();
                var currency = $(this).data('crypto') == 1 ? 'USD' : `${getCurrencySelected}`;
                if (!getCurrencySelected) return;
                form.find('input').removeAttr('disabled');
                var symbol = $('.newCurrencyVal').find(':selected').data('symbol');
                form.find('.currencyText').val(getCurrencySelected);
                form.find('.currency_symbol').text(currency);
                $('#payment_currency_name').text(`${$(this).data('name')} - ${getCurrencySelected}`);
                form.removeClass('d-none');
                $('html, body').animate({
                    scrollTop: $('html, body').height()
                }, 'slow');

                $('.newCurrencyRemove').on('click', function() {
                    form.find('input').val('');
                    form.remove();
                });
            });

            $('.symbl').on('input', function() {
                var curText = $(this).data('crypto') == 1 ? 'USD' : $(this).val();
                $(this).parents('.payment-method-body').find('.currency_symbol').text(curText);
            });

            $('.copyInput').on('click', function(e) {
                var copybtn = $(this);
                var input = copybtn.closest('.input-group').find('input');
                if (input && input.select) {
                    input.select();
                    try {
                        document.execCommand('SelectAll')
                        document.execCommand('Copy', false, null);
                        input.blur();
                        notify('success', `Copied: ${copybtn.closest('.input-group').find('input').val()}`);
                    } catch (err) {
                        alert('Please press Ctrl/Cmd + C to copy');
                    }
                }
            });


            let minAmountValue;
            let maxAmountValue;
            let hasError = false;

            function validateInput() {
                let input = $(this);
                let container = input.closest('.card-body');
                let minAmount = container.find('.minAmount');
                let maxAmount = container.find('.maxAmount');
                let minAmountErrorMessage = container.find('.min-amount-error-message');
                let maxAmountErrorMessage = container.find('.max-amount-error-message');

                minAmountValue = Number(minAmount.val());
                maxAmountValue = Number(maxAmount.val());

                if (!minAmountValue) {
                    minAmountErrorMessage.text('@lang('Minimum amount field is required')');
                    maxAmountErrorMessage.empty();
                    return;
                }

                if (!maxAmountValue) {
                    maxAmountErrorMessage.text('@lang('Maximum amount field is required')');
                    minAmountErrorMessage.empty();
                    return;
                }

                if (minAmountValue >= maxAmountValue) {
                    minAmountErrorMessage.text('@lang('Minimum amount should be less than maximum amount')');
                    maxAmountErrorMessage.empty();
                    hasError = true;
                    return;
                }
                minAmountErrorMessage.empty();
                maxAmountErrorMessage.empty();
                hasError = false;
            }


            $(document).on('input', '.minAmount, .maxAmount', validateInput);

            $('form').on('submit', function(e) {
                validateInput();
                if (hasError) {
                    e.preventDefault();
                }
            });

            var credCounter = {{ $cIdx ?? 100 }};
            $('#addEditCredentialBtn').on('click', function() {
                var key = prompt('@lang("Enter parameter key (e.g. webhook_secret, merchant_id, passkey):")');
                if (!key) return;
                key = key.toLowerCase().replace(/[^a-z0-9_]/g, '_');
                var title = prompt('@lang("Enter display title (e.g. Webhook Secret, Merchant ID):")', key.replace(/_/g, ' '));
                if (!title) title = key;

                var html = `
                    <div class="form-group col-xl-6 col-lg-12 col-md-6 edit-cred-item">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="mb-0 fw-bold text--primary">${title} <small class="text-muted">(${key})</small></label>
                            <button type="button" class="btn btn-xs btn-outline-danger remove-edit-cred-btn" title="@lang('Remove parameter')">
                                <i class="la la-trash"></i>
                            </button>
                        </div>
                        <input type="hidden" name="credentials[${credCounter}][key]" value="${key}">
                        <input type="hidden" name="credentials[${credCounter}][title]" value="${title}">
                        <input type="hidden" name="credentials[${credCounter}][global]" value="1">
                        <input type="text" class="form-control border--primary" name="credentials[${credCounter}][value]" placeholder="@lang('Enter value for') ${title}" required>
                    </div>
                `;
                $('#editCredentialsContainer').append(html);
                credCounter++;
            });

            $(document).on('click', '.remove-edit-cred-btn', function() {
                if (confirm('@lang("Are you sure you want to remove this credential field?")')) {
                    $(this).closest('.edit-cred-item').remove();
                }
            });

            $('.advancedDeleteBtn').on('click', function() {
                var btn = $(this);
                var action = btn.data('action');
                var name = btn.data('name');
                var alias = btn.data('alias');
                var currencies = btn.data('currencies');
                var packages = btn.data('packages');
                var tables = btn.data('tables');

                $('#advancedDeleteForm').attr('action', action);
                $('#delGatewayName').text(name);
                $('#delGatewayAlias').text(alias);
                $('#delCurrenciesCount').text(currencies);
                $('#delControllerDir').text(alias.charAt(0).toUpperCase() + alias.slice(1));

                if (packages && packages.length > 0) {
                    $('#delPackagesBadge').text(packages).show();
                    $('#delPackagesGroup').show();
                    $('#delPackagesCheck').prop('checked', true);
                } else {
                    $('#delPackagesBadge').text('@lang("None detected")');
                    $('#delPackagesCheck').prop('checked', false);
                }

                if (tables && tables.length > 0) {
                    $('#delTablesBadge').text(tables).show();
                    $('#delTablesGroup').show();
                    $('#delTablesCheck').prop('checked', true);
                } else {
                    $('#delTablesBadge').text('@lang("None specified")');
                    $('#delTablesCheck').prop('checked', false);
                }

                var modal = new bootstrap.Modal(document.getElementById('advancedDeleteModal'));
                modal.show();
            });

        })(jQuery);
    </script>
@endpush
