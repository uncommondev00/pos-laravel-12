@extends('layouts.app')
@section('title', __('lang_v1.sell_return'))

@section('content')

<livewire:sell-return-table />
@stop
@section('javascript')
<script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
<script>
    $(document).ready(function() {
        //Date range as a button
        $('#daterange-btn').daterangepicker(
            dateRangeSettings,
            function(start, end) {
                $('#daterange-btn span').html(start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format));
                sell_return_table.ajax.url('/sell-return?start_date=' + start.format('YYYY-MM-DD') + '&end_date=' + end.format('YYYY-MM-DD')).load();
            }
        );
        $('#daterange-btn').on('cancel.daterangepicker', function(ev, picker) {
            sell_return_table.ajax.url('/sell-return').load();
            $('#daterange-btn span').html('<i class="fa fa-calendar"></i> {{ __("messages.filter_by_date") }}');
        });

        sell_return_table = $('#sell_return_table').DataTable({
            processing: true,
            serverSide: true,
            aaSorting: [
                [0, 'desc']
            ],
            "ajax": {
                "url": "/sell-return",
                "data": function(d) {
                    var start = $('#daterange-btn').data('daterangepicker').startDate.format('YYYY-MM-DD');
                    var end = $('#daterange-btn').data('daterangepicker').endDate.format('YYYY-MM-DD');
                    d.start_date = start;
                    d.end_date = end;
                }
            },
            columnDefs: [{
                "targets": [7, 8],
                "orderable": false,
                "searchable": false
            }],
            columns: [{
                    data: 'transaction_date',
                    name: 'transaction_date'
                },
                {
                    data: 'invoice_no',
                    name: 'invoice_no'
                },
                {
                    data: 'parent_sale',
                    name: 'T1.invoice_no'
                },
                {
                    data: 'name',
                    name: 'contacts.name'
                },
                {
                    data: 'business_location',
                    name: 'bl.name'
                },
                {
                    data: 'payment_status',
                    name: 'payment_status'
                },
                {
                    data: 'final_total',
                    name: 'final_total'
                },
                {
                    data: 'payment_due',
                    name: 'payment_due'
                },
                {
                    data: 'action',
                    name: 'action'
                }
            ],
            "fnDrawCallback": function(oSettings) {
                var total_sell = sum_table_col($('#sell_return_table'), 'final_total');
                $('#footer_sell_return_total').text(total_sell);

                $('#footer_payment_status_count').html(__sum_status_html($('#sell_return_table'), 'payment-status-label'));

                var total_due = sum_table_col($('#sell_return_table'), 'payment_due');
                $('#footer_total_due').text(total_due);

                __currency_convert_recursively($('#sell_return_table'));
            },
            createdRow: function(row, data, dataIndex) {
                $(row).find('td:eq(2)').attr('class', 'clickable_td');
            }
        });
    })
</script>

@endsection
