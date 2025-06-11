<div class="modal-dialog" role="document">
  <div class="modal-content">

    <form action="{{ route('discount.update', [$discount->id]) }}" method="POST" id="discount_form">
      @csrf
      @method('PUT')

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">@lang('sale.edit_discount')</h4>
      </div>

      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="name">@lang('unit.name')*</label>
              <input type="text" name="name" id="name" class="form-control" required placeholder="@lang('unit.name')" value="{{ $discount->name }}">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="brand_id">@lang('product.brand')</label>
              <select name="brand_id" id="brand_id" class="form-control select2">
                <option value="">@lang('messages.please_select')</option>
                @foreach($brands as $key => $value)
                <option value="{{ $key }}" {{ $discount->brand_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="category_id">@lang('product.category')</label>
              <select name="category_id" id="category_id" class="form-control select2">
                <option value="">@lang('messages.please_select')</option>
                @foreach($categories as $key => $value)
                <option value="{{ $key }}" {{ $discount->category_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="location_id">@lang('sale.location')*</label>
              <select name="location_id" id="location_id" class="form-control select2" required>
                <option value="">@lang('messages.please_select')</option>
                @foreach($locations as $key => $value)
                <option value="{{ $key }}" {{ $discount->location_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="priority">@lang('lang_v1.priority')</label>
              <input type="text" name="priority" id="priority" class="form-control input_number" required placeholder="@lang('lang_v1.priority')" value="{{ $discount->priority }}">
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="discount_type">@lang('sale.discount_type')*</label>
              <select name="discount_type" id="discount_type" class="form-control select2" required>
                <option value="">@lang('messages.please_select')</option>
                <option value="fixed" {{ $discount->discount_type == 'fixed' ? 'selected' : '' }}>@lang('lang_v1.fixed')</option>
                <option value="percentage" {{ $discount->discount_type == 'percentage' ? 'selected' : '' }}>@lang('lang_v1.percentage')</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="discount_amount">@lang('sale.discount_amount')*</label>
              <input type="text" name="discount_amount" id="discount_amount" class="form-control input_number" required placeholder="@lang('sale.discount_amount')" value="{{ $discount->discount_amount }}">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="starts_at">@lang('lang_v1.starts_at')</label>
              <input type="text" name="starts_at" id="starts_at" class="form-control discount_date" required placeholder="@lang('lang_v1.starts_at')" value="{{ $starts_at }}" readonly>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="ends_at">@lang('lang_v1.ends_at')</label>
              <input type="text" name="ends_at" id="ends_at" class="form-control discount_date" required placeholder="@lang('lang_v1.ends_at')" value="{{ $ends_at }}" readonly>
            </div>
          </div>

          <div class="col-sm-6">
            <div class="form-group">
              <br>
              <label>
                <input type="checkbox" name="applicable_in_spg" value="1" class="input-icheck" {{ !empty($discount->applicable_in_spg) ? 'checked' : '' }}>
                <strong>@lang('lang_v1.applicable_in_cpg')</strong>
              </label>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <br>
              <label>
                <input type="checkbox" name="applicable_in_cg" value="1" class="input-icheck" {{ !empty($discount->applicable_in_cg) ? 'checked' : '' }}>
                <strong>@lang('lang_v1.applicable_in_cg')</strong>
              </label>
            </div>
          </div>

          <div class="col-sm-6">
            <div class="form-group">
              <label>
                <input type="checkbox" name="is_active" value="1" class="input-icheck" {{ !empty($discount->is_active) ? 'checked' : '' }}>
                <strong>@lang('lang_v1.is_active')</strong>
              </label>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
      </div>
    </form>

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
