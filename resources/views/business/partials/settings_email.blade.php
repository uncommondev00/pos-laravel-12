<div class="pos-tab-content">
    <div class="row">
        <div class="col-xs-4">
            <div class="form-group">
                <label for="email_settings[mail_driver]">{{ __('lang_v1.mail_driver') }}:</label>
                <select name="email_settings[mail_driver]" class="form-control">
                    @foreach ($mail_drivers as $driver)
                    <option value="{{ $driver }}" {{ $driver == ($email_settings['mail_driver'] ?? 'smtp') ? 'selected' : '' }}>{{ $driver }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
                <label for="email_settings[mail_host]">{{ __('lang_v1.mail_host') }}:</label>
                <input type="text" name="email_settings[mail_host]" value="{{ old('email_settings.mail_host', $email_settings['mail_host'] ?? '') }}" class="form-control" placeholder="{{ __('lang_v1.mail_host') }}">
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
                <label for="email_settings[mail_port]">{{ __('lang_v1.mail_port') }}:</label>
                <input type="text" name="email_settings[mail_port]" value="{{ old('email_settings.mail_port', $email_settings['mail_port'] ?? '') }}" class="form-control" placeholder="{{ __('lang_v1.mail_port') }}">
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
                <label for="email_settings[mail_username]">{{ __('lang_v1.mail_username') }}:</label>
                <input type="text" name="email_settings[mail_username]" value="{{ old('email_settings.mail_username', $email_settings['mail_username'] ?? '') }}" class="form-control" placeholder="{{ __('lang_v1.mail_username') }}">
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
                <label for="email_settings[mail_password]">{{ __('lang_v1.mail_password') }}:</label>
                <input type="password" name="email_settings[mail_password]" value="{{ old('email_settings.mail_password', $email_settings['mail_password'] ?? '') }}" class="form-control" placeholder="{{ __('lang_v1.mail_password') }}">
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
                <label for="email_settings[mail_encryption]">{{ __('lang_v1.mail_encryption') }}:</label>
                <input type="text" name="email_settings[mail_encryption]" value="{{ old('email_settings.mail_encryption', $email_settings['mail_encryption'] ?? '') }}" class="form-control" placeholder="{{ __('lang_v1.mail_encryption_place') }}">
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
                <label for="email_settings[mail_from_address]">{{ __('lang_v1.mail_from_address') }}:</label>
                <input type="email" name="email_settings[mail_from_address]" value="{{ old('email_settings.mail_from_address', $email_settings['mail_from_address'] ?? '') }}" class="form-control" placeholder="{{ __('lang_v1.mail_from_address') }}">
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
                <label for="email_settings[mail_from_name]">{{ __('lang_v1.mail_from_name') }}:</label>
                <input type="text" name="email_settings[mail_from_name]" value="{{ old('email_settings.mail_from_name', $email_settings['mail_from_name'] ?? '') }}" class="form-control" placeholder="{{ __('lang_v1.mail_from_name') }}">
            </div>
        </div>
    </div>
</div>
