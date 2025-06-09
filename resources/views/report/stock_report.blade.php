@extends('layouts.app')
@section('title', __('report.stock_report'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>{{ __('report.stock_report') }}</h1>
</section>

<!-- Main content -->
<section class="content no-print">
    <div class="row">
        <div class="col-md-12">
            @component('components.filters', ['title' => __('report.filters')])
            <form action="{{ route('reports.getStockReport') }}" method="get" id="stock_report_filter_form">
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
                            <label for="category_id">{{ __('category.category') }}:</label>
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
                            <label for="sub_category_id">{{ __('product.sub_category') }}:</label>
                            <select name="sub_category" id="sub_category_id" class="form-control select2" style="width:100%">
                                <option value="">{{ __('messages.all') }}</option>
                                {{-- Dynamic options will be loaded via JS --}}
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="brand">{{ __('product.brand') }}:</label>
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
                            <label for="unit">{{ __('product.unit') }}:</label>
                            <select name="unit" id="unit" class="form-control select2" style="width:100%">
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
            @slot('tool')
            <div class="box-tools">
                <a class="btn btn-block btn-primary print-stock-report" data-href="{{ route('reports.printStockreport') }}">
                    <i class="fa fa-print"></i> @lang('Print')
                </a>
            </div>
            @endslot
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="stock_report_table">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>@lang('business.product')</th>
                            <th>@lang('sale.unit_price')</th>
                            <th>@lang('report.current_stock')</th>
                            <th>@lang('report.total_unit_sold')</th>
                            <th>@lang('lang_v1.total_unit_transfered')</th>
                            <th>@lang('lang_v1.total_unit_adjusted')</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr class="bg-gray font-17 text-center footer-total">
                            <td colspan="3"><strong>@lang('sale.total'):</strong></td>
                            <td id="footer_total_stock"></td>
                            <td id="footer_total_sold"></td>
                            <td id="footer_total_transfered"></td>
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
<script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script>/

<script type="text/javascript">
    $(document).on('click', 'a.print-stock-report', function(e) {
        e.preventDefault();
        var href = $(this).data('href');

        $.ajax({
            method: 'GET',
            url: href,
            dataType: 'json',
            success: function(result) {
                $('#stock_report_section').html(result.html_content);
                setTimeout(function() {
                    window.print();
                    location.reload();
                }, 1000);
            },
        });
    });
</script>
@endsection
