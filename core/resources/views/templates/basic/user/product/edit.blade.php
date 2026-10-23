@extends($activeTemplate . 'layouts.frontend')

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}" />
@endpush

@section('content')
    <section class="upload-product pt-60 pb-120">
        <div class="container">
            <div class="row">
                <div class="col-xxl-8 col-xl-10 mb-4">
                    <form method="POST" class="upload-product-item-wrapper" enctype="multipart/form-data"
                          action="{{ route('user.product.save', $product->id) }}">
                        @csrf
                        <input type="hidden" name="sub_category" value="{{ $product->sub_category_id }}">
                        <input type="hidden" value="{{ $product->category_id }}" name="category">

                        <div class="upload-product-item">
                            <h6 class="upload-product-item__title">@lang('Title & Description')</h6>
                            <div class="form-group">
                                <label class="form--label text-dark">@lang('Title')</label>
                                <input type="text" class="form--control form--control--sm" name="title"
                                       value="{{ @$product->title }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form--label text-dark">@lang('Description')</label>
                                <textarea class="form--control form--control--sm nicEdit" id="description" name="description">@php echo(@$product->description) @endphp</textarea>
                            </div>
                        </div>

                        <div class="upload-product-item">
                            @php
                                $accept = '.png, .jpg, .jpeg';
                                if (@$product->category->file_type === 'audio') {
                                    $fileTypes = '.' . implode(', .', @$product->category->preview_file_types);
                                }
                            @endphp
                            <h6 class="upload-product-item__title">@lang('Files')</h6>
                            <div class="form-group">
                                <label class="form--label">@lang('Thumbnail Image')</label>
                                <input type="file" class="form--control form--control--sm" name="thumbnail"
                                       accept="{{ $accept }}">
                                <span class="alert-message fs-14">@lang('Supported Files:') {{ $accept }}. @lang('Image will be resized into')
                                    <b>{{ getFileSize('productThumbnail') }}</b> @lang('px')</b></span>
                            </div>
                            <div class="form-group">
                                @if (@$product->category->file_type == 'audio')
                                    <label for="previewFile" class="form--label">@lang('Preview File')</label>
                                    <input type="file" class="form--control form--control--sm" name="preview_file"
                                           accept="{{ $fileTypes }}">
                                    <span class="alert-message fs-14">@lang('Supported Files:') {{ $fileTypes }}.
                                        @lang('(mp3 for max size is')
                                        <b>{{ @$product->category->file_size }}</b> @lang('MB)')
                                    </span>
                                @else
                                    <label for="previewImage" class="form--label">@lang('Preview Image')</label>
                                    <input type="file" class="form--control form--control--sm" name="preview_image"
                                           accept="{{ $accept }}">
                                    <span class="alert-message fs-14">@lang('Supported Files:') {{ $accept }}.
                                        @lang('Image size must be')
                                        <b>{{ getFileSize('productPreview') }}</b> @lang('px')</b></span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="mainFile" class="form--label">@lang('Main File')</label>
                                <input type="file" class="form--control form--control--sm" name="main_file"
                                       accept=".zip">
                                <span class="alert-message fs-14">@lang('ZIP all the files for buyers')</span>
                            </div>
                            @if (@$product->category->file_type !== 'audio')
                                <div class="form-group">
                                    <label for="screenshots" class="form--label">@lang('Screenshots')</label>
                                    <input type="file" class="form--control form--control--sm" name="screenshots"
                                           accept=".zip" />
                                    <span class="alert-message fs-14">@lang('Upload a zip file of screenshots')</span>
                                </div>
                                <div class="form-group">
                                    <label for="demo_url" class="form--label">@lang('Preview Video')</label>
                                    <input type="file" class="form--control form--control--sm" name="preview_video"
                                           accept=".mp4">
                                    <span class="alert-message fs-14">@lang('File size shouldn\'t be more than '){{ gs('preview_video_size') }} @lang('MB')</span>
                                </div>
                            @endif
                        </div>
                        @if ($product->category->file_type != 'audio')
                            <div class="upload-product-item">
                                <h6 class="upload-product-item__title">@lang('Product Attributes')</h6>
                                @if ($form)
                                    <x-viser-form identifier="id" :identifierValue="$form->id" :isFrontend="true" :editData="$product->attribute_info" />
                                @endif
                                <div class="row">
                                    <div class="col-sm-6 col-xsm-6">
                                        <div class="form-group">
                                            <label for="demo_url" class="form--label">@lang('Demo Url')</label>
                                            <input type="url" class="form--control form--control--sm" name="demo_url"
                                                   value="{{ @$product->demo_url }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="upload-product-item">
                            <h6 class="upload-product-item__title">@lang('Tag & Support')</h6>
                            <div class="form-group select2-parent position-relative">
                                <label for="Category" class="form--label">@lang('Tags')</label>
                                <select name="tags[]" class="form--control form--control--sm select2 select2-auto-tokenize"
                                        multiple="multiple" required>
                                    @foreach ($product->tags ?? [] as $tag)
                                        <option value="{{ $tag }}" selected>{{ $tag }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="itemSupport" class="form--label">@lang('Item Will be Support?')</label>
                                <select id="itemSupport" class="select form--control form--control--sm select2"
                                        data-minimum-results-for-search="-1">
                                    <option value="yes">@lang('Yes')</option>
                                    <option value="no">@lang('No')</option>
                                </select>
                            </div>
                        </div>

                        <div class="upload-product-item">
                            <h6 class="upload-product-item__title">@lang('License Prices')</h6>
                            <div class="license-price-content-wrapper">
                                <div class="license-price-content priceGroup"
                                     data-seller_fee="{{ getAmount($product->personalBuyerFee()) }}">
                                    <span class="license-price-content__type fw-semibold">@lang('Personal License')</span>
                                    <div class="license-price-content__price">
                                        <span class="license-price-content__title">@lang('Price')</span>
                                        <div class="input-group">
                                            <span class="input-group-text">{{ gs('cur_sym') }}</span>
                                            <input type="number" step="any" name="price"
                                                   value="{{ getAmount($product->price) }}"
                                                   class="form-control form--control form--control--sm">
                                        </div>
                                    </div>
                                    <span class="license-price-content__operator">+</span>
                                    <div class="license-price-content__price">
                                        <span class="license-price-content__title">@lang('Buyer Fee')</span>
                                        <span
                                              class="license-price-content__text">{{ showAmount($product->personalBuyerFee()) }}</span>
                                    </div>
                                    <span class="license-price-content__operator">=</span>
                                    <div class="license-price-content__price">
                                        <span class="license-price-content__title">@lang('Total Price')</span>
                                        <span
                                              class="license-price-content__text text--base fw-semibold totalPrice">{{ showAmount($product->personalBuyerFee()) }}</span>
                                    </div>
                                </div>
                                <div class="license-price-content priceGroup"
                                     data-seller_fee="{{ getAmount($product->commercialBuyerFee()) }}">
                                    <span class="license-price-content__type fw-semibold">@lang('Commercial License')</span>
                                    <div class="license-price-content__price">
                                        <span class="license-price-content__title">@lang('Price')</span>
                                        <div class="input-group">
                                            <span class="input-group-text">{{ gs('cur_sym') }}</span>
                                            <input type="number" step="any" name="price_cl"
                                                   value="{{ getAmount($product->price_cl) }}"
                                                   class="form-control form--control form--control--sm">
                                        </div>
                                    </div>
                                    <span class="license-price-content__operator">+</span>
                                    <div class="license-price-content__price">
                                        <span class="license-price-content__title">@lang('Buyer Fee')</span>
                                        <span
                                              class="license-price-content__text">{{ showAmount($product->commercialBuyerFee()) }}</span>
                                    </div>
                                    <span class="license-price-content__operator">=</span>
                                    <div class="license-price-content__price">
                                        <span class="license-price-content__title">@lang('Total Price')</span>
                                        <span
                                              class="license-price-content__text text--base fw-semibold totalPrice">{{ showAmount($product->commercialBuyerFee()) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if (gs('changelog'))
                            <div class="upload-product-item">
                                <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between">
                                    <div>
                                        <h6 class="upload-product-item__title mb-1">@lang('Changelog')</h6>
                                        <p class="form--label mb-0">@lang('Detail the changes for each version of your product')</p>
                                    </div>
                                    <button type="button" id="add-changelog" class="btn btn--sm btn-outline--base">
                                        <span class="d-inline-flex align-items-center gap-1 text-nowrap">
                                            <i class="fas fa-plus"></i>
                                            <span>@lang('Add New Changelog')</span>
                                        </span>
                                    </button>
                                </div>

                                <div id="changelog-container">
                                    @if (old('changelog'))
                                        @foreach (old('changelog') as $key => $change)
                                            <div class="changelog-item">
                                                <div class="form-group">
                                                    <label class="form--label text-dark">@lang('Changelog Heading')</label>
                                                    <input type="text" name="changelog[{{ $key }}][heading]"
                                                           class="form--control form--control--sm"
                                                           value="{{ $change['heading'] }}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form--label text-dark">@lang('Changelog Description')</label>
                                                    <textarea class="form--control form--control--sm nicEdit" name="changelog[{{ $key }}][description]">{{ $change['description'] }}</textarea>
                                                </div>
                                                <button type="button"
                                                        class="remove-changelog btn--sm mb-2 btn btn--danger">@lang('Remove')</button>
                                            </div>
                                        @endforeach
                                    @elseif(!empty($product->changelogs))
                                        @foreach ($product->changelogs as $key => $change)
                                            <div class="changelog-item">
                                                <div class="form-group">
                                                    <label class="form--label text-dark">@lang('Changelog Heading')</label>
                                                    <input type="text" name="changelog[{{ $key }}][heading]"
                                                           class="form--control form--control--sm"
                                                           value="{{ $change->heading }}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form--label text-dark">@lang('Changelog Description')</label>
                                                    <textarea class="form--control form--control--sm nicEdit" name="changelog[{{ $key }}][description]">{{ $change->description }}</textarea>
                                                </div>
                                                <button type="button"
                                                        class="remove-changelog btn btn--sm mb-2 btn--danger">@lang('Remove')</button>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                            </div>
                        @endif

                        <div class="upload-product-item">
                            <h6 class="upload-product-item__title">@lang('Message to the Reviewer')</h6>
                            <div class="form-group">
                                <label class="form--label">@lang('Your Message')</label>
                                <textarea name="message" class="form--control form--control--sm">{{ old('message', '') }}</textarea>
                            </div>
                            <div class="form-group">
                                @php
                                    $uploadTerm = getContent('upload_term.content', true);
                                    $uploadTerm = @$uploadTerm->data_values;
                                @endphp

                                <div class="form--check mt-2">
                                    <input class="form-check-input" type="checkbox" id="medium" required>
                                    <label class="form-check-label"
                                           for="medium">{{ __(@$uploadTerm->details) }}</label>
                                </div>
                            </div>
                            <div class="form-group mb-0 text-end">
                                <button type="submit" class="btn btn--base btn--sm w-100">@lang('Submit')</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-xxl-4 col-xl-4">
                    <form action="{{ route('user.product.upload') }}" method="GET"
                          class="upload-product-item-wrapper">
                        <div class="upload-product-item">
                            <h6 class="upload-product-item__title">@lang('Category & Subcategory')</h6>
                            <div class="form-group">
                                <label class="form--label">@lang('Category')</label>
                                <input type="text" name="" id=""
                                       value="{{ @$product->category->name }}" disabled class="form--control">
                            </div>
                            <div class="form-group">
                                <label class="form--label">@lang('Subcategory')</label>
                                <input type="text" name="" id=""
                                       value="{{ @$product->subCategory->name }}" disabled class="form--control">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('style')
    <style>
        #changelog-container:has(.changelog-item) {
            margin-top: 24px
        }

        .changelog-item:not(:last-child) {
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px dashed hsl(var(--black)/0.15);
        }
    </style>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/nicEdit.js') }}"></script>
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.select2-auto-tokenize').select2({
                dropdownParent: $('.select2-parent'),
                tags: true,
                tokenSeparators: [',']
            });

            $('.select2-predefined').each(function() {
                $(this).select2({
                    dropdownParent: $(this).closest('.select2-pre-parent'),
                    placeholder: 'Select',
                    allowClear: true,
                });
            });

            $('.category').on('change', function() {
                let subcategories = $(this).find(':selected').data('subcategories');
                let html = '';

                $.each(subcategories, function(index, subcategory) {
                    html += `<option value="${subcategory.id}">${subcategory.name}</option>`;
                });

                $('[name=subcategory]').html(html);
            });

            let curSym = `{{ gs('cur_sym') }}`;

            $('[name=price], [name=price_cl]')
                .on('input', function() {
                    let price = $(this).val() * 1;
                    let sellerFee = $(this).closest('.priceGroup').data('seller_fee') * 1;
                    let totalPrice = price + sellerFee;
                    $(this).closest('.priceGroup').find('.totalPrice').text(curSym + totalPrice.toFixed(2));
                }).trigger('input');

            function initializeNicEditors() {
                $(".nicEdit").each(function(index) {
                    $(this).attr("id", "nicEditor" + index);
                    new nicEditor({
                        fullPanel: true
                    }).panelInstance('nicEditor' + index, {
                        hasPanel: true
                    });
                });
            }

            bkLib.onDomLoaded(initializeNicEditors);

            document.addEventListener('DOMContentLoaded', function() {
                let changelogIndex = document.querySelectorAll('.changelog-item').length;

                document.getElementById('add-changelog').addEventListener('click', function() {
                    changelogIndex++;
                    const changelogContainer = document.getElementById('changelog-container');
                    const newChangelog = document.createElement('div');
                    newChangelog.classList.add('changelog-item');

                    newChangelog.innerHTML = `
                        <div class="form-group">
                            <label class="form--label text-dark">@lang('Changelog Heading')</label>
                            <input type="text" name="changelog[${changelogIndex}][heading]" class="form--control form--control--sm">
                        </div>
                        <div class="form-group">
                            <label class="form--label text-dark">@lang('Changelog Description')</label>
                            <textarea class="form--control form--control--sm nicEdit" id="nicEditor${changelogIndex}" name="changelog[${changelogIndex}][description]"></textarea>
                        </div>
                        <button type="button" class="remove-changelog btn btn--sm btn--danger">
                            <span class="d-inline-flex align-items-center gap-1 text-nowrap">
                                <i class="fa fa-times"></i>
                                <span>@lang('Remove')</span>
                            </span>
                        </button>
                    `;

                    changelogContainer.insertBefore(newChangelog, changelogContainer.firstChild);

                    new nicEditor({
                        fullPanel: true
                    }).panelInstance(`nicEditor${changelogIndex}`, {
                        hasPanel: true
                    });

                    newChangelog.querySelector('.remove-changelog').addEventListener('click',
                        function() {
                            changelogContainer.removeChild(newChangelog);
                        });
                });

                document.querySelectorAll('.remove-changelog').forEach(function(button) {
                    button.addEventListener('click', function() {
                        button.closest('.changelog-item').remove();
                    });
                });
            });

            $(document).on('mouseover', '.nicEdit-main,.nicEdit-panelContain', function() {
                $(this).focus();
            });

        })(jQuery);
    </script>
@endpush
