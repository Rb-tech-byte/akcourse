@extends(activeTemplate() . 'layouts.frontend')

@section('content')

<!-- =========================================================================
     TOP ACTION BAR (HIDDEN IN PRINT / PDF GENERATION)
     ========================================================================= -->
<div class="ak23-cert-action-bar py-3 d-print-none" style="background: #0E0F17; border-bottom: 1px solid rgba(255,255,255,0.08);">
    <div class="container d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('user.home') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1-5 fs-12 text-decoration-none">
                <i class="las la-arrow-left me-1"></i> @lang('My Courses')
            </a>
            <span class="text-muted fs-12 d-none d-md-inline">&middot; Official Certificate of Course Purchase &amp; Software License</span>
        </div>

        <div class="d-flex align-items-center gap-2">
            <!-- Copy License Key Button -->
            <button type="button" class="btn btn-sm btn-outline-warning rounded-pill px-3 py-1-5 fs-12 fw-semibold" id="ak23CopyKeyBtn" data-key="{{ $licenseKey }}">
                <i class="las la-key me-1"></i> <span>Copy License Key</span>
            </button>
            <!-- Native Print / Save as PDF Button -->
            <button type="button" class="btn btn-sm btn--base rounded-pill px-4 py-1-5 fs-12 fw-bold" onclick="window.print();">
                <i class="las la-print me-1"></i> @lang('Print / Save as PDF')
            </button>
        </div>
    </div>
</div>

<!-- =========================================================================
     CERTIFICATE DISPLAY FRAME
     ========================================================================= -->
<div class="ak23-cert-page-wrapper py-4 py-md-5">
    <div class="container">
        
        <div class="ak23-certificate-container mx-auto position-relative" id="ak23PrintableCertificate">
            
            <!-- Outer Ornamental Guilloche Frame -->
            <div class="ak23-cert-frame">
                
                <!-- Inner Border -->
                <div class="ak23-cert-inner-frame">
                    
                    <!-- Decorative Corner Filigrees -->
                    <div class="ak23-corner-bracket top-left"></div>
                    <div class="ak23-corner-bracket top-right"></div>
                    <div class="ak23-corner-bracket bottom-left"></div>
                    <div class="ak23-corner-bracket bottom-right"></div>

                    <!-- Watermark Security Emblem (Background) -->
                    <div class="ak23-cert-watermark" aria-hidden="true">
                        <svg width="340" height="340" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="50" cy="50" r="48" stroke="#D4AF37" stroke-width="0.8" stroke-dasharray="2 1"/>
                            <circle cx="50" cy="50" r="38" stroke="#D4AF37" stroke-width="0.5"/>
                            <polygon points="50,15 61,38 85,38 66,54 73,78 50,63 27,78 34,54 15,38 39,38" fill="none" stroke="#D4AF37" stroke-width="0.6"/>
                            <text x="50" y="54" fill="#D4AF37" font-size="8" font-weight="800" text-anchor="middle" letter-spacing="2">M23</text>
                        </svg>
                    </div>

                    <!-- Certificate Header -->
                    <div class="text-center mb-3">
                        <div class="ak23-cert-issuer-badge">
                            <i class="las la-certificate text-warning me-1"></i> MAARIFA23 ACADEMY &middot; OFFICIAL PURCHASE REGISTRY
                        </div>
                        <h1 class="ak23-cert-main-title mt-2 mb-1">
                            CERTIFICATE OF COURSE PURCHASE
                        </h1>
                        <div class="ak23-cert-sub-title">
                            &amp; ONE-TIME PERPETUAL SOFTWARE LICENSE
                        </div>
                    </div>

                    <!-- Presentation Statement -->
                    <div class="text-center my-3">
                        <p class="ak23-cert-certifies-text mb-1">This official certificate verifies that</p>
                        <h2 class="ak23-cert-student-name">{{ $studentName }}</h2>
                        <p class="ak23-cert-certifies-text mb-1">has completed the authorized purchase and holds full perpetual ownership and studio rights for</p>
                        <h3 class="ak23-cert-course-name">{{ $courseTitle }}</h3>
                    </div>

                    <!-- License Grant Statement -->
                    <div class="ak23-cert-license-grant text-center mx-auto my-3">
                        <span class="grant-label">PERPETUAL SOFTWARE GRANT:</span>
                        <div class="software-name-pill">
                            <i class="las la-key text-warning me-1"></i>
                            <strong>{{ $softwareName }}</strong> &mdash; {{ $softwareEdition }}
                        </div>
                        <div class="grant-note">
                            Single Vendor Perpetual Studio License &middot; Lifetime Rights &middot; No Extensions or Renewals Required
                        </div>
                    </div>

                    <!-- Verification Metadata Grid -->
                    <div class="ak23-cert-meta-grid my-3">
                        <div class="meta-item">
                            <span class="meta-label">LICENSE KEY</span>
                            <strong class="meta-value text-gold" id="ak23LicenseText">{{ $licenseKey }}</strong>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">PURCHASE CERTIFICATE NO.</span>
                            <strong class="meta-value">{{ $certificateNumber }}</strong>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">DATE OF PURCHASE</span>
                            <strong class="meta-value">{{ $issueDate ?? ($purchaseDate ?? date('F d, Y')) }}</strong>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">PURCHASE STATUS</span>
                            <strong class="meta-value text-success"><i class="las la-check-circle me-1"></i>CONFIRMED &middot; LIFETIME</strong>
                        </div>
                    </div>

                    <!-- Signatures & Official Gold Seal Strip -->
                    <div class="ak23-cert-footer-strip d-flex align-items-end justify-content-between pt-3 mt-2">
                        <!-- Left: Instructor & Director Signature -->
                        <div class="ak23-cert-signature-col text-center">
                            <div class="ak23-signature-script">Alex M. Kaseja</div>
                            <div class="ak23-signature-line"></div>
                            <div class="ak23-signature-title">ALEX M. KASEJA</div>
                            <div class="ak23-signature-role">Director &middot; Maarifa23 Academy</div>
                        </div>

                        <!-- Center: Official Gold Foil Seal -->
                        <div class="ak23-cert-seal-col text-center">
                            <div class="ak23-official-gold-seal">
                                <svg width="105" height="105" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="50" cy="50" r="46" fill="#D4AF37" fill-opacity="0.12" stroke="#D4AF37" stroke-width="1.5"/>
                                    <circle cx="50" cy="50" r="40" stroke="#D4AF37" stroke-width="0.8" stroke-dasharray="3 1.5"/>
                                    <circle cx="50" cy="50" r="32" fill="#D4AF37" fill-opacity="0.15" stroke="#D4AF37" stroke-width="1"/>
                                    <path d="M50 24 L53 33 L62 33 L55 39 L57 48 L50 43 L43 48 L45 39 L38 33 L47 33 Z" fill="#D4AF37"/>
                                    <text x="50" y="58" fill="#FFFFFF" font-size="6" font-weight="800" text-anchor="middle" letter-spacing="1">PURCHASE</text>
                                    <text x="50" y="66" fill="#D4AF37" font-size="4.5" font-weight="700" text-anchor="middle" letter-spacing="1">VERIFIED &middot; M23</text>
                                </svg>
                            </div>
                        </div>

                        <!-- Right: Registry Verification QR & Link -->
                        <div class="ak23-cert-verification-col text-center">
                            <div class="ak23-qr-box mx-auto mb-1">
                                <svg width="58" height="58" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="50" height="50" fill="#13141F" rx="3"/>
                                    <rect x="5" y="5" width="14" height="14" fill="#D4AF37"/>
                                    <rect x="7" y="7" width="10" height="10" fill="#13141F"/>
                                    <rect x="9" y="9" width="6" height="6" fill="#D4AF37"/>
                                    <rect x="31" y="5" width="14" height="14" fill="#D4AF37"/>
                                    <rect x="33" y="7" width="10" height="10" fill="#13141F"/>
                                    <rect x="35" y="9" width="6" height="6" fill="#D4AF37"/>
                                    <rect x="5" y="31" width="14" height="14" fill="#D4AF37"/>
                                    <rect x="7" y="33" width="10" height="10" fill="#13141F"/>
                                    <rect x="9" y="35" width="6" height="6" fill="#D4AF37"/>
                                    <rect x="23" y="10" width="4" height="10" fill="#D4AF37"/>
                                    <rect x="23" y="24" width="8" height="4" fill="#D4AF37"/>
                                    <rect x="23" y="32" width="6" height="8" fill="#D4AF37"/>
                                    <rect x="33" y="24" width="10" height="16" fill="#D4AF37"/>
                                </svg>
                            </div>
                            <div class="ak23-signature-line"></div>
                            <div class="ak23-signature-title">ONLINE VERIFIED</div>
                            <a href="{{ route('license.verify', ['code' => $certificateNumber]) }}" class="ak23-verify-link fs-10 text-decoration-none" target="_blank">
                                verify.maarifa23.com
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@push('style')
<style>
/* Page & Canvas */
.ak23-cert-page-wrapper {
    background: #08090E;
    min-height: 85vh;
}

/* Certificate Outer Frame */
.ak23-certificate-container {
    max-width: 960px;
    background: #0E1019;
    padding: 16px;
    border-radius: 10px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.8), 0 0 0 1px rgba(212, 175, 55, 0.35);
}

.ak23-cert-frame {
    border: 3px double #D4AF37;
    padding: 12px;
    position: relative;
    background: radial-gradient(circle at 50% 50%, #161827 0%, #0E1019 100%);
}

.ak23-cert-inner-frame {
    border: 1px solid rgba(212, 175, 55, 0.4);
    padding: 36px 36px 28px;
    position: relative;
    overflow: hidden;
}

/* Decorative Corner Brackets */
.ak23-corner-bracket {
    position: absolute;
    width: 20px;
    height: 20px;
    border-color: #D4AF37;
    border-style: solid;
    pointer-events: none;
}

.ak23-corner-bracket.top-left {
    top: 6px;
    left: 6px;
    border-width: 2px 0 0 2px;
}

.ak23-corner-bracket.top-right {
    top: 6px;
    right: 6px;
    border-width: 2px 2px 0 0;
}

.ak23-corner-bracket.bottom-left {
    bottom: 6px;
    left: 6px;
    border-width: 0 0 2px 2px;
}

.ak23-corner-bracket.bottom-right {
    bottom: 6px;
    right: 6px;
    border-width: 0 2px 2px 0;
}

/* Watermark Security Emblem */
.ak23-cert-watermark {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    opacity: 0.05;
    pointer-events: none;
    z-index: 0;
}

/* Typography & Content */
.ak23-cert-issuer-badge {
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.16em;
    color: #D4AF37;
    text-transform: uppercase;
}

.ak23-cert-main-title {
    font-family: 'Cinzel', 'Playfair Display', 'Times New Roman', serif;
    font-size: 28px;
    font-weight: 800;
    letter-spacing: 0.08em;
    color: #FFFFFF;
    text-transform: uppercase;
}

.ak23-cert-sub-title {
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.14em;
    color: #D4AF37;
    text-transform: uppercase;
}

.ak23-cert-certifies-text {
    font-size: 13px;
    color: #9A9BAA;
    font-style: italic;
}

.ak23-cert-student-name {
    font-family: 'Cinzel', 'Playfair Display', 'Georgia', serif;
    font-size: 32px;
    font-weight: 800;
    color: #FFFFFF;
    letter-spacing: 0.04em;
    border-bottom: 1px solid rgba(212, 175, 55, 0.4);
    display: inline-block;
    padding: 0 28px 4px;
    margin-bottom: 8px;
}

.ak23-cert-course-name {
    font-size: 20px;
    font-weight: 700;
    color: #FBAF43;
    letter-spacing: 0.02em;
    margin-bottom: 4px;
}

/* Software Grant Box */
.ak23-cert-license-grant {
    background: rgba(212, 175, 55, 0.08);
    border: 1px solid rgba(212, 175, 55, 0.3);
    border-radius: 8px;
    padding: 10px 18px;
    max-width: 680px;
}

.ak23-cert-license-grant .grant-label {
    font-size: 9.5px;
    font-weight: 800;
    letter-spacing: 0.1em;
    color: #D4AF37;
    display: block;
    margin-bottom: 2px;
}

.ak23-cert-license-grant .software-name-pill {
    font-size: 14px;
    color: #FFFFFF;
}

.ak23-cert-license-grant .grant-note {
    font-size: 11px;
    color: #8C8E9E;
    margin-top: 2px;
}

/* Metadata Grid */
.ak23-cert-meta-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    background: rgba(0, 0, 0, 0.35);
    border: 1px solid rgba(255, 255, 255, 0.07);
    border-radius: 8px;
    padding: 12px 16px;
}

@media (max-width: 768px) {
    .ak23-cert-meta-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }
}

.ak23-cert-meta-grid .meta-label {
    font-size: 9.5px;
    font-weight: 700;
    letter-spacing: 0.08em;
    color: #7A7C8E;
    display: block;
    margin-bottom: 2px;
}

.ak23-cert-meta-grid .meta-value {
    font-size: 12px;
    color: #E0E0E8;
    word-break: break-all;
}

.text-gold {
    color: #FBAF43 !important;
}

/* Signatures & Seal Strip */
.ak23-cert-footer-strip {
    border-top: 1px solid rgba(212, 175, 55, 0.3);
}

.ak23-signature-script {
    font-family: 'Brush Script MT', 'Dancing Script', cursive, serif;
    font-size: 24px;
    color: #D4AF37;
    line-height: 1;
    margin-bottom: 4px;
}

.ak23-signature-line {
    width: 140px;
    height: 1px;
    background: rgba(212, 175, 55, 0.4);
    margin: 4px auto;
}

.ak23-signature-title {
    font-size: 10px;
    font-weight: 800;
    color: #FFFFFF;
    letter-spacing: 0.1em;
}

.ak23-signature-role {
    font-size: 10px;
    color: #8C8E9E;
}

.ak23-verify-link {
    color: #D4AF37 !important;
}

/* =========================================================================
   PRINT STYLES: PERFECT A4 LANDSCAPE PDF EXPORT
   ========================================================================= */
@page {
    size: landscape A4;
    margin: 0;
}

@media print {
    body, html {
        background: #FFFFFF !important;
        color: #000000 !important;
        margin: 0 !important;
        padding: 0 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    header, footer, nav, .d-print-none, .ak23-cert-action-bar {
        display: none !important;
    }

    .ak23-cert-page-wrapper {
        background: transparent !important;
        padding: 0 !important;
        min-height: auto !important;
    }

    .ak23-certificate-container {
        box-shadow: none !important;
        border-radius: 0 !important;
        max-width: 100% !important;
        width: 100vw !important;
        height: 100vh !important;
        padding: 24px !important;
        background: #0E1019 !important;
        page-break-inside: avoid;
    }
}
</style>
@endpush

@push('script')
<script>
    $(document).ready(function() {
        "use strict";

        $('#ak23CopyKeyBtn').on('click', function() {
            const key = $(this).data('key');
            if (navigator.clipboard) {
                navigator.clipboard.writeText(key).then(() => {
                    notify('success', 'License Key copied to clipboard: ' + key);
                });
            } else {
                const tempInput = $('<input>');
                $('body').append(tempInput);
                tempInput.val(key).select();
                document.execCommand('copy');
                tempInput.remove();
                notify('success', 'License Key copied: ' + key);
            }
        });
    });
</script>
@endpush
