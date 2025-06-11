<div class="modal-dialog" role="document">
  <div class="modal-content">

    <form action="{{ route('business-location.update', [$location->id]) }}" method="POST" id="business_location_add_form">
      @csrf
      @method('PUT')

      <input type="hidden" name="hidden_id" id="hidden_id" value="{{ $location->id }}">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">@lang('business.edit_business_location')</h4>
      </div>

      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="name">@lang('invoice.name'):</label>
              <input type="text" class="form-control" name="name" id="name" value="{{ $location->name }}" required placeholder="@lang('invoice.name')">
            </div>
          </div>

          <div class="clearfix"></div>

          <div class="col-sm-6">
            <div class="form-group">
              <label for="location_id">@lang('lang_v1.location_id'):</label>
              <input type="text" class="form-control" name="location_id" id="location_id" value="{{ $location->location_id }}" placeholder="@lang('lang_v1.location_id')">
            </div>
          </div>

          <div class="col-sm-6">
            <div class="form-group">
              <label for="landmark">@lang('business.landmark'):</label>
              <input type="text" class="form-control" name="landmark" id="landmark" value="{{ $location->landmark }}" placeholder="@lang('business.landmark')">
            </div>
          </div>

          <div class="clearfix"></div>

          <div class="col-sm-6">
            <div class="form-group">
              <label for="city">@lang('business.city'):</label>
              <input type="text" class="form-control" name="city" id="city" value="{{ $location->city }}" placeholder="@lang('business.city')" required>
            </div>
          </div>

          <div class="col-sm-6">
            <div class="form-group">
              <label for="zip_code">@lang('business.zip_code'):</label>
              <input type="text" class="form-control" name="zip_code" id="zip_code" value="{{ $location->zip_code }}" placeholder="@lang('business.zip_code')" required>
            </div>
          </div>

          <div class="clearfix"></div>

          <div class="col-sm-6">
            <div class="form-group">
              <label for="state">@lang('business.state'):</label>
              <input type="text" class="form-control" name="state" id="state" value="{{ $location->state }}" placeholder="@lang('business.state')" required>
            </div>
          </div>

          <div class="col-sm-6">
            <div class="form-group">
              <label for="country">@lang('business.country'):</label>
              <input type="text" class="form-control" name="country" id="country" value="{{ $location->country }}" placeholder="@lang('business.country')" required>
            </div>
          </div>

          <div class="clearfix"></div>

          <div class="col-sm-6">
            <div class="form-group">
              <label for="mobile">@lang('business.mobile'):</label>
              <input type="text" class="form-control" name="mobile" id="mobile" value="{{ $location->mobile }}" placeholder="@lang('business.mobile')">
            </div>
          </div>

          <div class="col-sm-6">
            <div class="form-group">
              <label for="alternate_number">@lang('business.alternate_number'):</label>
              <input type="text" class="form-control" name="alternate_number" id="alternate_number" value="{{ $location->alternate_number }}" placeholder="@lang('business.alternate_number')">
            </div>
          </div>

          <div class="clearfix"></div>

          <div class="col-sm-6">
            <div class="form-group">
              <label for="email">@lang('business.email'):</label>
              <input type="email" class="form-control" name="email" id="email" value="{{ $location->email }}" placeholder="@lang('business.email')">
            </div>
          </div>

          <div class="col-sm-6">
            <div class="form-group">
              <label for="website">@lang('lang_v1.website'):</label>
              <input type="text" class="form-control" name="website" id="website" value="{{ $location->website }}" placeholder="@lang('lang_v1.website')">
            </div>
          </div>

          <div class="clearfix"></div>

          <div class="col-sm-6">
            <div class="form-group">
              <label for="invoice_scheme_id">@lang('invoice.invoice_scheme'):</label> @show_tooltip(__('tooltip.invoice_scheme'))
              <select name="invoice_scheme_id" id="invoice_scheme_id" class="form-control" required>
                <option value="">{{ __('messages.please_select') }}</option>
                @foreach($invoice_schemes as $key => $value)
                <option value="{{ $key }}" {{ $location->invoice_scheme_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="col-sm-6">
            <div class="form-group">
              <label for="invoice_layout_id">@lang('invoice.invoice_layout'):</label> @show_tooltip(__('tooltip.invoice_layout'))
              <select name="invoice_layout_id" id="invoice_layout_id" class="form-control" required>
                <option value="">{{ __('messages.please_select') }}</option>
                @foreach($invoice_layouts as $key => $value)
                <option value="{{ $key }}" {{ $location->invoice_layout_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="clearfix"></div>

          <div class="col-md-12">
            <hr />
          </div>

          @for ($i = 1; $i <= 4; $i++)
            <div class="col-sm-3">
            <div class="form-group">
              <label for="custom_field{{ $i }}">@lang('lang_v1.custom_field', ['number' => $i])</label>
              <input type="text" class="form-control" name="custom_field{{ $i }}" id="custom_field{{ $i }}"
                value="{{ $location->{'custom_field'.$i} }}"
                placeholder="@lang('lang_v1.custom_field', ['number' => $i])">
            </div>
        </div>
        @endfor

      </div>
  </div>

  <div class="modal-footer">
    <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
  </div>
  </form>

</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
