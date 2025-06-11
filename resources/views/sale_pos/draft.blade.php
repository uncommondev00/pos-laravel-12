@extends('layouts.app')
@section('title', __( 'sale.drafts'))
@section('content')

<livewire:drafts-table />
@stop
@section('javascript')
@livewireScripts
<script type="text/javascript">
    $(document).ready(function() {
        sell_table = $('#sell_table').DataTable({
            processing: true,
            serverSide: true,
            aaSorting: [
                [0, 'desc']
            ],
            ajax: '/sells/draft-dt?is_quotation=0',
            columnDefs: [{
                "targets": 4,
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
                    data: 'action',
                    name: 'action'
                }
            ],
            "fnDrawCallback": function(oSettings) {
                __currency_convert_recursively($('#purchase_table'));
            }
        });
        //Date range as a button
        $('#daterange-btn').daterangepicker(
            dateRangeSettings,
            function(start, end) {
                $('#daterange-btn span').html(start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format));
                sell_table.ajax.url('/sells/draft-dt?is_quotation=0&start_date=' + start.format('YYYY-MM-DD') +
                    '&end_date=' + end.format('YYYY-MM-DD')).load();
            }
        );
        $('#daterange-btn').on('cancel.daterangepicker', function(ev, picker) {
            sell_table.ajax.url('/sells/draft-dt?is_quotation=0').load();
            $('#daterange-btn span').html('<i class="fa fa-calendar"></i> Filter By Date');
        });
    });
</script>

@endsection
