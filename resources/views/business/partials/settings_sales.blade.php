<div class="pos-tab-content">
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label for="default_sales_discount">{{ __('business.default_sales_discount') }}:*</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-percent"></i>
                    </span>
                    <input type="number" name="default_sales_discount" id="default_sales_discount" class="form-control" min="0" step="0.01" max="100" value="{{ old('default_sales_discount', $business->default_sales_discount) }}">
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label for="default_sales_tax">{{ __('business.default_sales_tax') }}:</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-info"></i>
                    </span>
                    <select name="default_sales_tax" id="default_sales_tax" class="form-control select2" style="width: 100%;">
                        <option value="" disabled>{{ __('business.default_sales_tax') }}</option>
                        @foreach($tax_rates as $key => $value)
                        <option value="{{ $key }}" {{ $key == old('default_sales_tax', $business->default_sales_tax) ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group">
                <label>{{ __('business.sell_price_tax') }}:</label>
                <div class="input-group">
                    <div class="radio">
                        <label>
                            <input type="radio" name="sell_price_tax" value="includes" class="input-icheck" {{ old('sell_price_tax', $business->sell_price_tax) == 'includes' ? 'checked' : '' }}>
                            {{ __('Includes the Sale Tax') }}
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="sell_price_tax" value="excludes" class="input-icheck" {{ old('sell_price_tax', $business->sell_price_tax) == 'excludes' ? 'checked' : '' }}>
                            {{ __('Excludes the Sale Tax (Calculate sale tax on Selling Price provided in Add Purchase)') }}
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label for="sales_cmsn_agnt">{{ __('lang_v1.sales_commission_agent') }}:</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-info"></i>
                    </span>
                    <select name="sales_cmsn_agnt" id="sales_cmsn_agnt" class="form-control select2" style="width: 100%;">
                        @foreach($commission_agent_dropdown as $key => $value)
                        <option value="{{ $key }}" {{ $key == old('sales_cmsn_agnt', $business->sales_cmsn_agnt) ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label for="item_addition_method">{{ __('lang_v1.sales_item_addition_method') }}:</label>
                <select name="item_addition_method" id="item_addition_method" class="form-control select2" style="width: 100%;">
                    <option value="0" {{ old('item_addition_method', $business->item_addition_method) == 0 ? 'selected' : '' }}>{{ __('lang_v1.add_item_in_new_row') }}</option>
                    <option value="1" {{ old('item_addition_method', $business->item_addition_method) == 1 ? 'selected' : '' }}>{{ __('lang_v1.increase_item_qty') }}</option>
                </select>
            </div>
        </div>

        <div class="col-sm-8">
            <div class="form-group">
                <div class="checkbox">
                    <br>
                    <label>
                        <input type="checkbox" name="pos_settings[enable_msp]" value="1" class="input-icheck" {{ old('pos_settings.enable_msp', $pos_settings['enable_msp'] ?? false) ? 'checked' : '' }}>
                        {{ __('lang_v1.sale_price_is_minimum_sale_price') }}
                    </label>
                    @show_tooltip(__('lang_v1.minimum_sale_price_help'))
                </div>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="col-sm-4">
            <div class="form-group">
                <div class="checkbox">
                    <br>
                    <label>
                        <input type="checkbox" name="pos_settings[allow_overselling]" value="1" class="input-icheck" {{ old('pos_settings.allow_overselling', $pos_settings['allow_overselling'] ?? false) ? 'checked' : '' }}>
                        {{ __('lang_v1.allow_overselling') }}
                    </label>
                    @show_tooltip(__('lang_v1.allow_overselling_help'))
                </div>
            </div>
        </div>
    </div>
</div>
