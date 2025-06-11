@extends('layouts.app')
@section('title', __('purchase.edit_purchase'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang('purchase.edit_purchase') <i class="fa fa-keyboard-o hover-q text-muted" aria-hidden="true" data-container="body"
            data-toggle="popover" data-placement="bottom" data-content="@include('purchase.partials.keyboard_shortcuts_details')" data-html="true"
            data-trigger="hover" data-original-title="" title=""></i></h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Page level currency setting -->
    <input type="hidden" id="p_code" value="{{ $currency_details->code }}">
    <input type="hidden" id="p_symbol" value="{{ $currency_details->symbol }}">
    <input type="hidden" id="p_thousand" value="{{ $currency_details->thousand_separator }}">
    <input type="hidden" id="p_decimal" value="{{ $currency_details->decimal_separator }}">

    @include('layouts.partials.error')

    <form action="{{ route('purchases.update', $purchase->id) }}" method="POST" id="add_purchase_form"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @php
        $currency_precision = config('constants.currency_precision', 2);
        @endphp

        <input type="hidden" id="purchase_id" value="{{ $purchase->id }}">

        @component('components.widget', ['class' => 'box-primary'])
        <div class="row">
            <div class="@if (!empty($default_purchase_status)) col-sm-4 @else col-sm-3 @endif">
                <div class="form-group">
                    <label for="supplier_id">{{ __('purchase.supplier') }}:*</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-user"></i>
                        </span>
                        <select name="contact_id" id="supplier_id" class="form-control" required>
                            <option value="">{{ __('messages.please_select') }}</option>
                            <option value="{{ $purchase->contact_id }}" selected>
                                {{ $purchase->contact->name }}
                            </option>
                        </select>
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default bg-white btn-flat add_new_supplier"
                                data-name="">
                                <i class="fa fa-plus-circle text-primary fa-lg"></i>
                            </button>
                        </span>
                    </div>
                </div>
            </div>

            <div class="@if (!empty($default_purchase_status)) col-sm-4 @else col-sm-3 @endif">
                <div class="form-group">
                    <label for="ref_no">{{ __('purchase.ref_no') }}:*</label>
                    <input type="text" name="ref_no" id="ref_no" value="{{ $purchase->ref_no }}"
                        class="form-control" required>
                </div>
            </div>

            <div class="@if (!empty($default_purchase_status)) col-sm-4 @else col-sm-3 @endif">
                <div class="form-group">
                    <label for="transaction_date">{{ __('purchase.purchase_date') }}:*</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span>
                        <input type="text" name="transaction_date" id="transaction_date"
                            value="@format_date($purchase->transaction_date)" class="form-control" readonly
                            required>
                    </div>
                </div>
            </div>

            <div class="col-sm-3 @if (!empty($default_purchase_status)) hide @endif">
                <div class="form-group">
                    <label for="status">
                        {{ __('purchase.purchase_status') }}:*
                        @show_tooltip(__('tooltip.order_status'))
                    </label>
                    <select name="status" id="status" class="form-control select2" required>
                        <option value="">{{ __('messages.please_select') }}</option>
                        @foreach ($orderStatuses as $key => $value)
                        <option value="{{ $key }}" {{ $purchase->status == $key ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="location_id">
                        {{ __('purchase.business_location') }}:*
                        @show_tooltip(__('tooltip.purchase_location'))
                    </label>
                    <select name="location_id" id="location_id" class="form-control select2" disabled>
                        <option value="">{{ __('messages.please_select') }}</option>
                        @foreach ($business_locations as $key => $value)
                        <option value="{{ $key }}"
                            {{ $purchase->location_id == $key ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-sm-3 @if (!$currency_details->purchase_in_diff_currency) hide @endif">
                <div class="form-group">
                    <label for="exchange_rate">
                        {{ __('purchase.p_exchange_rate') }}:*
                        @show_tooltip(__('tooltip.currency_exchange_factor'))
                    </label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-info"></i>
                        </span>
                        <input type="number" name="exchange_rate" id="exchange_rate"
                            value="{{ $purchase->exchange_rate }}" class="form-control" required step="0.001">
                    </div>
                    <span class="help-block text-danger">
                        @lang('purchase.diff_purchase_currency_help', ['currency' => $currency_details->name])
                    </span>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="document">{{ __('purchase.attach_document') }}:</label>
                    <input type="file" name="document" id="upload_document">
                    <p class="help-block">
                        @lang('purchase.max_file_size', ['size' => config('constants.document_size_limit') / 1000000])
                    </p>
                </div>
            </div>
        </div>
        @endcomponent

        {{-- I'll continue with the rest of the form in the next part --}}
        @component('components.widget', ['class' => 'box-primary'])
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-search"></i>
                        </span>
                        <input type="text" name="search_product" id="search_product"
                            class="form-control mousetrap"
                            placeholder="{{ __('lang_v1.search_product_placeholder') }}" autofocus>
                    </div>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <button tabindex="-1" type="button" class="btn btn-link btn-modal"
                        data-href="{{ route('products.quickAdd') }}"
                        data-container=".quick_add_product_modal">
                        <i class="fa fa-plus"></i>
                        {{ __('product.add_new_product') }}
                    </button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                @include('purchase.partials.edit_purchase_entry_row')

                <hr />
                <div class="pull-right col-md-5">
                    <table class="pull-right col-md-12">
                        <tr class="hide">
                            <th class="col-md-7 text-right">
                                {{ __('purchase.total_before_tax') }}:
                            </th>
                            <td class="col-md-5 text-left">
                                <span id="total_st_before_tax" class="display_currency"></span>
                                <input type="hidden" id="st_before_tax_input" value="0">
                            </td>
                        </tr>
                        <tr>
                            <th class="col-md-7 text-right">
                                {{ __('purchase.net_total_amount') }}:
                            </th>
                            <td class="col-md-5 text-left">
                                <span id="total_subtotal" class="display_currency">
                                    {{ $purchase->total_before_tax / $purchase->exchange_rate }}
                                </span>
                                <input type="hidden" id="total_subtotal_input" name="total_before_tax"
                                    value="{{ $purchase->total_before_tax / $purchase->exchange_rate }}">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        @endcomponent

        @component('components.widget', ['class' => 'box-primary'])
        <div class="row">
            <div class="col-sm-12">
                <table class="table">
                    <tr>
                        <td class="col-md-3">
                            <div class="form-group">
                                <label for="discount_type">{{ __('purchase.discount_type') }}:</label>
                                <select name="discount_type" id="discount_type" class="form-control select2">
                                    <option value="">{{ __('lang_v1.none') }}</option>
                                    <option value="fixed"
                                        {{ $purchase->discount_type == 'fixed' ? 'selected' : '' }}>
                                        {{ __('lang_v1.fixed') }}
                                    </option>
                                    <option value="percentage"
                                        {{ $purchase->discount_type == 'percentage' ? 'selected' : '' }}>
                                        {{ __('lang_v1.percentage') }}
                                    </option>
                                </select>
                            </div>
                        </td>
                        <td class="col-md-3">
                            <div class="form-group">
                                <label for="discount_amount">{{ __('purchase.discount_amount') }}:</label>
                                <input type="text" name="discount_amount" id="discount_amount"
                                    class="form-control input_number"
                                    value="{{ $purchase->discount_type == 'fixed'
                                                ? number_format(
                                                    $purchase->discount_amount / $purchase->exchange_rate,
                                                    $currency_precision,
                                                    $currency_details->decimal_separator,
                                                    $currency_details->thousand_separator,
                                                )
                                                : number_format(
                                                    $purchase->discount_amount,
                                                    $currency_precision,
                                                    $currency_details->decimal_separator,
                                                    $currency_details->thousand_separator,
                                                ) }}">
                            </div>
                        </td>
                        <td class="col-md-3">&nbsp;</td>
                        <td class="col-md-3">
                            <b>{{ __('purchase.discount') }}:</b>(-)
                            <span id="discount_calculated_amount" class="display_currency">0</span>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <div class="form-group">
                                <label for="tax_id">{{ __('purchase.purchase_tax') }}:</label>
                                <select name="tax_id" id="tax_id" class="form-control select2">
                                    <option value="" data-tax_amount="0" selected>
                                        {{ __('lang_v1.none') }}
                                    </option>
                                    @foreach ($taxes as $tax)
                                    <option value="{{ $tax->id }}" data-tax_amount="{{ $tax->amount }}"
                                        {{ $purchase->tax_id == $tax->id ? 'selected' : '' }}>
                                        {{ $tax->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="tax_amount" id="tax_amount"
                                    value="{{ $purchase->tax_amount }}">
                            </div>
                        </td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>
                            <b>{{ __('purchase.purchase_tax') }}:</b>(+)
                            <span id="tax_calculated_amount" class="display_currency">0</span>
                        </td>
                    </tr>

                    {{-- I'll continue with the shipping and total sections in the next part --}}
                    <tr>
                        <td>
                            <div class="form-group">
                                <label for="shipping_details">{{ __('purchase.shipping_details') }}:</label>
                                <input type="text" name="shipping_details" id="shipping_details"
                                    class="form-control" value="{{ $purchase->shipping_details }}">
                            </div>
                        </td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>
                            <div class="form-group">
                                <label for="shipping_charges">
                                    (+) {{ __('purchase.additional_shipping_charges') }}:
                                </label>
                                <input type="text" name="shipping_charges" id="shipping_charges"
                                    class="form-control input_number"
                                    value="{{ number_format(
                                                $purchase->shipping_charges / $purchase->exchange_rate,
                                                $currency_precision,
                                                $currency_details->decimal_separator,
                                                $currency_details->thousand_separator,
                                            ) }}">
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>
                            <input type="hidden" name="final_total" id="grand_total_hidden"
                                value="{{ $purchase->final_total }}">

                            <b>{{ __('purchase.purchase_total') }}: </b>
                            <span id="grand_total" class="display_currency" data-currency_symbol='true'>
                                {{ $purchase->final_total }}
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="4">
                            <div class="form-group">
                                <label for="additional_notes">{{ __('purchase.additional_notes') }}</label>
                                <textarea name="additional_notes" id="additional_notes" class="form-control" rows="3">{{ $purchase->additional_notes }}</textarea>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        @endcomponent

        <div class="row">
            <div class="col-sm-12">
                <button type="button" id="submit_purchase_form" class="btn btn-primary pull-right btn-flat">
                    {{ __('messages.update') }}
                </button>
            </div>
        </div>
    </form>

</section>
<!-- /.content -->
<!-- quick product modal -->
<div class="modal fade quick_add_product_modal" tabindex="-1" role="dialog" aria-labelledby="modalTitle"></div>
<div class="modal fade contact_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    @include('contact.create', ['quick_add' => true])
</div>

@endsection

@section('javascript')
<script src="{{ asset('js/purchase.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('js/product.js?v=' . $asset_v) }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        update_table_total();
        update_grand_total();
    });
</script>
@include('purchase.partials.keyboard_shortcuts')
@endsection
