<div class="modal-dialog" role="document">
  <div class="modal-content">

    <form action="{{ route('business-location.update', [$location->id]) }}" method="POST" id="business_location_add_form">
      @csrf
      @method('PUT')

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">@lang('business.edit_business_location')</h4>
      </div>

      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="name">@lang('invoice.name'):*</label>
              <input type="text" name="name" id="name" class="form-control" required placeholder="@lang('invoice.name')" value="{{ $location->name }}">
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label for="landmark">@lang('business.landmark'):</label>
              <input type="text" name="landmark" id="landmark" class="form-control" placeholder="@lang('business.landmark')" value="{{ $location->landmark }}">
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label for="city">@lang('business.city'):*</label>
              <input type="text" name="city" id="city" class="form-control" required placeholder="@lang('business.city')" value="{{ $location->city }}">
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label for="zip_code">@lang('business.zip_code'):*</label>
              <input type="text" name="zip_code" id="zip_code" class="form-control" required placeholder="@lang('business.zip_code')" value="{{ $location->zip_code }}">
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label for="state">@lang('business.state'):*</label>
              <input type="text" name="state" id="state" class="form-control" required placeholder="@lang('business.state')" value="{{ $location->state }}">
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label for="country">@lang('business.country'):*</label>
              <input type="text" name="country" id="country" class="form-control" required placeholder="@lang('business.country')" value="{{ $location->country }}">
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label for="invoice_scheme_id">@lang('invoice.invoice_scheme'):*</label> @show_tooltip(__('tooltip.invoice_scheme'))
              <select name="invoice_scheme_id" id="invoice_scheme_id" class="form-control" required>
                <option value="">@lang('messages.please_select')</option>
                @foreach($invoice_schemes as $key => $value)
                <option value="{{ $key }}" {{ $location->invoice_scheme_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label for="invoice_layout_id">@lang('invoice.invoice_layout'):*</label> @show_tooltip(__('tooltip.invoice_layout'))
              <select name="invoice_layout_id" id="invoice_layout_id" class="form-control" required>
                <option value="">@lang('messages.please_select')</option>
                @foreach($invoice_layouts as $key => $value)
                <option value="{{ $key }}" {{ $location->invoice_layout_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
      </div>

    </form>

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
