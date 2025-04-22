<div class="modal-dialog" role="document">
  <div class="modal-content">

    <form action="{{ route('group-taxes.store') }}" method="POST" id="tax_group_add_form">
      @csrf

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">@lang('tax_rate.add_tax_group')</h4>
      </div>

      <div class="modal-body">
        <div class="form-group">
          <label for="name">@lang('tax_rate.name') :*</label>
          <input
            type="text"
            class="form-control"
            name="name"
            id="name"
            value="{{ old('name') }}"
            required
            placeholder="@lang('tax_rate.name')">
        </div>

        <div class="form-group">
          <label for="taxes[]">@lang('tax_rate.sub_taxes') :*</label>
          <select
            name="taxes[]"
            id="taxes"
            class="form-control select2"
            required
            multiple>
            @foreach($taxes as $tax_id => $tax_name)
            <option value="{{ $tax_id }}" {{ in_array($tax_id, old('taxes', [])) ? 'selected' : '' }}>
              {{ $tax_name }}
            </option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
      </div>

    </form>

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
