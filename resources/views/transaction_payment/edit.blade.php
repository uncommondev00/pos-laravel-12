<div class="modal-dialog" role="document">
  <div class="modal-content">

    <form action="{{ route('brands.update', [$brand->id]) }}" method="POST" id="brand_edit_form">
      @csrf
      @method('PUT')

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">@lang('brand.edit_brand')</h4>
      </div>

      <div class="modal-body">
        <div class="form-group">
          <label for="name">@lang('brand.brand_name'):*</label>
          <input
            type="text"
            name="name"
            id="name"
            class="form-control"
            value="{{ $brand->name }}"
            required
            placeholder="@lang('brand.brand_name')">
        </div>

        <div class="form-group">
          <label for="description">@lang('brand.short_description'):</label>
          <input
            type="text"
            name="description"
            id="description"
            class="form-control"
            value="{{ $brand->description }}"
            placeholder="@lang('brand.short_description')">
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
      </div>
    </form>

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
