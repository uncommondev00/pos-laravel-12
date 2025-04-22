<div class="pos-tab-content">
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label for="tax_label_1">{{ __('business.tax_1_name') }}:</label>
                <span data-toggle="tooltip" title="{{ __('tooltip.tax_1_name') }}">
                    <i class="fa fa-info-circle"></i>
                </span>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-info"></i>
                    </span>
                    <input type="text" name="tax_label_1" value="{{ old('tax_label_1', $business->tax_label_1) }}" class="form-control" placeholder="{{ __('business.tax_1_placeholder') }}">
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="tax_number_1">{{ __('business.tax_1_no') }}:</label>
                <span data-toggle="tooltip" title="{{ __('tooltip.tax_1_no') }}">
                    <i class="fa fa-info-circle"></i>
                </span>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-info"></i>
                    </span>
                    <input type="text" name="tax_number_1" value="{{ old('tax_number_1', $business->tax_number_1) }}" class="form-control">
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="tax_label_2">{{ __('business.tax_2_name') }}:</label>
                <span data-toggle="tooltip" title="{{ __('tooltip.tax_2_name') }}">
                    <i class="fa fa-info-circle"></i>
                </span>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-info"></i>
                    </span>
                    <input type="text" name="tax_label_2" value="{{ old('tax_label_2', $business->tax_label_2) }}" class="form-control" placeholder="{{ __('business.tax_1_placeholder') }}">
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="tax_number_2">{{ __('business.tax_2_no') }}:</label>
                <span data-toggle="tooltip" title="{{ __('tooltip.tax_2_no') }}">
                    <i class="fa fa-info-circle"></i>
                </span>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-info"></i>
                    </span>
                    <input type="text" name="tax_number_2" value="{{ old('tax_number_2', $business->tax_number_2) }}" class="form-control">
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="form-group">
                <div class="checkbox">
                    <br>
                    <label>
                        <input type="checkbox" name="enable_inline_tax" value="1" {{ $business->enable_inline_tax ? 'checked' : '' }} class="input-icheck">
                        {{ __('lang_v1.enable_inline_tax') }}
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
