<div class="pos-tab-content">
    <div class="row">
        <div class="col-xs-3">
            <div class="form-group">
                <label for="sms_settings[url]">URL:</label>
                <input type="text" name="sms_settings[url]" value="{{ old('sms_settings[url]', $sms_settings['url']) }}" class="form-control" placeholder="URL">
            </div>
        </div>
        <div class="col-xs-3">
            <div class="form-group">
                <label for="sms_settings[send_to_param_name]">{{ __('lang_v1.send_to_param_name') }}:</label>
                <input type="text" name="sms_settings[send_to_param_name]" value="{{ old('sms_settings[send_to_param_name]', $sms_settings['send_to_param_name']) }}" class="form-control" placeholder="{{ __('lang_v1.send_to_param_name') }}">
            </div>
        </div>
        <div class="col-xs-3">
            <div class="form-group">
                <label for="sms_settings[msg_param_name]">{{ __('lang_v1.msg_param_name') }}:</label>
                <input type="text" name="sms_settings[msg_param_name]" value="{{ old('sms_settings[msg_param_name]', $sms_settings['msg_param_name']) }}" class="form-control" placeholder="{{ __('lang_v1.msg_param_name') }}">
            </div>
        </div>
        <div class="col-xs-3">
            <div class="form-group">
                <label for="sms_settings[request_method]">{{ __('lang_v1.request_method') }}:</label>
                <select name="sms_settings[request_method]" class="form-control">
                    <option value="get" {{ $sms_settings['request_method'] == 'get' ? 'selected' : '' }}>GET</option>
                    <option value="post" {{ $sms_settings['request_method'] == 'post' ? 'selected' : '' }}>POST</option>
                </select>
            </div>
        </div>
        <div class="clearfix"></div>
        <hr>
        @for ($i = 1; $i <= 10; $i++)
            <div class="col-xs-4">
            <div class="form-group">
                <label for="sms_settings_param_key{{ $i }}">{{ __('lang_v1.sms_settings_param_key', ['number' => $i]) }}:</label>
                <input type="text" name="sms_settings[param_{{ $i }}]" value="{{ old('sms_settings[param_' . $i . ']', $sms_settings['param_' . $i]) }}" class="form-control" placeholder="{{ __('lang_v1.sms_settings_param_val', ['number' => $i]) }}" id="sms_settings_param_key{{ $i }}">
            </div>
    </div>
    <div class="col-xs-4">
        <div class="form-group">
            <label for="sms_settings_param_val{{ $i }}">{{ __('lang_v1.sms_settings_param_val', ['number' => $i]) }}:</label>
            <input type="text" name="sms_settings[param_val_{{ $i }}]" value="{{ old('sms_settings[param_val_' . $i . ']', $sms_settings['param_val_' . $i]) }}" class="form-control" placeholder="{{ __('lang_v1.sms_settings_param_val', ['number' => $i]) }}" id="sms_settings_param_val{{ $i }}">
        </div>
    </div>
    <div class="clearfix"></div>
    @endfor
</div>
</div>
