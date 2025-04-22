@extends('layouts.app')
@section('title', __('report.stock_expiry_report'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>{{ __('report.stock_expiry_report') }}</h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            @component('components.filters', ['title' => __('report.filters')])
            <form action="{{ route('reports.getStockExpiryReport') }}" method="get" id="stock_report_filter_form">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="location_id">{{ __('purchase.business_location') }}:</label>
                            <select name="location_id" id="location_id" class="form-control select2" style="width:100%">
                                @foreach($business_locations as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="category_id">Category:</label>
                            <select name="category" id="category_id" class="form-control select2" style="width:100%">
                                <option value="">{{ __('messages.all') }}</option>
                                @foreach($categories as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sub_category_id">Sub Category:</label>
                            <select name="sub_category" id="sub_category_id" class="form-control select2" style="width:100%">
                                <option value="">{{ __('messages.all') }}</option>
                                {{-- Sub-categories will be loaded dynamically --}}
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="brand">Brand:</label>
                            <select name="brand" id="brand" class="form-control select2" style="width:100%">
                                <option value="">{{ __('messages.all') }}</option>
                                @foreach($brands as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="unit">Unit:</label>
                            <select name="unit" id="unit" class="form-control select2" style="width:100%">
                                <option value="">{{ __('messages.all') }}</option>
                                @foreach($units as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="view_stock_filter">{{ __('report.view_stocks') }}:</label>
                            <select name="view_stock_filter" id="view_stock_filter" class="form-control select2" style="width:100%">
                                <option value="">{{ __('messages.all') }}</option>
                                @foreach($view_stock_filter as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
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
                <table class="table table-bordered table-striped" id="stock_expiry_report_table">
                    <thead>
                        <tr>
                            <th>@lang('business.product')</th>
                            <th>SKU</th>
                            <th>@lang('purchase.ref_no')</th>
                            <th>@lang('business.location')</th>
                            <th>@lang('report.stock_left')</th>
                            <th>@lang('lang_v1.lot_number')</th>
                            <th>@lang('product.exp_date')</th>
                            <th>@lang('product.mfg_date')</th>
                            <th>@lang('messages.edit')</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr class="bg-gray font-17 text-center footer-total">
                            <td colspan="4"><strong>@lang('sale.total'):</strong></td>
                            <td id="footer_total_stock_left"></td>
                            <td colspan="4"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @endcomponent
        </div>
    </div>
</section>

<div class="modal fade exp_update_modal" tabindex="-1" role="dialog">
</div>
@endsection

@section('javascript')
<script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script>
@endsection
