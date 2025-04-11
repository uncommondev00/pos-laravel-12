@extends('layouts.app')

@section('title', __('sale.edit_sale'))

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('sale.edit_sale') <small>(@lang('sale.invoice_no'): <span
                    class="text-success">#{{ $transaction->invoice_no }})</span></small></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <input type="hidden" id="item_addition_method" value="{{ $business_details->item_addition_method }}">

        <form action="{{ route('pos.update', ['id' => $transaction->id]) }}" method="POST"
            id="edit_sell_form">
            @csrf
            @method('PUT')

            <input type="hidden" id="location_id" name="location_id" value="{{ $transaction->location_id }}"
                data-receipt_printer_type="{{ !empty($location_printer_type) ? $location_printer_type : 'browser' }}">

            <div class="row">
                <div class="col-md-12 col-sm-12">
                    @component('components.widget', ['class' => 'box-primary'])
                        <!-- Price Groups Section -->
                        @if (!empty($price_groups))
                            @if (count($price_groups) > 1)
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-money"></i>
                                            </span>
                                            <input type="hidden" id="hidden_price_group" name="hidden_price_group"
                                                value="{{ $transaction->selling_price_group_id }}">
                                            <select name="price_group" id="price_group" class="form-control select2">
                                                @foreach ($price_groups as $key => $value)
                                                    <option value="{{ $key }}"
                                                        {{ $transaction->selling_price_group_id == $key ? 'selected' : '' }}>
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="input-group-addon">
                                                @show_tooltip(__('lang_v1.price_group_help_text'))
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <input type="hidden" id="price_group" name="price_group"
                                    value="{{ $transaction->selling_price_group_id }}">
                            @endif
                        @endif

                        <!-- Subscription Section -->
                        @if (in_array('subscription', $enabled_modules))
                            <div class="col-md-4 pull-right col-sm-6">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="is_recurring" id="is_recurring" class="input-icheck"
                                            value="1" {{ $transaction->is_recurring ? 'checked' : '' }}>
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

                        <!-- Customer Selection -->
                        <div class="clearfix"></div>
                        <div class="@if (!empty($commission_agent)) col-sm-3 @else col-sm-4 @endif">
                            <div class="form-group">
                                <label for="customer_id">@lang('contact.customer')<span class="required">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </span>
                                    <input type="hidden" id="default_customer_id" value="{{ $transaction->contact->id }}">
                                    <input type="hidden" id="default_customer_name" value="{{ $transaction->contact->name }}">
                                    <select name="contact_id" id="customer_id" class="form-control mousetrap" required>
                                        <option value="">Enter Customer name / phone</option>
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

                        <!-- Payment Terms -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="multi-input">
                                    <label for="pay_term_number">
                                        @lang('contact.pay_term'):
                                        @show_tooltip(__('tooltip.pay_term'))
                                    </label>
                                    <br />
                                    <input type="number" name="pay_term_number" id="pay_term_number"
                                        value="{{ $transaction->pay_term_number }}" class="form-control width-40 pull-left"
                                        placeholder="@lang('contact.pay_term')">

                                    <select name="pay_term_type" id="pay_term_type" class="form-control width-60 pull-left">
                                        <option value="">@lang('messages.please_select')</option>
                                        <option value="months" {{ $transaction->pay_term_type == 'months' ? 'selected' : '' }}>
                                            @lang('lang_v1.months')
                                        </option>
                                        <option value="days" {{ $transaction->pay_term_type == 'days' ? 'selected' : '' }}>
                                            @lang('lang_v1.days')
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Continue with more sections... -->
                        <!-- Commission Agent Section -->
                        @if (!empty($commission_agent))
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="commission_agent">@lang('lang_v1.commission_agent'):</label>
                                    <select name="commission_agent" id="commission_agent" class="form-control select2">
                                        @foreach ($commission_agent as $key => $agent)
                                            <option value="{{ $key }}"
                                                {{ $transaction->commission_agent == $key ? 'selected' : '' }}>
                                                {{ $agent }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        <!-- Transaction Date -->
                        <div class="@if (!empty($commission_agent)) col-sm-3 @else col-sm-4 @endif">
                            <div class="form-group">
                                <label for="transaction_date">@lang('sale.sale_date')<span class="required">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input type="text" name="transaction_date" id="transaction_date" class="form-control"
                                        value="{{ $transaction->transaction_date }}" readonly required>
                                </div>
                            </div>
                        </div>

                        <!-- Status Section -->
                        @php
                            if ($transaction->status == 'draft' && $transaction->is_quotation == 1) {
                                $status = 'quotation';
                            } else {
                                $status = $transaction->status;
                            }
                        @endphp
                        <div class="@if (!empty($commission_agent)) col-sm-3 @else col-sm-4 @endif">
                            <div class="form-group">
                                <label for="status">@lang('sale.status')<span class="required">*</span></label>
                                <select name="status" id="status" class="form-control select2" required>
                                    <option value="">@lang('messages.please_select')</option>
                                    <option value="final" {{ $status == 'final' ? 'selected' : '' }}>@lang('sale.final')
                                    </option>
                                    <option value="draft" {{ $status == 'draft' ? 'selected' : '' }}>@lang('sale.draft')
                                    </option>
                                    <option value="quotation" {{ $status == 'quotation' ? 'selected' : '' }}>
                                        @lang('lang_v1.quotation')</option>
                                </select>
                            </div>
                        </div>

                        <!-- Product Search Section -->
                        <div class="col-sm-10 col-sm-offset-1">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-barcode"></i>
                                    </span>
                                    <input type="text" name="search_product" id="search_product"
                                        class="form-control mousetrap" placeholder="@lang('lang_v1.search_product_placeholder')" autofocus>
                                </div>
                            </div>
                        </div>

                        <!-- Products Table Section -->
                        <div class="row col-sm-12 pos_product_div" style="min-height: 0">
                            <input type="hidden" name="sell_price_tax" id="sell_price_tax"
                                value="{{ $business_details->sell_price_tax }}">

                            <input type="hidden" id="product_row_count" value="{{ count($sell_details) }}">

                            @php
                                $hide_tax = '';
                                if (session()->get('business.enable_inline_tax') == 0) {
                                    $hide_tax = 'hide';
                                }
                            @endphp

                            <div class="table-responsive">
                                <table class="table table-condensed table-bordered table-striped table-responsive"
                                    id="pos_table">
                                    <thead>
                                        <tr>
                                            <th class="text-center">@lang('sale.product')</th>
                                            <th class="text-center">@lang('sale.qty')</th>
                                            <th class="text-center {{ $hide_tax }}">@lang('sale.price_inc_tax')</th>
                                            <th class="text-center">@lang('sale.subtotal')</th>
                                            <th class="text-center"><i class="fa fa-close"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sell_details as $sell_line)
                                            @include('sale_pos.product_row', [
                                                'product' => $sell_line,
                                                'row_count' => $loop->index,
                                                'tax_dropdown' => $taxes,
                                                'sub_units' => !empty($sell_line->unit_details)
                                                    ? $sell_line->unit_details
                                                    : [],
                                            ])
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Total Table -->
                            <div class="table-responsive">
                                <table class="table table-condensed table-bordered table-striped table-responsive">
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

                        <!-- Discount and Tax Section -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="discount_type">@lang('sale.discount_type')<span class="required">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-info"></i>
                                    </span>
                                    <select name="discount_type" id="discount_type" class="form-control" required
                                        data-default="percentage">
                                        <option value="">@lang('messages.please_select')</option>
                                        <option value="fixed" {{ $transaction->discount_type == 'fixed' ? 'selected' : '' }}>
                                            @lang('lang_v1.fixed')
                                        </option>
                                        <option value="percentage"
                                            {{ $transaction->discount_type == 'percentage' ? 'selected' : '' }}>
                                            @lang('lang_v1.percentage')
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="discount_amount">@lang('sale.discount_amount')<span class="required">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-info"></i>
                                    </span>
                                    <input type="text" name="discount_amount" id="discount_amount"
                                        class="form-control input_number"
                                        value="{{ @num_format($transaction->discount_amount) }}"
                                        data-default="{{ $business_details->default_sales_discount }}">
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Details Section -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="shipping_details">@lang('sale.shipping_details')</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-info"></i>
                                    </span>
                                    <textarea name="shipping_details" id="shipping_details" class="form-control" placeholder="@lang('sale.shipping_details')"
                                        rows="1" cols="30">{{ $transaction->shipping_details }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="shipping_charges">@lang('sale.shipping_charges')</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-info"></i>
                                    </span>
                                    <input type="text" name="shipping_charges" id="shipping_charges"
                                        class="form-control input_number"
                                        value="{{ @num_format($transaction->shipping_charges) }}"
                                        placeholder="@lang('sale.shipping_charges')">
                                </div>
                            </div>
                        </div>

                        <!-- Final Total Section -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="sale_note">@lang('sale.sell_note')</label>
                                <textarea name="sale_note" id="sale_note" class="form-control" rows="3">{{ $transaction->additional_notes }}</textarea>
                            </div>
                        </div>

                        <input type="hidden" name="is_direct_sale" value="1">

                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary pull-right" id="submit-sell">
                                @lang('messages.update')
                            </button>
                        </div>

                        @if (in_array('subscription', $enabled_modules))
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
