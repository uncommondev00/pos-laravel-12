@extends('layouts.app')
@section('title', __('lang_v1.product_purchase_report'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>{{ __('lang_v1.product_purchase_report')}}</h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            @component('components.filters', ['title' => __('report.filters')])
            <form action="{{ route('reports.getStockReport') }}" method="get" id="product_purchase_report_form">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="search_product">{{ __('lang_v1.search_product') }}:</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-search"></i>
                            </span>
                            <input type="hidden" value="" id="variation_id">
                            <input type="text" name="search_product" id="search_product" class="form-control"
                                placeholder="{{ __('lang_v1.search_product_placeholder') }}" autofocus>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="supplier_id">{{ __('purchase.supplier') }}:</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </span>
                            <select name="supplier_id" class="form-control select2" required>
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach($suppliers as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="location_id">{{ __('purchase.business_location') }}:</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-map-marker"></i>
                            </span>
                            <select name="location_id" class="form-control select2" required>
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach($business_locations as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="product_pr_date_filter">{{ __('report.date_range') }}:</label>
                        <input type="text" name="date_range" id="product_pr_date_filter" class="form-control"
                            placeholder="{{ __('lang_v1.select_a_date_range') }}" readonly>
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
                <table class="table table-bordered table-striped" id="product_purchase_report_table">
                    <thead>
                        <tr>
                            <th>@lang('sale.product')</th>
                            <th>@lang('purchase.supplier')</th>
                            <th>@lang('purchase.ref_no')</th>
                            <th>@lang('messages.date')</th>
                            <th>@lang('sale.qty')</th>
                            <th>@lang('lang_v1.total_unit_adjusted')</th>
                            <th>@lang('lang_v1.unit_perchase_price')</th>
                            <th>@lang('sale.subtotal')</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr class="bg-gray font-17 footer-total text-center">
                            <td colspan="4"><strong>@lang('sale.total'):</strong></td>
                            <td id="footer_total_purchase"></td>
                            <td id="footer_total_adjusted"></td>
                            <td></td>
                            <td><span class="display_currency" id="footer_subtotal" data-currency_symbol="true"></span></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @endcomponent
        </div>
    </div>
</section>
<!-- /.content -->
<div class="modal fade view_register" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
</div>

@endsection

@section('javascript')
<script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script>
@endsection
