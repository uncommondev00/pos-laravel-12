@foreach ($variations as $variation)
    <tr>
        <td><span class="sr_number"></span></td>
        <td>
            <a data-toggle="modal" id="view_pp_logs_{{ $row_count }}" data-container=".get_purchase_price_logs"
                data-href="{{ route('purchases.getPurchasePriceLogs', $variation->id) }}"
                style="cursor: pointer;" title="View Price Logs">
                {{ $product->name }} ({{ $variation->sub_sku }})
            </a>
            @if ($product->type == 'variable')
                <br />
                (<b>{{ $variation->product_variation->name }}</b> : {{ $variation->name }})
            @endif
        </td>

        <td>
            <input type="hidden" name="purchases[{{ $row_count }}][product_id]" id="product_id_{{ $row_count }}"
                value="{{ $product->id }}">

            <input type="hidden" name="purchases[{{ $row_count }}][variation_id]"
                id="variation_id_{{ $row_count }}" value="{{ $variation->id }}" class="hidden_variation_id">

            @php
                $check_decimal = $product->unit->allow_decimal == 0 ? 'true' : 'false';
                $currency_precision = config('constants.currency_precision', 2);
                $quantity_precision = config('constants.quantity_precision', 2);
            @endphp

            <input type="text" name="purchases[{{ $row_count }}][quantity]" id="quantity_{{ $row_count }}"
                value="{{ number_format(1, $quantity_precision, $currency_details->decimal_separator, $currency_details->thousand_separator) }}"
                class="form-control input-sm purchase_quantity input_number mousetrap" required
                data-rule-abs_digit="{{ $check_decimal }}"
                data-msg-abs_digit="{{ __('lang_v1.decimal_value_not_allowed') }}">

            <input type="hidden" class="base_unit_cost" id="base_unit_cost_{{ $row_count }}"
                value="{{ $variation->default_purchase_price }}">

            <input type="hidden" class="base_unit_selling_price" id="base_unit_selling_price_{{ $row_count }}"
                value="{{ $variation->default_sell_price }}">

            <input type="hidden" name="purchases[{{ $row_count }}][product_unit_id]"
                id="product_unit_id_{{ $row_count }}" value="{{ $product->unit->id }}">

            @if (!empty($sub_units))
                <br>
                <select name="purchases[{{ $row_count }}][sub_unit_id]" id="sub_unit_id_{{ $row_count }}"
                    class="form-control input-sm sub_unit">
                    @foreach ($sub_units as $key => $value)
                        <option value="{{ $key }}" data-multiplier="{{ $value['multiplier'] }}">
                            {{ $value['name'] }}
                        </option>
                    @endforeach
                </select>
            @else
                {{ $product->unit->short_name }}
            @endif
        </td>

        <td>
            <input type="text" name="purchases[{{ $row_count }}][pp_without_discount]"
                id="pp_without_discount_{{ $row_count }}"
                value="{{ number_format($variation->default_purchase_price, $currency_precision, $currency_details->decimal_separator, $currency_details->thousand_separator) }}"
                class="form-control input-sm purchase_unit_cost_without_discount input_number" required>
        </td>

        <td>
            <input type="text" name="purchases[{{ $row_count }}][discount_percent]"
                id="discount_percent_{{ $row_count }}" value="0"
                class="form-control input-sm inline_discounts input_number" required>
        </td>

        <td>
            <input type="text" name="purchases[{{ $row_count }}][purchase_price]"
                id="purchase_price_{{ $row_count }}"
                value="{{ number_format($variation->default_purchase_price, $currency_precision, $currency_details->decimal_separator, $currency_details->thousand_separator) }}"
                class="form-control input-sm purchase_unit_cost input_number" required>
        </td>

        <td class="{{ $hide_tax }}">
            <span class="row_subtotal_before_tax display_currency">0</span>
            <input type="hidden" class="row_subtotal_before_tax_hidden"
                id="row_subtotal_before_tax_{{ $row_count }}" value="0">
        </td>

        <td class="{{ $hide_tax }}">
            <div class="input-group">
                <select name="purchases[{{ $row_count }}][purchase_line_tax_id]"
                    id="purchase_line_tax_id_{{ $row_count }}"
                    class="form-control select2 input-sm purchase_line_tax_id">
                    <option value="" data-tax_amount="0" {{ $hide_tax == 'hide' ? 'selected' : '' }}>
                        @lang('lang_v1.none')
                    </option>
                    @foreach ($taxes as $tax)
                        <option value="{{ $tax->id }}" data-tax_amount="{{ $tax->amount }}"
                            {{ $product->tax == $tax->id && $hide_tax != 'hide' ? 'selected' : '' }}>
                            {{ $tax->name }}
                        </option>
                    @endforeach
                </select>

                <input type="hidden" name="purchases[{{ $row_count }}][item_tax]"
                    id="item_tax_{{ $row_count }}" value="0" class="purchase_product_unit_tax">

                <span class="input-group-addon purchase_product_unit_tax_text">0.00</span>
            </div>
        </td>

        {{-- I'll continue with the rest in the next part due to length --}}
        {{-- Continuing from previous section --}}

        <td class="{{ $hide_tax }}">
            @php
                $dpp_inc_tax = number_format(
                    $variation->dpp_inc_tax,
                    $currency_precision,
                    $currency_details->decimal_separator,
                    $currency_details->thousand_separator,
                );
                if ($hide_tax == 'hide') {
                    $dpp_inc_tax = number_format(
                        $variation->default_purchase_price,
                        $currency_precision,
                        $currency_details->decimal_separator,
                        $currency_details->thousand_separator,
                    );
                }
            @endphp

            <input type="text" name="purchases[{{ $row_count }}][purchase_price_inc_tax]"
                id="purchase_price_inc_tax_{{ $row_count }}" value="{{ $dpp_inc_tax }}"
                class="form-control input-sm purchase_unit_cost_after_tax input_number" required>
        </td>

        <td>
            <span class="row_subtotal_after_tax display_currency">0</span>
            <input type="hidden" class="row_subtotal_after_tax_hidden" id="row_subtotal_after_tax_{{ $row_count }}"
                value="0">
        </td>

        <td class="@if (!session('business.enable_editing_product_from_purchase')) hide @endif">
            <input type="text" name="purchases[{{ $row_count }}][profit_percent]"
                id="profit_percent_{{ $row_count }}"
                value="{{ number_format($variation->profit_percent, $currency_precision, $currency_details->decimal_separator, $currency_details->thousand_separator) }}"
                class="form-control input-sm input_number profit_percent" required>
        </td>

        <td>
            @if (session('business.enable_editing_product_from_purchase'))
                <input type="text" name="purchases[{{ $row_count }}][default_sell_price]"
                    id="default_sell_price_{{ $row_count }}"
                    value="{{ number_format($variation->default_sell_price, $currency_precision, $currency_details->decimal_separator, $currency_details->thousand_separator) }}"
                    class="form-control input-sm input_number default_sell_price" required>
            @else
                {{ number_format($variation->default_sell_price, $currency_precision, $currency_details->decimal_separator, $currency_details->thousand_separator) }}
            @endif
        </td>

        @if (session('business.enable_lot_number'))
            <td>
                <input type="text" name="purchases[{{ $row_count }}][lot_number]"
                    id="lot_number_{{ $row_count }}" class="form-control input-sm">
            </td>
        @endif

        @if (session('business.enable_product_expiry'))
            <td style="text-align: left;">
                @if (!empty($product->expiry_period_type))
                    <input type="hidden" class="row_product_expiry" id="row_product_expiry_{{ $row_count }}"
                        value="{{ $product->expiry_period }}">

                    <input type="hidden" class="row_product_expiry_type"
                        id="row_product_expiry_type_{{ $row_count }}" value="{{ $product->expiry_period_type }}">

                    @php
                        $hide_mfg = session('business.expiry_type') != 'add_manufacturing';
                    @endphp

                    <b class="{{ $hide_mfg ? 'hide' : '' }}">
                        <small>@lang('product.mfg_date'):</small>
                    </b>

                    <div class="input-group {{ $hide_mfg ? 'hide' : '' }}">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span>
                        <input type="text" name="purchases[{{ $row_count }}][mfg_date]"
                            id="mfg_date_{{ $row_count }}"
                            class="form-control input-sm expiry_datepicker mfg_date" readonly>
                    </div>

                    <b><small>@lang('product.exp_date'):</small></b>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span>
                        <input type="text" name="purchases[{{ $row_count }}][exp_date]"
                            id="exp_date_{{ $row_count }}"
                            class="form-control input-sm expiry_datepicker exp_date" readonly>
                    </div>
                @else
                    <div class="text-center">
                        @lang('product.not_applicable')
                    </div>
                @endif
            </td>
        @endif

        <td>
            <i class="fa fa-times remove_purchase_entry_row text-danger" data-row="{{ $row_count }}"
                id="remove_purchase_entry_{{ $row_count }}" title="Remove" style="cursor:pointer;"></i>
        </td>
    </tr>
    @php
        $row_count++;
    @endphp
@endforeach

<input type="hidden" id="row_count" value="{{ $row_count }}">
