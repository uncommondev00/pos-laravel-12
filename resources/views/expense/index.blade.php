@extends('layouts.app')
@section('title', __('expense.expenses'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang('expense.expenses')</h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            @component('components.filters', ['title' => __('report.filters')])
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
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="expense_for">{{ __('expense.expense_for') }}:</label>
                    <select name="expense_for" id="expense_for" class="form-control select2">
                        @foreach($users as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="expense_category_id">{{ __('expense.expense_category') }}:</label>
                    <select name="expense_category_id" id="expense_category_id" class="form-control select2" style="width:100%">
                        <option value="">{{ __('report.all') }}</option>
                        @foreach($categories as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="expense_date_range">{{ __('report.date_range') }}:</label>
                    <input type="text" name="date_range" id="expense_date_range" class="form-control"
                        value="@format_date('first day of this month')  ~ @format_date('last day of this month') "
                        placeholder="{{ __('lang_v1.select_a_date_range') }}" readonly>
                </div>
            </div>
            @endcomponent
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @component('components.widget', ['class' => 'box-primary', 'title' => __('expense.all_expenses')])
            @can('category.create')
            @slot('tool')
            <div class="box-tools">
                <a class="btn btn-block btn-primary" href="{{route('expenses.create')}}">
                    <i class="fa fa-plus"></i> @lang('messages.add')</a>
            </div>
            @endslot
            @endcan
            @can('category.view')
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="expense_table">
                    <thead>
                        <tr>
                            <th>@lang('messages.date')</th>
                            <th>@lang('purchase.ref_no')</th>
                            <th>@lang('expense.expense_category')</th>
                            <th>@lang('business.location')</th>
                            <th>@lang('sale.payment_status')</th>
                            <th>@lang('sale.total_amount')</th>
                            <th>@lang('purchase.payment_due')
                            <th>@lang('expense.expense_for')</th>
                            <th>@lang('expense.expense_note')</th>
                            <th>@lang('messages.action')</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr class="bg-gray font-17 text-center footer-total">
                            <td colspan="4"><strong>@lang('sale.total'):</strong></td>
                            <td id="footer_payment_status_count"></td>
                            <td><span class="display_currency" id="footer_expense_total" data-currency_symbol="true"></span></td>
                            <td><span class="display_currency" id="footer_total_due" data-currency_symbol="true"></span></td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @endcan
            @endcomponent
        </div>
    </div>

</section>
<!-- /.content -->
<!-- /.content -->
<div class="modal fade payment_modal" tabindex="-1" role="dialog"
    aria-labelledby="gridSystemModalLabel">
</div>

<div class="modal fade edit_payment_modal" tabindex="-1" role="dialog"
    aria-labelledby="gridSystemModalLabel">
</div>
@stop
@section('javascript')
<script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
@endsection
