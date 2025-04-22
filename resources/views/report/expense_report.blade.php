@extends('layouts.app')

@section('title', __('report.expense_report'))


@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>{{ __('report.expense_report') }}</h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="row no-print">
        <div class="col-md-12">
            <!-- Filters Section -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ __('report.filters') }}</h3>
                </div>
                <div class="box-body">
                    <form action="{{ route('reports.getExpenseReport') }}" method="get">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="location_id">{{ __('purchase.business_location') }}:</label>
                                    <select name="location_id" id="location_id" class="form-control select2" style="width: 100%">
                                        @foreach ($business_locations as $key => $location)
                                        <option value="{{ $key }}">{{ $location }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="category_id">{{ __('report.category') }}:</label>
                                    <select name="category" id="category_id" class="form-control select2" style="width: 100%">
                                        <option value="">{{ __('report.all') }}</option>
                                        @foreach ($categories as $key => $category)
                                        <option value="{{ $key }}">{{ $category }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="trending_product_date_range">{{ __('report.date_range') }}:</label>
                                    @php
                                    use Carbon\Carbon;

                                    $startDate = Carbon::now()->startOfMonth()->format('Y-m-d'); // or your desired format
                                    $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
                                    @endphp


                                    <input type="text" name="date_range" id="trending_product_date_range" class="form-control" value="{{ $startDate . ' ~ ' . $endDate }}" readonly placeholder="{{ __('lang_v1.select_a_date_range') }}">

                                </div>
                            </div>
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary pull-right">{{ __('report.apply_filters') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-body">

                </div>
            </div>
        </div>
    </div>

    <!-- Print Button -->
    <div class="row no-print">
        <div class="col-sm-12">
            <button type="button" class="btn btn-primary pull-right" aria-label="Print" onclick="window.print();">
                <i class="fa fa-print"></i> {{ __('messages.print') }}
            </button>
        </div>
    </div>
</section>

@endsection

@section('javascript')
<script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script>

@endsection
