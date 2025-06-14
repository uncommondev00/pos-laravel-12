@extends('layouts.app')
@section('title', __('lang_v1.purchase_payment_report'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>{{ __('lang_v1.purchase_payment_report')}}</h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            @component('components.filters', ['title' => __('report.filters')])
            <form action="#" method="get" id="purchase_payment_report_form">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="supplier_id">{{ __('purchase.supplier') }}:</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </span>
                            <select name="supplier_id" id="supplier_id" class="form-control select2" placeholder="{{ __('messages.please_select') }}" required>
                                @foreach($suppliers as $key => $value)
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
                            <select name="location_id" id="location_id" class="form-control select2" placeholder="{{ __('messages.please_select') }}" required>
                                @foreach($business_locations as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="ppr_date_filter">{{ __('report.date_range') }}:</label>
                        <input type="text" name="date_range" id="ppr_date_filter" class="form-control" placeholder="{{ __('lang_v1.select_a_date_range') }}" readonly>
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
                <table class="table table-bordered table-striped"
                    id="purchase_payment_report_table">
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>@lang('purchase.ref_no')</th>
                            <th>@lang('lang_v1.paid_on')</th>
                            <th>@lang('sale.amount')</th>
                            <th>@lang('purchase.supplier')</th>
                            <th>@lang('lang_v1.payment_method')</th>
                            <th>@lang('lang_v1.purchase')</th>
                            <th>@lang('messages.action')</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr class="bg-gray font-17 footer-total text-center">
                            <td colspan="3"><strong>@lang('sale.total'):</strong></td>
                            <td><span class="display_currency" id="footer_total_amount" data-currency_symbol="true"></span></td>
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
<div class="modal fade view_register" tabindex="-1" role="dialog"
    aria-labelledby="gridSystemModalLabel">
</div>

@endsection

@section('javascript')
<script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
@endsection
