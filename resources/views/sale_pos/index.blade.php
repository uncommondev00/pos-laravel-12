@extends('layouts.app')
@section('title', __( 'sale.list_pos'))

@section('content')

<livewire:sell-table />

<!-- /.content -->
<div class="modal fade payment_modal" tabindex="-1" role="dialog"
    aria-labelledby="gridSystemModalLabel">
</div>

<div class="modal fade edit_payment_modal" tabindex="-1" role="dialog"
    aria-labelledby="gridSystemModalLabel">
</div>

<div class="modal fade register_details_modal" tabindex="-1" role="dialog"
    aria-labelledby="gridSystemModalLabel">
</div>
<div class="modal fade close_register_modal" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog"
    aria-labelledby="gridSystemModalLabel">
</div>

<!-- This will be printed -->
<!-- <section class="invoice print_section" id="receipt_section">
</section> -->


@stop

@section('javascript')
@livewireScripts
<script type="text/javascript">
// Add this script to your layout file
document.addEventListener('livewire:load', function() {
    // Initialize date range picker
    $('#sell_list_filter_date_range').daterangepicker(
        {
            locale: {
                format: 'YYYY-MM-DD'
            },
            opens: 'left',
            autoUpdateInput: false
        },
        function(start, end) {
            alert(0)
            // Update the input display
            $(this).val(start.format('YYYY-MM-DD') + ' ~ ' + end.format('YYYY-MM-DD'));
            
            // Send dates to Livewire
            Livewire.emit('dateRangeChanged', start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
        }
    );

    // Clear handler
    $('#sell_list_filter_date_range').on('cancel.daterangepicker', function() {
        $(this).val('');
        Livewire.emit('dateRangeChanged', '', '');
    });
});
    $(document).ready(function() {
        //Date range as a button
        //Date range as a button
        // $('#sell_list_filter_date_range').daterangepicker(
        //     dateRangeSettings,
        //     function(start, end) {
        //         $('#sell_list_filter_date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format));
        //         sell_table.ajax.reload();
        //     }
        // );
        // $('#sell_list_filter_date_range').on('cancel.daterangepicker', function(ev, picker) {
        //     $('#sell_list_filter_date_range').val('');
        //     sell_table.ajax.reload();
        // });

        sell_table = $('#sell_table').DataTable({
            processing: true,
            serverSide: true,
            aaSorting: [
                [0, 'desc']
            ],
            "ajax": {
                "url": "/sells",
                "data": function(d) {
                    if ($('#sell_list_filter_date_range').val()) {
                        var start = $('#sell_list_filter_date_range').data('daterangepicker').startDate.format('YYYY-MM-DD');
                        var end = $('#sell_list_filter_date_range').data('daterangepicker').endDate.format('YYYY-MM-DD');
                        d.start_date = start;
                        d.end_date = end;
                    }
                    d.is_direct_sale = 0;

                    d.location_id = $('#sell_list_filter_location_id').val();
                    d.customer_id = $('#sell_list_filter_customer_id').val();
                    d.payment_status = $('#sell_list_filter_payment_status').val();
                }
            },
            columnDefs: [{
                "targets": 12,
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
                    data: 'total_paid',
                    name: 'total_paid',
                    'searchable': false
                },
                {
                    data: 'total_remaining',
                    name: 'total_remaining'
                },
                {
                    data: 'vatable',
                    name: 'vatable',
                    'searchable': false
                },
                {
                    data: 'vat',
                    name: 'vat',
                    'searchable': false
                },
                {
                    data: 'vat_exempt',
                    name: 'vat_exempt',
                    'searchable': false
                },
                {
                    data: 'vzr',
                    name: 'vzr',
                    'searchable': false
                },
                {
                    data: 'action',
                    name: 'action'
                }
            ],
            "fnDrawCallback": function(oSettings) {

                $('#footer_sale_total').text(sum_table_col($('#sell_table'), 'final-total'));

                $('#footer_total_paid').text(sum_table_col($('#sell_table'), 'total-paid'));

                $('#footer_total_remaining').text(sum_table_col($('#sell_table'), 'payment_due'));
                $('#footer_total_sell_return_due').text(sum_table_col($('#sell_table'), 'sell_return_due'));

                $('#footer_vatable').text(sum_table_col($('#sell_table'), 'vatable'));

                $('#footer_vat').text(sum_table_col($('#sell_table'), 'vat'));

                $('#footer_vat_exempt').text(sum_table_col($('#sell_table'), 'vat_exempt'));

                $('#footer_vzr').text(sum_table_col($('#sell_table'), 'vzr'));

                $('#footer_payment_status_count ').html(__sum_status_html($('#sell_table'), 'payment-status-label'));
                __currency_convert_recursively($('#sell_table'));
            },
            createdRow: function(row, data, dataIndex) {
                $(row).find('td:eq(4)').attr('class', 'clickable_td');
            }
        });

        $(document).on('change', '#sell_list_filter_location_id, #sell_list_filter_customer_id, #sell_list_filter_payment_status', function() {
            sell_table.ajax.reload();
        });
    });
</script>
<script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
@endsection
