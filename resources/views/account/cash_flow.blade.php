@extends('layouts.app')
@section('title', __('lang_v1.cash_flow'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang('lang_v1.cash_flow')
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-solid">
                <div class="box-header">
                    <h3 class="box-title">
                        <i class="fa fa-filter" aria-hidden="true"></i> @lang('report.filters'):
                    </h3>
                </div>
                <div class="box-body">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="account_id">@lang('account.account'):</label>
                            <select name="account_id" id="account_id" class="form-control">
                                <option value="">@lang('messages.please_select')</option>
                                @foreach($accounts as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="transaction_date_range">@lang('report.date_range'):</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" name="transaction_date_range" id="transaction_date_range"
                                    class="form-control" readonly placeholder="@lang('report.date_range')">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="transaction_type">@lang('account.transaction_type'):</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-exchange"></i></span>
                                <select name="transaction_type" id="transaction_type" class="form-control">
                                    <option value="">@lang('messages.all')</option>
                                    <option value="debit">@lang('account.debit')</option>
                                    <option value="credit">@lang('account.credit')</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div> <!-- .box-body -->
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-body">
                    @can('account.access')
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="cash_flow_table">
                            <thead>
                                <tr>
                                    <th>@lang('messages.date')</th>
                                    <th>@lang('account.account')</th>
                                    <th>@lang('lang_v1.description')</th>
                                    <th>@lang('account.credit')</th>
                                    <th>@lang('account.debit')</th>
                                    <th>@lang('lang_v1.balance')</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade account_model" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>
</section>

<!-- /.content -->

@endsection

@section('javascript')
<script>
    $(document).ready(function() {

        dateRangeSettings.autoUpdateInput = false
        $('#transaction_date_range').daterangepicker(
            dateRangeSettings,
            function(start, end) {
                $('#transaction_date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format));
                var start = '';
                var end = '';
                if ($('#transaction_date_range').val()) {
                    start = $('input#transaction_date_range').data('daterangepicker').startDate.format('YYYY-MM-DD');
                    end = $('input#transaction_date_range').data('daterangepicker').endDate.format('YYYY-MM-DD');
                }
                cash_flow_table.ajax.reload();
            }
        );

        // Cash Flow Table
        cash_flow_table = $('#cash_flow_table').DataTable({
            processing: true,
            serverSide: true,
            "ajax": {
                "url": "{{route('account.cashFlow')}}",
                "data": function(d) {
                    var start = '';
                    var end = '';
                    if ($('#transaction_date_range').val() != '') {
                        start = $('#transaction_date_range').data('daterangepicker').startDate.format('YYYY-MM-DD');
                        end = $('#transaction_date_range').data('daterangepicker').endDate.format('YYYY-MM-DD');
                    }

                    d.account_id = $('#account_id').val();
                    d.type = $('#transaction_type').val();
                    d.start_date = start,
                        d.end_date = end
                }
            },
            "ordering": false,
            "searching": false,
            columns: [{
                    data: 'operation_date',
                    name: 'operation_date'
                },
                {
                    data: 'account_name',
                    name: 'account_name'
                },
                {
                    data: 'sub_type',
                    name: 'sub_type'
                },
                {
                    data: 'credit',
                    name: 'amount'
                },
                {
                    data: 'debit',
                    name: 'amount'
                },
                {
                    data: 'balance',
                    name: 'balance'
                },
            ],
            "fnDrawCallback": function(oSettings) {
                __currency_convert_recursively($('#cash_flow_table'));
            }
        });
        $('#transaction_type, #account_id').change(function() {
            cash_flow_table.ajax.reload();
        });
        $('#transaction_date_range').on('cancel.daterangepicker', function(ev, picker) {
            $('#transaction_date_range').val('').change();
            cash_flow_table.ajax.reload();
        });

    });
</script>
@endsection
