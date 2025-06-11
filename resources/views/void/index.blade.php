@extends('layouts.app')
@section('title', __( 'All void transaction'))

@section('content')

<livewire:void-transaction-table />

@stop

@section('javascript')
@livewireScripts
<script type="text/javascript">
    $(document).ready(function() {
        //Date range as a button
        $('#sell_list_filter_date_range').daterangepicker(
            dateRangeSettings,
            function(start, end) {
                $('#sell_list_filter_date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format));
                sell_table.ajax.reload();
            }
        );
        $('#sell_list_filter_date_range').on('cancel.daterangepicker', function(ev, picker) {
            $('#sell_list_filter_date_range').val('');
            sell_table.ajax.reload();
        });

        sell_table = $('#sell_table').DataTable({
            processing: true,
            serverSide: true,
            aaSorting: [
                [0, 'desc']
            ],
            "ajax": {
                "url": "/voids",
                "data": function(d) {
                    if ($('#sell_list_filter_date_range').val()) {
                        var start = $('#sell_list_filter_date_range').data('daterangepicker').startDate.format('YYYY-MM-DD');
                        var end = $('#sell_list_filter_date_range').data('daterangepicker').endDate.format('YYYY-MM-DD');
                        d.start_date = start;
                        d.end_date = end;
                    }
                    d.is_direct_sale = 1;

                    d.location_id = $('#sell_list_filter_location_id').val();
                    d.customer_id = $('#sell_list_filter_customer_id').val();
                    d.payment_status = $('#sell_list_filter_payment_status').val();

                    @if($is_woocommerce)
                    if ($('#synced_from_woocommerce').is(':checked')) {
                        d.only_woocommerce_sells = 1;
                    }
                    @endif
                }
            },
            columnDefs: [{
                "targets": [7],
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
                    data: 'mac',
                    name: 'mac',
                    'searchable': false
                },
                {
                    data: 'ip',
                    name: 'ip',
                    'searchable': false
                }
            ],

            "fnDrawCallback": function(oSettings) {

                $('#footer_sale_total').text(sum_table_col($('#sell_table'), 'final-total'));


                $('#footer_payment_status_count').html(__sum_status_html($('#sell_table'), 'payment-status-label'));

                __currency_convert_recursively($('#sell_table'));
            },
            createdRow: function(row, data, dataIndex) {
                $(row).find('td:eq(4)').attr('class', 'clickable_td');
            }
        });

        $(document).on('change', '#sell_list_filter_location_id, #sell_list_filter_customer_id, #sell_list_filter_payment_status', function() {
            sell_table.ajax.reload();
        });
        @if($is_woocommerce)
        $('#synced_from_woocommerce').on('ifChanged', function(event) {
            sell_table.ajax.reload();
        });
        @endif
    });
</script>
<script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
@endsection
