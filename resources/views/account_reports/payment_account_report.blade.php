@extends('layouts.app')
@section('title', __('account.payment_account_report'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>{{ __('account.payment_account_report')}}</h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary" id="accordion">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseFilter">
                            <i class="fa fa-filter" aria-hidden="true"></i> @lang('report.filters')
                        </a>
                    </h3>
                </div>
                <div id="collapseFilter" class="panel-collapse active collapse in" aria-expanded="true">
                    <div class="box-body">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="account_id">{{ __('account.account') }}:</label>
                                <select name="account_id" id="account_id" class="form-control select2">
                                    @foreach($accounts as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date_filter">{{ __('report.date_range') }}:</label>
                                <input type="text" name="date_range" id="date_filter" class="form-control" placeholder="{{ __('lang_v1.select_a_date_range') }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="payment_account_report">
                            <thead>
                                <tr>
                                    <th>@lang('messages.date')</th>
                                    <th>@lang('account.payment_ref_no')</th>
                                    <th>@lang('account.invoice_ref_no')</th>
                                    <th>@lang('lang_v1.payment_type')</th>
                                    <th>@lang('account.account')</th>
                                    <th>@lang('messages.action')</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->

@endsection

@section('javascript')

<script type="text/javascript">
    $(document).ready(function() {
        if ($('#date_filter').length == 1) {
            $('#date_filter').daterangepicker({
                ranges: ranges,
                autoUpdateInput: false,
                locale: {
                    format: moment_date_format
                }
            });
            $('#date_filter').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format(moment_date_format) + ' ~ ' + picker.endDate.format(moment_date_format));
                payment_account_report.ajax.reload();
            });

            $('#date_filter').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                payment_account_report.ajax.reload();
            });
        }

        payment_account_report = $('#payment_account_report').DataTable({
            processing: true,
            serverSide: true,
            "ajax": {
                "url": "{{route('account.paymentAccountReport')}}",
                "data": function(d) {
                    d.account_id = $('#account_id').val();
                    var start_date = '';
                    var endDate = '';
                    if ($('#date_filter').val()) {
                        var start_date = $('#date_filter').data('daterangepicker').startDate.format('YYYY-MM-DD');
                        var endDate = $('#date_filter').data('daterangepicker').endDate.format('YYYY-MM-DD');
                    }
                    d.start_date = start_date;
                    d.end_date = endDate;
                }
            },
            columnDefs: [{
                "targets": 5,
                "orderable": false,
                "searchable": false
            }],
            columns: [{
                    data: 'paid_on',
                    name: 'paid_on'
                },
                {
                    data: 'payment_ref_no',
                    name: 'payment_ref_no'
                },
                {
                    data: 'transaction_number',
                    name: 'transaction_number'
                },
                {
                    data: 'type',
                    name: 'T.type'
                },
                {
                    data: 'account',
                    name: 'account'
                },
                {
                    data: 'action',
                    name: 'action'
                }
            ],
            "fnDrawCallback": function(oSettings) {
                __currency_convert_recursively($('#payment_account_report'));
            }
        });

        $('select#account_id, #date_filter').change(function() {
            payment_account_report.ajax.reload();
        });
    })

    $(document).on('submit', 'form#link_account_form', function(e) {
        e.preventDefault();
        var data = $(this).serialize();

        $.ajax({
            method: $(this).attr("method"),
            url: $(this).attr("action"),
            dataType: "json",
            data: data,
            success: function(result) {
                if (result.success === true) {
                    $('div.view_modal').modal('hide');
                    toastr.success(result.msg);
                    payment_account_report.ajax.reload();
                } else {
                    toastr.error(result.msg);
                }
            }
        });
    });
</script>
@endsection
