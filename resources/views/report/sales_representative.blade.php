@extends('layouts.app')
@section('title', __('report.sales_representative'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>{{ __('report.sales_representative') }}</h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid">
                <div class="box-body">
                    <form action="{{ route('reports.getStockReport') }}" method="get" id="sales_representative_filter_form">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="sr_id">{{ __('report.user') }}:</label>
                                <select name="sr_id" class="form-control select2" style="width:100%" placeholder="{{ __('report.all_users') }}">
                                    @foreach($users as $userId => $userName)
                                    <option value="{{ $userId }}">{{ $userName }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="sr_business_id">{{ __('business.business_location') }}:</label>
                                <select name="sr_business_id" class="form-control select2" style="width:100%">
                                    @foreach($business_locations as $businessId => $businessName)
                                    <option value="{{ $businessId }}">{{ $businessName }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="sr_date_filter">{{ __('report.date_range') }}:</label>
                                <input type="text" name="date_range" placeholder="{{ __('lang_v1.select_a_date_range') }}" class="form-control" id="sr_date_filter" readonly>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary pull-right">{{ __('messages.filter') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-solid">
                <div class="box-body">
                    <h3 class="text-muted">
                        {{ __('report.total_sell') }} - {{ __('lang_v1.total_sales_return') }}:
                        <span id="sr_total_sales">
                            <i class="fa fa-refresh fa-spin fa-fw"></i>
                        </span>
                        -
                        <span id="sr_total_sales_return">
                            <i class="fa fa-refresh fa-spin fa-fw"></i>
                        </span>
                        =
                        <span id="sr_total_sales_final">
                            <i class="fa fa-refresh fa-spin fa-fw"></i>
                        </span>
                    </h3>
                    <div class="hide" id="total_commission_div">
                        <h3 class="text-muted">
                            {{ __('lang_v1.total_sale_commission') }}:
                            <span id="sr_total_commission">
                                <i class="fa fa-refresh fa-spin fa-fw"></i>
                            </span>
                        </h3>
                    </div>
                    <h3 class="text-muted">
                        {{ __('report.total_expense') }}:
                        <span id="sr_total_expenses">
                            <i class="fa fa-refresh fa-spin fa-fw"></i>
                        </span>
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <!-- Custom Tabs -->
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#sr_sales_tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-cog" aria-hidden="true"></i> @lang('lang_v1.sales_added')</a>
                    </li>

                    <li>
                        <a href="#sr_commission_tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-cog" aria-hidden="true"></i> @lang('lang_v1.sales_with_commission')</a>
                    </li>

                    <li>
                        <a href="#sr_expenses_tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-cog" aria-hidden="true"></i> @lang('expense.expenses')</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="sr_sales_tab">
                        @include('report.partials.sales_representative_sales')
                    </div>

                    <div class="tab-pane" id="sr_commission_tab">
                        @include('report.partials.sales_representative_commission')
                    </div>

                    <div class="tab-pane" id="sr_expenses_tab">
                        @include('report.partials.sales_representative_expenses')
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
<!-- /.content -->

<div class="modal fade view_register" tabindex="-1" role="dialog"
    aria-labelledby="gridSystemModalLabel">
</div>
<div class="modal fade payment_modal" tabindex="-1" role="dialog"
    aria-labelledby="gridSystemModalLabel">
</div>
<div class="modal fade edit_payment_modal" tabindex="-1" role="dialog"
    aria-labelledby="gridSystemModalLabel">
</div>

@endsection

@section('javascript')
<script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
@endsection
