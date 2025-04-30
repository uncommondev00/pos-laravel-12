<div class="modal-dialog" role="document">
  <div class="modal-content">

    <form action="{{ route('tax-rates.update', [$tax_rate->id]) }}" method="POST" id="tax_rate_edit_form">
      @csrf
      @method('PUT')

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">@lang('tax_rate.edit_taxt_rate')</h4>
      </div>

      <div class="modal-body">
        <div class="form-group">
          <label for="name">@lang('tax_rate.name') :*</label>
          <input
            type="text"
            class="form-control"
            name="name"
            id="name"
            value="{{ old('name', $tax_rate->name) }}"
            required
            placeholder="@lang('tax_rate.name')">
        </div>

        <div class="form-group">
          <label for="amount">@lang('tax_rate.rate') :*</label>
          <input
            type="text"
            class="form-control input_number"
            name="amount"
            id="amount"
            value="{{ old('amount', $tax_rate->amount) }}"
            required>
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
      </div>

    </form>

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
