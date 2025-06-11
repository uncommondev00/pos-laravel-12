<div class="col-md-12">
    <div class="box box-solid payment_row bg-lightgray" id="tr_row">
        @if($removable)
        <div class="box-header">
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool remove_payment_row">
                    <i class="fa fa-times fa-2x"></i>
                </button>
            </div>
        </div>
        @endif

        @if(!empty($payment_line['id']))
        <input type="hidden"
            name="payment[{{ $row_index }}][payment_id]"
            id="payment_{{ $row_index }}_id"
            value="{{ $payment_line['id'] }}">
        @endif

        <div class="box-body">
            @include('sale_pos.partials.payment_row_form')
        </div>
    </div>
</div>
