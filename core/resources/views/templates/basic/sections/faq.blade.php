@php
    $faqContent  = @getContent('faq.content', true)->data_values;
    $faqElements = getContent('faq.element');
@endphp

<section class="ak23-faq-clean-section" id="faq" style="scroll-margin-top: 80px;">
    <div class="container">
        <div class="ak23-faq-header-clean mb-4">
            <div class="ak23-badge-gold mb-2">
                <i class="las la-question-circle"></i> @lang('FREQUENTLY ASKED QUESTIONS')
            </div>
            <h2 class="ak23-clean-title" style="font-size: 32px; margin-bottom: 6px;">
                {{ __(@$faqContent->heading ?? 'Commonly Asked Questions') }}
            </h2>
            <p class="ak23-clean-subtitle" style="font-size: 14.5px; margin-bottom: 0;">
                {{ __(@$faqContent->subheading ?? 'Find answers to commonly asked questions right here for quick solutions') }}
            </p>
        </div>

        <div class="row g-4 align-items-center">
            <div class="col-lg-6">
                <div class="accordion ak23-clean-accordion" id="accordionFaqExample">
                    @foreach ($faqElements as $faqElement)
                    <div class="accordion-item ak23-accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button ak23-accordion-btn {{ $loop->first ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseFaq{{$loop->index}}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="collapseFaq{{$loop->index}}">
                                {{ __(@$faqElement->data_values->question) }}
                            </button>
                        </h2>
                        <div id="collapseFaq{{$loop->index}}" class="accordion-collapse collapse {{ $loop->first ? 'show' : ''}}"
                            data-bs-parent="#accordionFaqExample">
                            <div class="accordion-body ak23-accordion-body">
                                {{ __(@$faqElement->data_values->answer) }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="ak23-faq-thumb-box">
                    <img src="{{ asset('assets/images/frontend/faq/faq_theme.svg') }}" alt="AK23 Studio Support FAQ" class="ak23-faq-theme-svg">
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Clean White FAQ Section Styles */
.ak23-faq-clean-section {
    background: #FFFFFF !important;
    border-top: 1px solid #E2E8F0;
    padding: 70px 0 100px;
    color: #0F172A;
}
.ak23-faq-header-clean {
    text-align: left;
}
.ak23-clean-accordion {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.ak23-accordion-item {
    background: #FFFFFF !important;
    border: 1px solid #E2E8F0 !important;
    border-radius: 12px !important;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.03);
    transition: all 0.2s ease;
}
.ak23-accordion-item:hover {
    border-color: #FBAF43 !important;
    box-shadow: 0 4px 14px rgba(251, 175, 67, 0.12);
}
.ak23-accordion-btn {
    background: #FFFFFF !important;
    color: #0F172A !important;
    font-weight: 700 !important;
    font-size: 14.5px !important;
    padding: 18px 20px !important;
    border: none !important;
    box-shadow: none !important;
    text-align: left;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.ak23-accordion-btn:not(.collapsed) {
    background: #FFFBEB !important;
    color: #92400E !important;
    border-bottom: 1px solid #FEF3C7 !important;
}
.ak23-accordion-btn::after {
    font-family: "Line Awesome Free" !important;
    font-weight: 900 !important;
    content: "\f107" !important;
    background-image: none !important;
    color: #D97706 !important;
    font-size: 16px;
    transition: transform 0.2s ease;
}
.ak23-accordion-btn:not(.collapsed)::after {
    content: "\f106" !important;
    color: #B45309 !important;
    transform: rotate(180deg);
}
.ak23-accordion-body {
    background: #FFFFFF !important;
    color: #475569 !important;
    font-size: 13.5px !important;
    line-height: 1.65 !important;
    padding: 16px 20px 20px !important;
}
.ak23-faq-thumb-box {
    display: flex;
    justify-content: center;
    align-items: center;
}
.ak23-faq-theme-svg {
    max-width: 480px;
    width: 100%;
    height: auto;
    filter: drop-shadow(0 15px 30px rgba(251, 175, 67, 0.15));
}
</style>
