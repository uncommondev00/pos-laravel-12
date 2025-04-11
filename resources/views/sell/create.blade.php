@extends('layouts.app')

@section('title', __('sale.add_sale'))

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('sale.add_sale')</h1>
    </section>
    <!-- Main content -->
    <section class="content no-print">
        @if (is_null($default_location))
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-map-marker"></i>
                            </span>
                            <select name="select_location_id" id="select_location_id" class="form-control input-sm" required
                                autofocus>
                                <option value="">@lang('lang_v1.select_location')</option>
                                @foreach ($business_locations as $id => $location)
                                    <option value="{{ $id }}" data-{{ $id }}="{{ $location }}">
                                        {{ $location }}</option>
                                @endforeach
                            </select>
                            <span class="input-group-addon">
                                @show_tooltip(__('tooltip.sale_location'))
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <input type="hidden" id="item_addition_method" value="{{ $business_details->item_addition_method }}">

        <form action="{{ route('pos.store') }}" method="POST" id="add_sell_form">
            @csrf
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    @component('components.widget', ['class' => 'box-primary'])
                        <input type="hidden" id="location_id" name="location_id" value="{{ $default_location }}"
                            data-receipt_printer_type="{{ isset($bl_attributes[$default_location]['data-receipt_printer_type']) ? $bl_attributes[$default_location]['data-receipt_printer_type'] : 'browser' }}">

                        @if (!empty($price_groups))
                            @if (count($price_groups) > 1)
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-money"></i>
                                            </span>
                                            @php
                                                reset($price_groups);
                                            @endphp
                                            <input type="hidden" id="hidden_price_group" name="hidden_price_group"
                                                value="{{ key($price_groups) }}">
                                            <select name="price_group" id="price_group" class="form-control select2">
                                                @foreach ($price_groups as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                            <span class="input-group-addon">
                                                @show_tooltip(__('lang_v1.price_group_help_text'))
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                @php
                                    reset($price_groups);
                                @endphp
                                <input type="hidden" id="price_group" name="price_group" value="{{ key($price_groups) }}">
                            @endif
                        @endif

                        @if (in_array('subscription', $enabled_modules))
                            <div class="col-md-4 pull-right col-sm-6">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="is_recurring" id="is_recurring" class="input-icheck"
                                            value="1">
                                        @lang('lang_v1.subscribe')?
                                    </label>
                                    <button type="button" data-toggle="modal" data-target="#recurringInvoiceModal"
                                        class="btn btn-link">
                                        <i class="fa fa-external-link"></i>
                                    </button>
                                    @show_tooltip(__('lang_v1.recurring_invoice_help'))
                                </div>
                            </div>
                        @endif

                        <div class="clearfix"></div>
                        <div class="@if (!empty($commission_agent)) col-sm-3 @else col-sm-4 @endif">
                            <div class="form-group">
                                <label for="customer_id">@lang('contact.customer') :*</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </span>
                                    <input type="hidden" id="default_customer_id" value="{{ $walk_in_customer['id'] }}">
                                    <input type="hidden" id="default_customer_name" value="{{ $walk_in_customer['name'] }}">
                                    <select name="contact_id" id="customer_id" class="form-control mousetrap" required
                                        placeholder="Enter Customer name / phone">
                                    </select>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default bg-white btn-flat add_new_customer"
                                            data-name="">
                                            <i class="fa fa-plus-circle text-primary fa-lg"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="multi-input">
                                    <label for="pay_term_number">
                                        @lang('contact.pay_term') :
                                        @show_tooltip(__('tooltip.pay_term'))
                                    </label>
                                    <br />
                                    <input type="number" name="pay_term_number" id="pay_term_number"
                                        class="form-control width-40 pull-left" placeholder="@lang('contact.pay_term')"
                                        value="{{ $walk_in_customer['pay_term_number'] }}">

                                    <select name="pay_term_type" id="pay_term_type" class="form-control width-60 pull-left">
                                        <option value="">@lang('messages.please_select')</option>
                                        <option value="months"
                                            {{ $walk_in_customer['pay_term_type'] == 'months' ? 'selected' : '' }}>
                                            @lang('lang_v1.months')
                                        </option>
                                        <option value="days"
                                            {{ $walk_in_customer['pay_term_type'] == 'days' ? 'selected' : '' }}>
                                            @lang('lang_v1.days')
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        @if (!empty($commission_agent))
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="commission_agent">@lang('lang_v1.commission_agent') :</label>
                                    <select name="commission_agent" id="commission_agent" class="form-control select2">
                                        @foreach ($commission_agent as $key => $agent)
                                            <option value="{{ $key }}">{{ $agent }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        <div class="@if (!empty($commission_agent)) col-sm-3 @else col-sm-4 @endif">
                            <div class="form-group">
                                <label for="transaction_date">@lang('sale.sale_date') :*</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input type="text" name="transaction_date" id="transaction_date" class="form-control"
                                        value="{{ $default_datetime }}" readonly required>
                                </div>
                            </div>
                        </div>

                        <div class="@if (!empty($commission_agent)) col-sm-3 @else col-sm-4 @endif">
                            <div class="form-group">
                                <label for="status">@lang('sale.status') :*</label>
                                <select name="status" id="status" class="form-control select2" required>
                                    <option value="">@lang('messages.please_select')</option>
                                    <option value="final">@lang('sale.final')</option>
                                    <option value="draft">@lang('sale.draft')</option>
                                    <option value="quotation">@lang('lang_v1.quotation')</option>
                                </select>
                            </div>
                        </div>
                    @endcomponent

                    @component('components.widget', ['class' => 'box-primary'])
                        <div class="col-sm-10 col-sm-offset-1">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-barcode"></i>
                                    </span>
                                    <input type="text" name="search_product" id="search_product"
                                        class="form-control mousetrap" placeholder="@lang('lang_v1.search_product_placeholder')"
                                        {{ is_null($default_location) ? 'disabled' : '' }}
                                        {{ is_null($default_location) ? '' : 'autofocus' }}>
                                </div>
                            </div>
                        </div>

                        <div class="row col-sm-12 pos_product_div" style="min-height: 0">
                            <!-- Hidden inputs -->
                            <input type="hidden" name="sell_price_tax" id="sell_price_tax"
                                value="{{ $business_details->sell_price_tax }}">

                            <input type="hidden" id="product_row_count" value="0">

                            @php
                                $hide_tax = '';
                                if (session()->get('business.enable_inline_tax') == 0) {
                                    $hide_tax = 'hide';
                                }
                            @endphp

                            <!-- Products Table -->
                            <div class="table-responsive">
                                <table class="table table-condensed table-bordered table-striped table-responsive"
                                    id="pos_table">
                                    <thead>
                                        <tr>
                                            <th class="text-center">
                                                @lang('sale.product')
                                            </th>
                                            <th class="text-center">
                                                @lang('sale.qty')
                                            </th>
                                            <th class="text-center {{ $hide_tax }}">
                                                @lang('sale.price_inc_tax')
                                            </th>
                                            <th class="text-center">
                                                @lang('sale.subtotal')
                                            </th>
                                            <th class="text-center">
                                                <i class="fa fa-close" aria-hidden="true"></i>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>

                            <!-- Total Table -->
                            <div class="table-responsive">
                                <table class="table table-condensed table-bordered table-striped">
                                    <tr>
                                        <td>
                                            <div class="pull-right">
                                                <b>@lang('sale.total'): </b>
                                                <span class="price_total">0</span>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    @endcomponent

                    @component('components.widget', ['class' => 'box-primary'])
                        <!-- Discount Type -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="discount_type">@lang('sale.discount_type') :*</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-info"></i>
                                    </span>
                                    <select name="discount_type" id="discount_type" class="form-control" required
                                        data-default="percentage">
                                        <option value="">@lang('messages.please_select')</option>
                                        <option value="fixed">@lang('lang_v1.fixed')</option>
                                        <option value="percentage" selected>@lang('lang_v1.percentage')</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Discount Amount -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="discount_amount">@lang('sale.discount_amount') :*</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-info"></i>
                                    </span>
                                    <input type="text" name="discount_amount" id="discount_amount"
                                        class="form-control input_number"
                                        value="{{ @num_format($business_details->default_sales_discount) }}"
                                        data-default="{{ $business_details->default_sales_discount }}">
                                </div>
                            </div>
                        </div>

                        <!-- Total Discount Display -->
                        <div class="col-md-4"><br>
                            <b>@lang('sale.discount_amount'):</b>(-)
                            <span class="display_currency" id="total_discount">0</span>
                        </div>

                        <div class="clearfix"></div>

                        <!-- Tax Rate -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tax_rate_id">@lang('sale.order_tax') :*</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-info"></i>
                                    </span>
                                    <select name="tax_rate_id" id="tax_rate_id" class="form-control"
                                        data-default="{{ $business_details->default_sales_tax }}">
                                        <option value="">@lang('messages.please_select')</option>
                                        @foreach ($taxes['tax_rates'] as $key => $tax)
                                            <option value="{{ $key }}"
                                                {{ $business_details->default_sales_tax == $key ? 'selected' : '' }}
                                                {{ isset($tax) ? $tax : '' }}>
                                                {{ $tax }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <input type="hidden" name="tax_calculation_amount" id="tax_calculation_amount"
                                        value="{{ empty($edit) ? @num_format($business_details->tax_calculation_amount) : @num_format(optional($transaction->tax)->amount) }}"
                                        data-default="{{ $business_details->tax_calculation_amount }}">
                                </div>
                            </div>
                        </div>

                        <!-- Order Tax Display -->
                        <div class="col-md-4 col-md-offset-4">
                            <b>@lang('sale.order_tax'):</b>(+)
                            <span class="display_currency" id="order_tax">0</span>
                        </div>

                        <div class="clearfix"></div>

                        <!-- Shipping Details -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="shipping_details">@lang('sale.shipping_details')</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-info"></i>
                                    </span>
                                    <textarea name="shipping_details" id="shipping_details" class="form-control" placeholder="@lang('sale.shipping_details')"
                                        rows="1" cols="30"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Charges -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="shipping_charges">@lang('sale.shipping_charges')</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-info"></i>
                                    </span>
                                    <input type="text" name="shipping_charges" id="shipping_charges"
                                        class="form-control input_number" value="{{ @num_format(0.0) }}"
                                        placeholder="@lang('sale.shipping_charges')">
                                </div>
                            </div>
                        </div>

                        <div class="clearfix"></div>

                        <!-- Total Payable -->
                        <div class="col-md-4 col-md-offset-8">
                            <div>
                                <b>@lang('sale.total_payable'): </b>
                                <input type="hidden" name="final_total" id="final_total_input">
                                <span id="total_payable">0</span>
                            </div>
                        </div>

                        <!-- Sale Note -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="sale_note">@lang('sale.sell_note')</label>
                                <textarea name="sale_note" id="sale_note" class="form-control" rows="3"></textarea>
                            </div>
                        </div>

                        <input type="hidden" name="is_direct_sale" value="1">
                    @endcomponent
                </div>
            </div>

            @component('components.widget', [
                'class' => 'box-primary',
                'id' => 'payment_rows_div',
                'title' => __('purchase.add_payment'),
            ])
                <div class="payment_row">
                    <!-- Payment Row Form -->
                    @include('sale_pos.partials.payment_row_form', ['row_index' => 0])

                    <hr>

                    <!-- Balance Display -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="pull-right">
                                <strong>@lang('lang_v1.balance'):</strong>
                                <span class="balance_due">0.00</span>
                            </div>
                        </div>
                    </div>

                    <br>

                    <!-- Submit Button -->
                    <div class="row">
                        <div class="col-sm-12">
                            <button type="button" id="submit-sell" class="btn btn-primary pull-right btn-flat">
                                @lang('messages.submit')
                            </button>
                        </div>
                    </div>
                </div>
            @endcomponent

            @if (empty($pos_settings['disable_recurring_invoice']))
                @include('sale_pos.partials.recurring_invoice_modal')
            @endif

        </form>
    </section>

    <div class="modal fade contact_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        @include('contact.create', ['quick_add' => true])
    </div>
    <!-- /.content -->
    <div class="modal fade register_details_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>
    <div class="modal fade close_register_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>

@stop

@section('javascript')
    <script src="{{ asset('js/pos.js?v=' . $asset_v) }}"></script>
@endsection
