@extends('layouts.app')
@section('title', __('lang_v1.purchase_return'))

@section('content')

<livewire:purchase-return-table />
@stop
@section('javascript')
@livewireScripts
<script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
<script>
    $(document).ready(function() {
        //Purchase table
        purchase_return_table = $('#purchase_return_datatable').DataTable({
            processing: true,
            serverSide: true,
            aaSorting: [
                [0, 'desc']
            ],
            ajax: '/purchase-return',
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
                    data: 'ref_no',
                    name: 'ref_no'
                },
                {
                    data: 'parent_purchase',
                    name: 'T.ref_no'
                },
                {
                    data: 'location_name',
                    name: 'BS.name'
                },
                {
                    data: 'name',
                    name: 'contacts.name'
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
                var total_purchase = sum_table_col($('#purchase_return_datatable'), 'final_total');
                $('#footer_purchase_return_total').text(total_purchase);

                $('#footer_payment_status_count').html(__sum_status_html($('#purchase_return_datatable'), 'payment-status-label'));

                var total_due = sum_table_col($('#purchase_return_datatable'), 'payment_due');
                $('#footer_total_due').text(total_due);

                __currency_convert_recursively($('#purchase_return_datatable'));
            },
            createdRow: function(row, data, dataIndex) {
                $(row).find('td:eq(5)').attr('class', 'clickable_td');
            }
        });
        //Date range as a button
        $('#daterange-btn').daterangepicker(
            dateRangeSettings,
            function(start, end) {
                $('#daterange-btn span').html(start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format));
                purchase_return_table.ajax.url('/purchase-return?start_date=' + start.format('YYYY-MM-DD') +
                    '&end_date=' + end.format('YYYY-MM-DD')).load();
            }
        );
        $('#daterange-btn').on('cancel.daterangepicker', function(ev, picker) {
            purchase_return_table.ajax.url('/purchase-return').load();
            $('#daterange-btn span').html('<i class="fa fa-calendar"></i> {{ __("messages.filter_by_date") }}');
        });
    });
</script>

@endsection
