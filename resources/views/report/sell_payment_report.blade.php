@extends('layouts.app')

@section('title', __('lang_v1.sell_payment_report'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>{{ __('lang_v1.sell_payment_report') }}</h1>
</section>

<!-- Main content -->
<section class="content no-print">
    <div class="row">
        <div class="col-md-12">
            @component('components.filters', ['title' => __('report.filters')])
            <form action="#" method="GET" id="sell_payment_report_form">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="customer_id">{{ __('contact.customer') }}:</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </span>
                            <select name="customer_id" id="customer_id" class="form-control select2" required>
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach($customers as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="location_id">{{ __('purchase.business_location') }}:</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-map-marker"></i>
                            </span>
                            <select name="location_id" id="location_id" class="form-control select2" required>
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach($business_locations as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="spr_date_filter">{{ __('report.date_range') }}:</label>
                        <input
                            type="text"
                            name="date_range"
                            id="spr_date_filter"
                            class="form-control"
                            value=""
                            readonly
                            placeholder="{{ __('lang_v1.select_a_date_range') }}">
                    </div>
                </div>
            </form>
            @endcomponent
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            @component('components.widget', ['class' => 'box-primary'])
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="sell_payment_report_table">
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>@lang('purchase.ref_no')</th>
                            <th>@lang('lang_v1.paid_on')</th>
                            <th>@lang('sale.amount')</th>
                            <th>@lang('contact.customer')</th>
                            <th>@lang('lang_v1.payment_method')</th>
                            <th>@lang('sale.sale')</th>
                            <th>@lang('messages.action')</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr class="bg-gray font-17 footer-total text-center">
                            <td colspan="3"><strong>@lang('sale.total'):</strong></td>
                            <td>
                                <span class="display_currency" id="footer_total_amount" data-currency_symbol="true"></span>
                            </td>
                            <td colspan="4"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @endcomponent
        </div>
    </div>
</section>

<!-- /.content -->
<div class="modal fade view_register" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>

@endsection

@section('javascript')
<script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
@endsection
