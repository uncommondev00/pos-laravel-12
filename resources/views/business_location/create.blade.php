<div class="modal-dialog" role="document">
  <div class="modal-content">

    <form action="{{ route('business-location.store') }}" method="POST" id="business_location_add_form">
      @csrf

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">@lang('business.add_business_location')</h4>
      </div>

      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="name">@lang('invoice.name') :*</label>
              <input type="text" name="name" class="form-control" required placeholder="@lang('invoice.name')" />
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="location_id">@lang('lang_v1.location_id') :</label>
              <input type="text" name="location_id" class="form-control" placeholder="@lang('lang_v1.location_id')" />
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="landmark">@lang('business.landmark') :</label>
              <input type="text" name="landmark" class="form-control" placeholder="@lang('business.landmark')" />
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="city">@lang('business.city') :*</label>
              <input type="text" name="city" class="form-control" required placeholder="@lang('business.city')" />
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="zip_code">@lang('business.zip_code') :*</label>
              <input type="text" name="zip_code" class="form-control" required placeholder="@lang('business.zip_code')" />
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="state">@lang('business.state') :*</label>
              <input type="text" name="state" class="form-control" required placeholder="@lang('business.state')" />
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="country">@lang('business.country') :*</label>
              <input type="text" name="country" class="form-control" required placeholder="@lang('business.country')" />
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="mobile">@lang('business.mobile') :</label>
              <input type="text" name="mobile" class="form-control" placeholder="@lang('business.mobile')" />
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="alternate_number">@lang('business.alternate_number') :</label>
              <input type="text" name="alternate_number" class="form-control" placeholder="@lang('business.alternate_number')" />
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="email">@lang('business.email') :</label>
              <input type="email" name="email" class="form-control" placeholder="@lang('business.email')" />
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="website">@lang('lang_v1.website') :</label>
              <input type="text" name="website" class="form-control" placeholder="@lang('lang_v1.website')" />
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="invoice_scheme_id">@lang('invoice.invoice_scheme') :*</label>
              <select name="invoice_scheme_id" class="form-control" required>
                <option value="">@lang('messages.please_select')</option>
                @foreach($invoice_schemes as $id => $scheme)
                <option value="{{ $id }}">{{ $scheme }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="invoice_layout_id">@lang('invoice.invoice_layout') :*</label>
              <select name="invoice_layout_id" class="form-control" required>
                <option value="">@lang('messages.please_select')</option>
                @foreach($invoice_layouts as $id => $layout)
                <option value="{{ $id }}">{{ $layout }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="col-sm-12">
            <hr />
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="custom_field1">@lang('lang_v1.custom_field', ['number' => 1]) :</label>
              <input type="text" name="custom_field1" class="form-control" placeholder="@lang('lang_v1.custom_field', ['number' => 1])" />
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="custom_field2">@lang('lang_v1.custom_field', ['number' => 2]) :</label>
              <input type="text" name="custom_field2" class="form-control" placeholder="@lang('lang_v1.custom_field', ['number' => 2])" />
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="custom_field3">@lang('lang_v1.custom_field', ['number' => 3]) :</label>
              <input type="text" name="custom_field3" class="form-control" placeholder="@lang('lang_v1.custom_field', ['number' => 3])" />
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="custom_field4">@lang('lang_v1.custom_field', ['number' => 4]) :</label>
              <input type="text" name="custom_field4" class="form-control" placeholder="@lang('lang_v1.custom_field', ['number' => 4])" />
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
