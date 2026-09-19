{{--
    PSGC Address Autocomplete Partial (Single-Field)
    =================================================
    Replaces the old Country / State / City 3-dropdown pattern with a single
    address textarea that has PSGC autocomplete.

    Usage:
        @include('partials._psgc_address', [
            'model'       => $customer,        // optional: existing record for edit forms
            'addressName' => 'address',         // name of the address field (default: 'address')
                                                 // branches use 'branch_address'
            'required'    => true,              // whether the address field is required (default: true)
        ])

    The partial outputs:
      - A single textarea (the address field) with PSGC autocomplete dropdown.
        The user can type a street address ("123 Rizal St") and then search for
        a barangay/city/province via the dropdown. Selecting from the dropdown
        appends the PSGC text to whatever the user typed.
      - Hidden inputs for psgc_code and full_address (the PSGC reference).

    Data model:
      - {addressName} column  → full combined text, e.g.
        "123 Rizal St, Aplaya, City Of Digos, Davao del Sur, Region XI"
      - full_address column   → just the PSGC part, e.g.
        "Aplaya, City Of Digos, Davao del Sur, Region XI"
      - psgc_code column      → 10-digit PSA PSGC barangay code

    On edit forms:
      - If the model has full_address, the textarea is pre-filled with the
        model's address (which already contains the full text).
      - Legacy rows (country_id/state_id/city_id only, no full_address) show
        whatever is in the address column — the user can re-select via
        autocomplete to upgrade to PSGC.
--}}
@php
    $addressName = $addressName ?? 'address';
    $required = $required ?? true;
    $model = $model ?? null;
    $existingFullAddress = $model ? ($model->full_address ?? '') : '';
    $existingPsgcCode = $model ? ($model->psgc_code ?? '') : '';
    $existingStreet = $model ? ($model->{$addressName} ?? '') : old($addressName);
    // For legacy rows without full_address, try to build a display value from
    // the old country/state/city fields so the user sees something meaningful.
    if ($existingFullAddress === '' && $model) {
        $legacyParts = [];
        if (! empty($model->{$addressName})) { $legacyParts[] = trim($model->{$addressName}); }
        if (! empty($model->city_id)) { $legacyParts[] = getCityName($model->city_id); }
        if (! empty($model->state_id)) { $legacyParts[] = getStateName($model->state_id); }
        if (! empty($model->country_id)) { $legacyParts[] = getCountryName($model->country_id); }
        $existingStreet = implode(', ', array_filter($legacyParts));
    }
@endphp

<div class="row row-mb-0 psgc-address-container">
    {{-- Hidden inputs for psgc_code + full_address (the PSGC reference) --}}
    <input type="hidden" name="psgc_code" class="psgc-code-input" value="{{ old('psgc_code', $existingPsgcCode) }}" />
    <input type="hidden" name="full_address" class="psgc-full-address-input" value="{{ old('full_address', $existingFullAddress) }}" />

    {{-- Single address field with PSGC autocomplete --}}
    <div class="row col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 form-group my-form-group has-feedback {{ $errors->has($addressName) ? ' has-error' : '' }}">
        <label class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2" for="{{ $addressName }}">
            {{ trans('message.Address') }} @if($required)<label class="color-danger">*</label>@endif
        </label>
        <div class="col-md-10 col-lg-10 col-xl-10 col-xxl-10 col-sm-10 col-xs-10 psgc-autocomplete-wrapper" style="position: relative;">
            <textarea class="form-control addressTextarea psgc-search-input"
                      id="{{ $addressName }}"
                      name="{{ $addressName }}"
                      maxlength="200"
                      rows="2"
                      autocomplete="off"
                      placeholder="{{ trans('message.House #, Street, Barangay, City — type to search') }}">{{ old($addressName, $existingStreet) }}</textarea>
            <div class="psgc-dropdown" style="display:none; position:absolute; z-index:9999; width:100%; max-height:250px; overflow-y:auto; background:#fff; border:1px solid #ccc; border-radius:4px; box-shadow:0 2px 8px rgba(0,0,0,0.15);"></div>
            @if ($errors->has($addressName))
            <span class="help-block">
                <strong>{{ $errors->first($addressName) }}</strong>
            </span>
            @endif
        </div>
    </div>
</div>

@once
<script nonce="{{ $cspNonce ?? '' }}">
(function() {
    // PSGC Address Autocomplete — debounced search against /psgc/search.
    // Self-initializing: finds all .psgc-search-input elements on the page.
    //
    // Single-field design:
    //   The textarea holds the full address (street + PSGC). When the user
    //   selects from the dropdown, the PSGC text is appended to whatever they
    //   already typed. The hidden psgc_code + full_address inputs are updated
    //   to the selected PSGC reference. If the user later selects a different
    //   PSGC entry, the old PSGC text in the textarea is replaced (preserving
    //   any street address the user typed).
    var PSGC_SEARCH_URL = '{{ route("psgc.search") }}';
    var MIN_QUERY = {{ (int) config('services.psgc.min_query_length', 2) }};

    function initPsgcAutocomplete() {
        $('.psgc-search-input').each(function() {
            var $input = $(this);
            if ($input.data('psgc-init')) return;
            $input.data('psgc-init', true);

            var $wrapper = $input.closest('.psgc-autocomplete-wrapper');
            var $container = $input.closest('.psgc-address-container');
            var $dropdown = $wrapper.find('.psgc-dropdown');
            var $codeInput = $container.find('.psgc-code-input');
            var $fullInput = $container.find('.psgc-full-address-input');
            var debounceTimer = null;

            $input.on('input', function() {
                // Clear the hidden PSGC reference when the user edits the text,
                // since we can no longer guarantee it matches. The reference
                // will be re-set when the user selects from the dropdown.
                $codeInput.val('');
                $fullInput.val('');

                var q = $(this).val().trim();
                // Extract the last fragment after the last comma for searching,
                // so the user can type "123 Rizal St, digos" and search for "digos".
                var lastFragment = q.split(',').pop().trim();

                if (lastFragment.length < MIN_QUERY) {
                    $dropdown.hide().empty();
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function() {
                    $.ajax({
                        type: 'GET',
                        url: PSGC_SEARCH_URL,
                        data: { q: lastFragment },
                        success: function(results) {
                            $dropdown.empty();
                            if (!results || results.length === 0) {
                                $dropdown.append('<div style="padding:8px 12px;color:#999;">No matches found</div>');
                                $dropdown.show();
                                return;
                            }
                            results.forEach(function(item) {
                                var $item = $('<div>', {
                                    'class': 'psgc-dropdown-item',
                                    'css': { padding: '8px 12px', cursor: 'pointer', borderBottom: '1px solid #eee' },
                                    'text': item.full_address
                                });
                                $item.data('psgc-code', item.psgc_code);
                                $item.data('full-address', item.full_address);
                                $item.on('mouseenter', function() { $(this).css('background', '#f0f6ff'); });
                                $item.on('mouseleave', function() { $(this).css('background', '#fff'); });
                                $item.on('mousedown', function(e) {
                                    e.preventDefault();

                                    // Save the old full_address so we can replace
                                    // (not duplicate) it in the textarea.
                                    var oldFullAddress = $fullInput.val();

                                    // Update hidden PSGC reference.
                                    $codeInput.val(item.psgc_code);
                                    $fullInput.val(item.full_address);

                                    // Update the textarea:
                                    //  1. If empty → set to PSGC text.
                                    //  2. Contains old PSGC → replace old with new
                                    //     (preserves street address the user typed).
                                    //  3. Already contains the new PSGC text → skip.
                                    //  4. Has text with a comma → text before the last
                                    //     comma is the street address; the text after is
                                    //     the partial search fragment — replace it with
                                    //     the full PSGC text.
                                    //  5. Has text with no comma → treat all text as
                                    //     street address and append the PSGC text.
                                    var currentText = ($input.val() || '').trim();

                                    if (currentText === '') {
                                        $input.val(item.full_address);
                                    } else if (oldFullAddress && currentText.indexOf(oldFullAddress) !== -1) {
                                        // Replace old PSGC with new, keep street.
                                        var streetOnly = currentText.replace(oldFullAddress, '').replace(/,\s*$/, '').replace(/^\s*,\s*/, '').trim();
                                        $input.val(streetOnly ? streetOnly + ', ' + item.full_address : item.full_address);
                                    } else if (currentText.indexOf(item.full_address) !== -1) {
                                        // Already contains this PSGC text — skip.
                                    } else {
                                        // No old PSGC to replace. Figure out the street
                                        // part: if there's a comma, the text after the
                                        // last comma is the partial search fragment (e.g.
                                        // "digos" in "123 Rizal St, digos") — discard it.
                                        // If there's no comma, the entire text is the
                                        // street address — keep it and append PSGC.
                                        var lastComma = currentText.lastIndexOf(',');
                                        var streetPart;
                                        if (lastComma >= 0) {
                                            streetPart = currentText.substring(0, lastComma).replace(/,\s*$/, '').trim();
                                        } else {
                                            streetPart = currentText;
                                        }
                                        $input.val(streetPart ? streetPart + ', ' + item.full_address : item.full_address);
                                    }

                                    $dropdown.hide().empty();
                                });
                                $dropdown.append($item);
                            });
                            $dropdown.show();
                        },
                        error: function() {
                            $dropdown.empty().append('<div style="padding:8px 12px;color:#c00;">Search failed. Please try again.</div>').show();
                        }
                    });
                }, 300);
            });

            // Hide dropdown when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest($wrapper).length) {
                    $dropdown.hide();
                }
            });

            // Hide dropdown on Escape
            $input.on('keydown', function(e) {
                if (e.key === 'Escape') {
                    $dropdown.hide();
                }
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPsgcAutocomplete);
    } else {
        initPsgcAutocomplete();
    }
})();
</script>
@endonce
