@extends('layouts.app')
@section('title', __('lang_v1.lot_report'))

@section('content')

<section class="content-header">
    <h1>{{ __('lang_v1.lot_report') }}</h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            @component('components.filters', ['title' => __('report.filters')])
            <form action="{{ route('reports.getStockReport') }}" method="get" id="stock_report_filter_form">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="location_id">{{ __('purchase.business_location') }}:</label>
                            <select name="location_id" class="form-control select2" style="width:100%">
                                @foreach($business_locations as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="category">{{ __('category.category') }}:</label>
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
                            <label for="sub_category">{{ __('product.sub_category') }}:</label>
                            <select name="sub_category" id="sub_category_id" class="form-control select2" style="width:100%">
                                <option value="">{{ __('messages.all') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="brand">{{ __('product.brand') }}:</label>
                            <select name="brand" class="form-control select2" style="width:100%">
                                <option value="">{{ __('messages.all') }}</option>
                                @foreach($brands as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="unit">{{ __('product.unit') }}:</label>
                            <select name="unit" class="form-control select2" style="width:100%">
                                <option value="">{{ __('messages.all') }}</option>
                                @foreach($units as $key => $value)
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
                <table class="table table-bordered table-striped" id="lot_report">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>@lang('business.product')</th>
                            <th>@lang('lang_v1.lot_number')</th>
                            <th>@lang('product.exp_date')</th>
                            <th>@lang('report.current_stock')</th>
                            <th>@lang('report.total_unit_sold')</th>
                            <th>@lang('lang_v1.total_unit_adjusted')</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr class="bg-gray font-17 text-center footer-total">
                            <td colspan="4"><strong>@lang('sale.total'):</strong></td>
                            <td id="footer_total_stock"></td>
                            <td id="footer_total_sold"></td>
                            <td id="footer_total_adjusted"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @endcomponent
        </div>
    </div>
</section>

@endsection

@section('javascript')
<script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script>
@endsection
