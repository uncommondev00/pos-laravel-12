<div class="pos-tab-content">
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label for="theme_color">{{ __('lang_v1.theme_color') }}</label>
                <select name="theme_color" id="theme_color" class="form-control select2" style="width: 100%;">
                    <option value="">{{ __('messages.please_select') }}</option>
                    @foreach($theme_colors as $value => $label)
                    <option value="{{ $value }}" {{ $business->theme_color == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="enable_tooltip" value="1" class="input-icheck" {{ $business->enable_tooltip ? 'checked' : '' }}>
                        {{ __('business.show_help_text') }}
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
