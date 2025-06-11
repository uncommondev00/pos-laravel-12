<div class="modal-dialog" role="document">
  <div class="modal-content">

    <form action="{{ route('units.update', $unit->id) }}" method="POST" id="unit_edit_form">
      @csrf
      @method('PUT')

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">@lang( 'unit.edit_unit' )</h4>
      </div>

      <div class="modal-body">
        <div class="row">
          <div class="form-group col-sm-12">
            <label for="actual_name">{{ __('unit.name') }}:*</label>
            <input type="text" name="actual_name" id="actual_name" class="form-control" required placeholder="{{ __('unit.name') }}" value="{{ $unit->actual_name }}">
          </div>

          <div class="form-group col-sm-12">
            <label for="short_name">{{ __('unit.short_name') }}:*</label>
            <input type="text" name="short_name" id="short_name" class="form-control" placeholder="{{ __('unit.short_name') }}" required value="{{ $unit->short_name }}">
          </div>

          <div class="form-group col-sm-12">
            <label for="allow_decimal">{{ __('unit.allow_decimal') }}:*</label>
            <select name="allow_decimal" id="allow_decimal" class="form-control" required>
              <option value="">{{ __('messages.please_select') }}</option>
              <option value="1" {{ $unit->allow_decimal == 1 ? 'selected' : '' }}>{{ __('messages.yes') }}</option>
              <option value="0" {{ $unit->allow_decimal == 0 ? 'selected' : '' }}>{{ __('messages.no') }}</option>
            </select>
          </div>

          <div class="form-group col-sm-12">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="define_base_unit" value="1" class="toggler" data-toggle_id="base_unit_div" {{ !empty($unit->base_unit_id) ? 'checked' : '' }}> @lang( 'lang_v1.add_as_multiple_of_base_unit' )
                </label> @show_tooltip(__('lang_v1.multi_unit_help'))
              </div>
            </div>
          </div>

          <div class="form-group col-sm-12 @if(empty($unit->base_unit_id)) hide @endif" id="base_unit_div">
            <table class="table">
              <tr>
                <th style="vertical-align: middle;">1 <span id="unit_name">{{$unit->actual_name}}</span></th>
                <th style="vertical-align: middle;">=</th>
                <td style="vertical-align: middle;">
                  <input type="text" name="base_unit_multiplier" class="form-control input_number" placeholder="{{ __('lang_v1.times_base_unit') }}" value="{{ !empty($unit->base_unit_multiplier) ? number_format($unit->base_unit_multiplier) : '' }}">
                </td>
                <td style="vertical-align: middle;">
                  <select name="base_unit_id" class="form-control">
                    <option value="">{{ __('lang_v1.select_base_unit') }}</option>
                    @foreach($units as $key => $value)
                    <option value="{{ $key }}" {{ $unit->base_unit_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                  </select>
                </td>
              </tr>
              <tr>
                <td colspan="4" style="padding-top: 0;">
                  <p class="help-block">*@lang('lang_v1.edit_multi_unit_help_text')</p>
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">@lang( 'messages.update' )</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
      </div>

    </form>

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
