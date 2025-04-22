<div class="pos-tab-content">
  <div class="row">
    @if(!empty($modules))
    <h4>@lang('lang_v1.enable_disable_modules')</h4>
    @foreach($modules as $k => $v)
    <div class="col-sm-4">
      <div class="form-group">
        <div class="checkbox">
          <br>
          <label>
            <input type="checkbox" name="enabled_modules[]" value="{{ $k }}"
              class="input-icheck"
              {{ in_array($k, $enabled_modules) ? 'checked' : '' }}>
            {{$v['name']}}
          </label>
          @if(!empty($v['tooltip']))
          @show_tooltip($v['tooltip'])
          @endif
        </div>
      </div>
    </div>
    @endforeach
    @endif
  </div>
</div>
