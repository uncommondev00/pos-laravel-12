<div class="pos-tab-content">
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label for="stock_expiry_alert_days">{{ __('business.view_stock_expiry_alert_for') . ':*' }}</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-calendar-times-o"></i>
                    </span>
                    <input type="number" name="stock_expiry_alert_days" value="{{ old('stock_expiry_alert_days', $business->stock_expiry_alert_days) }}" class="form-control" required>
                    <span class="input-group-addon">
                        @lang('business.days')
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
