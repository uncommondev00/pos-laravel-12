@extends('layouts.app')
@section('title', __('purchase.add_purchase'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('purchase.add_purchase') <i class="fa fa-keyboard-o hover-q text-muted" aria-hidden="true" data-container="body"
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

        <form action="{{ route('purchases.store') }}" method="POST" id="add_purchase_form"
            enctype="multipart/form-data">
            @csrf

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
                            <label for="ref_no">{{ __('purchase.ref_no') }}:</label>
                            <input type="text" name="ref_no" id="ref_no" class="form-control">
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
                                    value="{{ @format_date('now') }}" class="form-control" readonly required>
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
                                    <option value="{{ $key }}"
                                        {{ $default_purchase_status == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    @php
                        $default_location = null;
                        if (count($business_locations) == 1) {
                            $default_location = array_key_first($business_locations->toArray());
                        }
                    @endphp

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="location_id">
                                {{ __('purchase.business_location') }}:*
                                @show_tooltip(__('tooltip.purchase_location'))
                            </label>
                            <select name="location_id" id="location_id" class="form-control select2" required>
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach ($business_locations as $key => $value)
                                    <option value="{{ $key }}" {{ $default_location == $key ? 'selected' : '' }}>
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
                                <input type="number" name="exchange_rate" id="exchange_rate" class="form-control" required
                                    step="0.001" value="{{ $currency_details->p_exchange_rate }}">
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

            <!-- Rest of the form remains the same as it's already using native HTML -->
            <!-- Only convert remaining Form:: helpers if any -->

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
                                data-container=".get_purchase_price_logs">
                                <i class="fa fa-plus"></i> {{ __('product.add_new_product') }}
                            </button>
                        </div>
                    </div>
                </div>

                @php
                    $hide_tax = session()->get('business.enable_inline_tax') == 0 ? 'hide' : '';
                @endphp

                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-condensed table-bordered table-th-green text-center table-striped"
                                id="purchase_entry_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('product.product_name') }}</th>
                                        <th>{{ __('purchase.purchase_quantity') }}</th>
                                        <th>{{ __('lang_v1.unit_cost_before_discount') }}</th>
                                        <th>{{ __('lang_v1.discount_percent') }}</th>
                                        <th>{{ __('purchase.unit_cost_before_tax') }}</th>
                                        <th class="{{ $hide_tax }}">{{ __('purchase.subtotal_before_tax') }}</th>
                                        <th class="{{ $hide_tax }}">{{ __('purchase.product_tax') }}</th>
                                        <th class="{{ $hide_tax }}">{{ __('purchase.net_cost') }}</th>
                                        <th>{{ __('purchase.line_total') }}</th>
                                        <th class="@if (!session('business.enable_editing_product_from_purchase')) hide @endif">
                                            {{ __('lang_v1.profit_margin') }}
                                        </th>
                                        <th>{{ __('purchase.unit_selling_price') }}</th>
                                        @if (session('business.enable_lot_number'))
                                            <th>{{ __('lang_v1.lot_number') }}</th>
                                        @endif
                                        @if (session('business.enable_product_expiry'))
                                            <th>
                                                {{ __('product.mfg_date') }} / {{ __('product.exp_date') }}
                                            </th>
                                        @endif
                                        <th><i class="fa fa-trash" aria-hidden="true"></i></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <hr />
                        <div class="pull-right col-md-5">
                            <table class="pull-right col-md-12">
                                <tr class="hide">
                                    <th class="col-md-7 text-right">{{ __('purchase.total_before_tax') }}:</th>
                                    <td class="col-md-5 text-left">
                                        <span id="total_st_before_tax" class="display_currency"></span>
                                        <input type="hidden" id="st_before_tax_input" value="0">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="col-md-7 text-right">{{ __('purchase.net_total_amount') }}:</th>
                                    <td class="col-md-5 text-left">
                                        <span id="total_subtotal" class="display_currency"></span>
                                        <input type="hidden" id="total_subtotal_input" name="total_before_tax"
                                            value="0">
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <input type="hidden" id="row_count" value="0">
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
                                            <option value="fixed">{{ __('lang_v1.fixed') }}</option>
                                            <option value="percentage">{{ __('lang_v1.percentage') }}</option>
                                        </select>
                                    </div>
                                </td>
                                <td class="col-md-3">
                                    <div class="form-group">
                                        <label for="discount_amount">{{ __('purchase.discount_amount') }}:</label>
                                        <input type="text" name="discount_amount" id="discount_amount"
                                            class="form-control input_number" value="0" required>
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
                                        <label for="tax_id">
                                            {{ __('purchase.purchase_tax') }}:
                                            <span style="color: red;">(Not Required!)</span>
                                        </label>
                                        <select name="tax_id" id="tax_id" class="form-control select2">
                                            <option value="" data-tax_amount="0" data-tax_type="fixed" selected>
                                                {{ __('lang_v1.none') }}
                                            </option>
                                            @foreach ($taxes as $tax)
                                                <option value="{{ $tax->id }}" data-tax_amount="{{ $tax->amount }}"
                                                    data-tax_type="{{ $tax->calculation_type }}">
                                                    {{ $tax->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="tax_amount" id="tax_amount" value="0">
                                    </div>
                                </td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>
                                    <b>{{ __('purchase.purchase_tax') }}:</b>(+)
                                    <span id="tax_calculated_amount" class="display_currency">0</span>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label for="shipping_details">{{ __('purchase.shipping_details') }}:</label>
                                        <input type="text" name="shipping_details" id="shipping_details"
                                            class="form-control">
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
                                            class="form-control input_number" value="0" required>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>
                                    <input type="hidden" name="final_total" id="grand_total_hidden" value="0">
                                    <b>{{ __('purchase.purchase_total') }}: </b>
                                    <span id="grand_total" class="display_currency" data-currency_symbol='true'>0</span>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="4">
                                    <div class="form-group">
                                        <label for="additional_notes">{{ __('purchase.additional_notes') }}</label>
                                        <textarea name="additional_notes" id="additional_notes" rows="3" class="form-control"></textarea>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            @endcomponent

            @component('components.widget', ['class' => 'box-primary', 'title' => __('purchase.add_payment')])
                <div class="box-body payment_row">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="amount_0">{{ __('sale.amount') }}:*</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fas fa-money-bill-alt"></i>
                                            </span>
                                            <input type="text" name="payment[0][amount]" id="amount_0"
                                                class="form-control payment-amount input_number" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="method_0">{{ __('purchase.payment_method') }}:*</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fas fa-money-bill-alt"></i>
                                            </span>
                                            <select name="payment[0][method]" id="method_0"
                                                class="form-control payment_types_dropdown" required>
                                                <option value="">{{ __('messages.please_select') }}</option>
                                                <option value="cash">{{ __('lang_v1.cash') }}</option>
                                                <option value="card">{{ __('lang_v1.card') }}</option>
                                                <option value="cheque">{{ __('lang_v1.cheque') }}</option>
                                                <option value="bank_transfer">{{ __('lang_v1.bank_transfer') }}</option>
                                                <option value="other">{{ __('lang_v1.other') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="paid_on_0">{{ __('lang_v1.paid_on') }}:*</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <input type="text" name="payment[0][paid_on]" id="paid_on_0"
                                                class="form-control paid_on" readonly required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="clearfix"></div>

                            {{-- Card Payment Details --}}
                            <div class="col-md-12 payment_details_div hide" data-type="card">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="card_number_0">{{ __('lang_v1.card_no') }}</label>
                                            <input type="text" name="payment[0][card_number]" id="card_number_0"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="card_holder_name_0">{{ __('lang_v1.card_holder_name') }}</label>
                                            <input type="text" name="payment[0][card_holder_name]" id="card_holder_name_0"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="card_transaction_number_0">
                                                {{ __('lang_v1.card_transaction_no') }}
                                            </label>
                                            <input type="text" name="payment[0][card_transaction_number]"
                                                id="card_transaction_number_0" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="card_type_0">{{ __('lang_v1.card_type') }}</label>
                                            <select name="payment[0][card_type]" id="card_type_0" class="form-control">
                                                <option value="credit">{{ __('lang_v1.credit') }}</option>
                                                <option value="debit">{{ __('lang_v1.debit') }}</option>
                                                <option value="visa">{{ __('lang_v1.visa') }}</option>
                                                <option value="master">{{ __('lang_v1.master') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="card_month_0">{{ __('lang_v1.month') }}</label>
                                            <input type="text" name="payment[0][card_month]" id="card_month_0"
                                                class="form-control" placeholder="MM">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="card_year_0">{{ __('lang_v1.year') }}</label>
                                            <input type="text" name="payment[0][card_year]" id="card_year_0"
                                                class="form-control" placeholder="YYYY">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Cheque Payment Details --}}
                            <div class="col-md-12 payment_details_div hide" data-type="cheque">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="cheque_number_0">{{ __('lang_v1.cheque_no') }}</label>
                                            <input type="text" name="payment[0][cheque_number]" id="cheque_number_0"
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Bank Transfer Details --}}
                            <div class="col-md-12 payment_details_div hide" data-type="bank_transfer">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="bank_account_number_0">
                                                {{ __('lang_v1.bank_account_no') }}
                                            </label>
                                            <input type="text" name="payment[0][bank_account_number]"
                                                id="bank_account_number_0" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Payment Note --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="payment_note_0">{{ __('purchase.payment_note') }}</label>
                                    <textarea name="payment[0][note]" id="payment_note_0" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="pull-right">
                                <strong>{{ __('purchase.payment_due') }}:</strong>
                                <span id="payment_due">0.00</span>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-12">
                            <button type="button" id="submit_purchase_form" class="btn btn-primary pull-right btn-flat">
                                {{ __('messages.save') }}
                            </button>
                        </div>
                    </div>
                </div>
            @endcomponent
        </form>
    </section>
    <!-- quick product modal -->
    <div class="modal fade quick_add_product_modal" tabindex="-1" role="dialog" aria-labelledby="modalTitle"></div>
    <div class="modal fade get_purchase_price_logs" tabindex="-1" role="dialog" aria-labelledby="modalTitle"></div>
    <div class="modal fade contact_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        @include('contact.create', ['quick_add' => true])
    </div>
    <!-- /.content -->

@endsection

@section('javascript')
    <script src="{{ asset('js/purchase.js?v=' . $asset_v) }}"></script>
    <script src="{{ asset('js/product.js?v=' . $asset_v) }}"></script>
    @include('purchase.partials.keyboard_shortcuts')
@endsection
