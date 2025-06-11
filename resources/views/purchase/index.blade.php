@extends('layouts.app')
@section('title', __('purchase.purchases'))

@section('content')

<livewire:purchase-table />

<section id="receipt_section" class="print_section"></section>

<!-- /.content -->
@stop
@section('javascript')
@livewireScripts
<script src="{{ asset('js/purchase.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
<script>
    document.addEventListener('livewire:load', function() {
        Livewire.hook('message.processed', (message, component) => {
            __currency_convert_recursively($('#purchase-table'));
        });

    });
    Date range as a button
    $('#purchase_list_filter_date_range').daterangepicker(
        dateRangeSettings,
        function(start, end) {
            $('#purchase_list_filter_date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format));
            purchase_table.ajax.reload();
        }
    );
    $('#purchase_list_filter_date_range').on('cancel.daterangepicker', function(ev, picker) {
        purchase_table.ajax.reload();
        $('#purchase_list_filter_date_range').val('');
    });
</script>

@endsection
