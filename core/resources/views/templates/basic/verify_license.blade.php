@extends(activeTemplate() . 'layouts.frontend')

@section('content')

<div class="ak23-verify-page py-5" style="background: #0A0B10; min-height: 75vh;">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">

                <div class="text-center mb-4">
                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-1 rounded-pill fs-11 text-uppercase fw-bold letter-spacing-1 mb-2">
                        <i class="las la-shield-alt me-1"></i> Official Purchase Registry Verification
                    </span>
                    <h1 class="text-white fw-bold mb-2 fs-28">Verify Course Purchase &amp; License</h1>
                    <p class="text-muted fs-14 mb-0">
                        Authenticate genuine Maarifa23 Academy course purchase certificates, perpetual software license keys, and verified buyer ownership.
                    </p>
                </div>

                <!-- Verification Form Card -->
                <div class="p-4 rounded-3 border" style="background: #12131D; border-color: rgba(255,255,255,0.08) !important;">
                    <form action="{{ route('license.verify') }}" method="GET">
                        <div class="mb-3">
                            <label for="verificationCode" class="form-label text-white fs-13 fw-semibold">
                                License Key or Purchase Certificate Number
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-secondary text-muted">
                                    <i class="las la-key"></i>
                                </span>
                                <input type="text" 
                                       name="code" 
                                       id="verificationCode" 
                                       class="form-control form-control-lg bg-dark text-white border-secondary fs-14" 
                                       placeholder="e.g. M23-LIC-8F2A-94B1-C87E or M23-CERT-2026-..." 
                                       value="{{ $lookupCode ?? '' }}" 
                                       required 
                                       autocomplete="off">
                                <button type="submit" class="btn btn--base px-4 fw-bold fs-14">
                                    <i class="las la-check-circle me-1"></i> Verify
                                </button>
                            </div>
                            <small class="text-muted fs-11 mt-1 d-block">
                                <i class="las la-info-circle me-1"></i>Enter the full code found on your purchase certificate, digital receipt, or student dashboard.
                            </small>
                        </div>
                    </form>

                    @if(!empty($searched))
                        <div class="alert alert-danger d-flex align-items-center gap-2 mt-4 mb-0" style="background: rgba(239, 68, 68, 0.1); border-color: rgba(239, 68, 68, 0.3); color: #f87171;">
                            <i class="las la-exclamation-triangle fs-20"></i>
                            <div>
                                <strong>Code Not Found:</strong> The license or certificate code <code>{{ $lookupCode }}</code> was not found in our active registry. Please check the characters and try again.
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Trust Guarantee Strip -->
                <div class="mt-4 p-3 rounded-3 d-flex align-items-center justify-content-around text-center border" style="background: rgba(255,255,255,0.02); border-color: rgba(255,255,255,0.05) !important;">
                    <div>
                        <i class="las la-certificate text-warning fs-22 mb-1 d-block"></i>
                        <span class="text-white fs-12 fw-bold d-block">Lifetime License</span>
                        <span class="text-muted fs-11">No renewals needed</span>
                    </div>
                    <div class="border-start border-secondary h-50 mx-2"></div>
                    <div>
                        <i class="las la-lock text-success fs-22 mb-1 d-block"></i>
                        <span class="text-white fs-12 fw-bold d-block">Cryptographic ID</span>
                        <span class="text-muted fs-11">Anti-tamper verification</span>
                    </div>
                    <div class="border-start border-secondary h-50 mx-2"></div>
                    <div>
                        <i class="las la-shield-alt text-primary fs-22 mb-1 d-block"></i>
                        <span class="text-white fs-12 fw-bold d-block">Studio Authorized</span>
                        <span class="text-muted fs-11">Official Maarifa23 accreditation</span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
