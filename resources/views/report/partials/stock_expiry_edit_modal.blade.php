<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      <h4 class="modal-title">
        <b>@lang('product.product_name'):</b> {{ $purchase_line->name }},
        <b>@lang('purchase.ref_no'):</b> {{ $purchase_line->ref_no }}
      </h4>
    </div>

    <form id="stock_exp_modal_form" method="POST" action="{{ route('reports.updateStockExpiryReport') }}">
      @csrf
      <input type="hidden" name="purchase_line_id" value="{{ $purchase_line->id }}">

      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="exp_date">{{ __('product.exp_date') }}:*</label>
              <input
                type="text"
                name="exp_date"
                id="exp_date_expiry_modal"
                class="form-control"
                value="@format_date($purchase_line->exp_date) "
                required
                readonly>
              <i>
                <p class="help-block">@lang('lang_v1.expiry_date_will_be_changed_in_pl')</p>
              </i>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.cancel')</button>
      </div>
    </form>
  </div>
</div>
