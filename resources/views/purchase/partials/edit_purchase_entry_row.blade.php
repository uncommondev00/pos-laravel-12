@php
    $hide_tax = '';
    if (session()->get('business.enable_inline_tax') == 0) {
        $hide_tax = 'hide';
    }
    $currency_precision = config('constants.currency_precision', 2);
    $quantity_precision = config('constants.quantity_precision', 2);
@endphp
<div class="table-responsive">
    <table class="table table-condensed table-bordered table-th-green text-center table-striped"
        id="purchase_entry_table">
        <thead>
            <tr>
                <th>#</th>
                <th>@lang('product.product_name')</th>
                <th>@lang('purchase.purchase_quantity')</th>
                <th>@lang('lang_v1.unit_cost_before_discount')</th>
                <th>@lang('lang_v1.discount_percent')</th>
                <th>@lang('purchase.unit_cost_before_tax')</th>
                <th class="{{ $hide_tax }}">@lang('purchase.subtotal_before_tax')</th>
                <th class="{{ $hide_tax }}">@lang('purchase.product_tax')</th>
                <th class="{{ $hide_tax }}">@lang('purchase.net_cost')</th>
                <th>@lang('purchase.line_total')</th>
                <th class="@if (!session('business.enable_editing_product_from_purchase')) hide @endif">
                    @lang('lang_v1.profit_margin')
                </th>
                <th>@lang('purchase.unit_selling_price')</th>
                @if (session('business.enable_lot_number'))
                    <th>
                        @lang('lang_v1.lot_number')
                    </th>
                @endif
                @if (session('business.enable_product_expiry'))
                    <th>@lang('product.mfg_date') / @lang('product.exp_date')</th>
                @endif
                <th>
                    <i class="fa fa-trash" aria-hidden="true"></i>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php $row_count = 0; ?>
            @foreach ($purchase->purchase_lines as $purchase_line)
                <tr>
                    <td><span class="sr_number"></span></td>
                    <td>
                        {{ $purchase_line->product->name }} ({{ $purchase_line->variations->sub_sku }})
                        @if ($purchase_line->product->type == 'variable')
                            <br />
                            <b>{{ $purchase_line->variations->product_variation->name }}</b> :
                            {{ $purchase_line->variations->name }}
                        @endif
                    </td>

                    <td>
                        <input type="hidden" name="purchases[{{ $loop->index }}][product_id]"
                            id="product_id_{{ $loop->index }}" value="{{ $purchase_line->product_id }}">

                        <input type="hidden" name="purchases[{{ $loop->index }}][variation_id]"
                            id="variation_id_{{ $loop->index }}" value="{{ $purchase_line->variation_id }}">

                        <input type="hidden" name="purchases[{{ $loop->index }}][purchase_line_id]"
                            id="purchase_line_id_{{ $loop->index }}" value="{{ $purchase_line->id }}">

                        @php
                            $check_decimal = $purchase_line->product->unit->allow_decimal == 0 ? 'true' : 'false';
                            $formatted_quantity = number_format(
                                $purchase_line->quantity,
                                $quantity_precision,
                                $currency_details->decimal_separator,
                                $currency_details->thousand_separator,
                            );
                        @endphp

                        <input type="text" name="purchases[{{ $loop->index }}][quantity]"
                            id="quantity_{{ $loop->index }}" value="{{ $formatted_quantity }}"
                            class="form-control input-sm purchase_quantity input_number mousetrap" required
                            data-rule-abs_digit="{{ $check_decimal }}"
                            data-msg-abs_digit="{{ __('lang_v1.decimal_value_not_allowed') }}">

                        <input type="hidden" class="base_unit_cost"
                            value="{{ $purchase_line->variations->default_purchase_price }}">

                        @if (count($purchase_line->product->unit->sub_units) > 0)
                            <br>
                            <select name="purchases[{{ $loop->index }}][sub_unit_id]"
                                id="sub_unit_id_{{ $loop->index }}" class="form-control input-sm sub_unit">
                                <option value="{{ $purchase_line->product->unit->id }}" data-multiplier="1">
                                    {{ $purchase_line->product->unit->short_name }}
                                </option>
                                @foreach ($purchase_line->product->unit->sub_units as $sub_unit)
                                    <option value="{{ $sub_unit->id }}"
                                        data-multiplier="{{ $sub_unit->base_unit_multiplier }}"
                                        {{ $sub_unit->id == $purchase_line->sub_unit_id ? 'selected' : '' }}>
                                        {{ $sub_unit->short_name }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            {{ $purchase_line->product->unit->short_name }}
                        @endif

                        <input type="hidden" name="purchases[{{ $loop->index }}][product_unit_id]"
                            id="product_unit_id_{{ $loop->index }}" value="{{ $purchase_line->product->unit->id }}">

                        <input type="hidden" class="base_unit_selling_price"
                            value="{{ $purchase_line->variations->default_sell_price }}">
                    </td>

                    <td>
                        <input type="text" name="purchases[{{ $loop->index }}][pp_without_discount]"
                            id="pp_without_discount_{{ $loop->index }}"
                            value="{{ number_format($purchase_line->pp_without_discount / $purchase->exchange_rate, $currency_precision, $currency_details->decimal_separator, $currency_details->thousand_separator) }}"
                            class="form-control input-sm purchase_unit_cost_without_discount input_number" required>
                    </td>

                    <td>
                        <input type="text" name="purchases[{{ $loop->index }}][discount_percent]"
                            id="discount_percent_{{ $loop->index }}"
                            value="{{ number_format($purchase_line->discount_percent, $currency_precision, $currency_details->decimal_separator, $currency_details->thousand_separator) }}"
                            class="form-control input-sm inline_discounts input_number" required>
                        <b>%</b>
                    </td>

                    <td>
                        <input type="text" name="purchases[{{ $loop->index }}][purchase_price]"
                            id="purchase_price_{{ $loop->index }}"
                            value="{{ number_format($purchase_line->purchase_price / $purchase->exchange_rate, $currency_precision, $currency_details->decimal_separator, $currency_details->thousand_separator) }}"
                            class="form-control input-sm purchase_unit_cost input_number" required>
                    </td>

                    <td class="{{ $hide_tax }}">
                        <span class="row_subtotal_before_tax">
                            {{ number_format(($purchase_line->quantity * $purchase_line->purchase_price) / $purchase->exchange_rate, $currency_precision, $currency_details->decimal_separator, $currency_details->thousand_separator) }}
                        </span>
                        <input type="hidden" class="row_subtotal_before_tax_hidden"
                            id="subtotal_before_tax_{{ $loop->index }}"
                            value="{{ number_format(($purchase_line->quantity * $purchase_line->purchase_price) / $purchase->exchange_rate, $currency_precision, $currency_details->decimal_separator, $currency_details->thousand_separator) }}">
                    </td>

                    <td class="{{ $hide_tax }}">
                        <div class="input-group">
                            <select name="purchases[{{ $loop->index }}][purchase_line_tax_id]"
                                id="purchase_line_tax_id_{{ $loop->index }}"
                                class="form-control input-sm purchase_line_tax_id">
                                <option value="" data-tax_amount="0"
                                    {{ empty($purchase_line->tax_id) ? 'selected' : '' }}>
                                    @lang('lang_v1.none')
                                </option>
                                @foreach ($taxes as $tax)
                                    <option value="{{ $tax->id }}" data-tax_amount="{{ $tax->amount }}"
                                        {{ $purchase_line->tax_id == $tax->id ? 'selected' : '' }}>
                                        {{ $tax->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="input-group-addon purchase_product_unit_tax_text">
                                {{ number_format($purchase_line->item_tax / $purchase->exchange_rate, $currency_precision, $currency_details->decimal_separator, $currency_details->thousand_separator) }}
                            </span>
                            <input type="hidden" name="purchases[{{ $loop->index }}][item_tax]"
                                id="item_tax_{{ $loop->index }}"
                                value="{{ number_format($purchase_line->item_tax / $purchase->exchange_rate, $currency_precision, $currency_details->decimal_separator, $currency_details->thousand_separator) }}"
                                class="purchase_product_unit_tax">
                        </div>
                    </td>

                    {{-- I'll continue with the rest of the fields in the next part due to length --}}
                    {{-- Continuing from previous section --}}

                    <td class="{{ $hide_tax }}">
                        <input type="text" name="purchases[{{ $loop->index }}][purchase_price_inc_tax]"
                            id="purchase_price_inc_tax_{{ $loop->index }}"
                            value="{{ number_format($purchase_line->purchase_price_inc_tax / $purchase->exchange_rate, $currency_precision, $currency_details->decimal_separator, $currency_details->thousand_separator) }}"
                            class="form-control input-sm purchase_unit_cost_after_tax input_number" required>
                    </td>

                    <td>
                        <span class="row_subtotal_after_tax">
                            {{ number_format(($purchase_line->purchase_price_inc_tax * $purchase_line->quantity) / $purchase->exchange_rate, $currency_precision, $currency_details->decimal_separator, $currency_details->thousand_separator) }}
                        </span>
                        <input type="hidden" class="row_subtotal_after_tax_hidden"
                            id="subtotal_after_tax_{{ $loop->index }}"
                            value="{{ number_format(($purchase_line->purchase_price_inc_tax * $purchase_line->quantity) / $purchase->exchange_rate, $currency_precision, $currency_details->decimal_separator, $currency_details->thousand_separator) }}">
                    </td>

                    <td class="@if (!session('business.enable_editing_product_from_purchase')) hide @endif">
                        @php
                            $pp = $purchase_line->purchase_price;
                            $sp = $purchase_line->variations->default_sell_price;
                            if (!empty($purchase_line->sub_unit->base_unit_multiplier)) {
                                $sp = $sp * $purchase_line->sub_unit->base_unit_multiplier;
                            }
                            $profit_percent = $pp == 0 ? 100 : (($sp - $pp) * 100) / $pp;
                        @endphp

                        <input type="text" name="purchases[{{ $loop->index }}][profit_percent]"
                            id="profit_percent_{{ $loop->index }}"
                            value="{{ number_format($profit_percent, $currency_precision, $currency_details->decimal_separator, $currency_details->thousand_separator) }}"
                            class="form-control input-sm input_number profit_percent" required>
                    </td>

                    <td>
                        @if (session('business.enable_editing_product_from_purchase'))
                            <input type="text" name="purchases[{{ $loop->index }}][default_sell_price]"
                                id="default_sell_price_{{ $loop->index }}"
                                value="{{ number_format($sp, $currency_precision, $currency_details->decimal_separator, $currency_details->thousand_separator) }}"
                                class="form-control input-sm input_number default_sell_price" required>
                        @else
                            {{ number_format($sp, $currency_precision, $currency_details->decimal_separator, $currency_details->thousand_separator) }}
                        @endif
                    </td>

                    @if (session('business.enable_lot_number'))
                        <td>
                            <input type="text" name="purchases[{{ $loop->index }}][lot_number]"
                                id="lot_number_{{ $loop->index }}" value="{{ $purchase_line->lot_number }}"
                                class="form-control input-sm">
                        </td>
                    @endif

                    @if (session('business.enable_product_expiry'))
                        <td style="text-align: left;">
                            @if (!empty($purchase_line->product->expiry_period_type))
                                <input type="hidden" class="row_product_expiry"
                                    id="row_product_expiry_{{ $loop->index }}"
                                    value="{{ $purchase_line->product->expiry_period }}">
                                <input type="hidden" class="row_product_expiry_type"
                                    id="row_product_expiry_type_{{ $loop->index }}"
                                    value="{{ $purchase_line->product->expiry_period_type }}">

                                @php
                                    $hide_mfg = session('business.expiry_type') != 'add_manufacturing';
                                    $mfg_date = !empty($purchase_line->mfg_date) ? $purchase_line->mfg_date : null;
                                    $exp_date = !empty($purchase_line->exp_date) ? $purchase_line->exp_date : null;
                                @endphp

                                <b class="{{ $hide_mfg ? 'hide' : '' }}">
                                    <small>@lang('product.mfg_date'):</small>
                                </b>

                                <div class="input-group {{ $hide_mfg ? 'hide' : '' }}">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input type="text" name="purchases[{{ $loop->index }}][mfg_date]"
                                        id="mfg_date_{{ $loop->index }}"
                                        value="{{ !empty($mfg_date) ? @format_date($mfg_date) : null }}"
                                        class="form-control input-sm expiry_datepicker mfg_date" readonly>
                                </div>

                                <b><small>@lang('product.exp_date'):</small></b>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input type="text" name="purchases[{{ $loop->index }}][exp_date]"
                                        id="exp_date_{{ $loop->index }}"
                                        value="{{ !empty($exp_date) ? @format_date($exp_date) : null }}"
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
                        <i class="fa fa-times remove_purchase_entry_row text-danger" data-index="{{ $loop->index }}"
                            title="Remove" style="cursor:pointer;"></i>
                    </td>
                </tr>
                @php
                    $row_count = $loop->index + 1;
                @endphp
            @endforeach
        </tbody>
    </table>
</div>
<input type="hidden" id="row_count" value="{{ $row_count }}">
