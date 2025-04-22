<!-- Purchase related settings -->
<div class="pos-tab-content">
    <div class="row">
        @if(!config('constants.disable_purchase_in_other_currency', true))
        <div class="col-sm-4">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="purchase_in_diff_currency" value="1" class="input-icheck" id="purchase_in_diff_currency" {{ $business->purchase_in_diff_currency ? 'checked' : '' }}>
                        {{ __('purchase.allow_purchase_different_currency') }}
                    </label>
                    @show_tooltip(__('tooltip.purchase_different_currency'))
                </div>
            </div>
        </div>

        <div class="col-sm-4 @if($business->purchase_in_diff_currency != 1) hide @endif" id="settings_purchase_currency_div">
            <div class="form-group">
                <label for="purchase_currency_id">{{ __('purchase.purchase_currency') }}:</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-money"></i>
                    </span>
                    <select name="purchase_currency_id" class="form-control select2" required style="width:100% !important">
                        <option value="">{{ __('business.currency') }}</option>
                        @foreach($currencies as $currencyId => $currency)
                        <option value="{{ $currencyId }}" {{ $currencyId == $business->purchase_currency_id ? 'selected' : '' }}>
                            {{ $currency }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="col-sm-4 @if($business->purchase_in_diff_currency != 1) hide @endif" id="settings_currency_exchange_div">
            <div class="form-group">
                <label for="p_exchange_rate">{{ __('purchase.p_exchange_rate') }}:</label>
                @show_tooltip(__('tooltip.currency_exchange_factor'))
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-info"></i>
                    </span>
                    <input type="number" name="p_exchange_rate" value="{{ old('p_exchange_rate', $business->p_exchange_rate) }}" class="form-control" placeholder="{{ __('business.p_exchange_rate') }}" required step="0.001">
                </div>
            </div>
        </div>
        @endif

        <div class="clearfix"></div>

        <div class="col-sm-4">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="enable_editing_product_from_purchase" value="1" class="input-icheck" {{ $business->enable_editing_product_from_purchase ? 'checked' : '' }}>
                        {{ __('lang_v1.enable_editing_product_from_purchase') }}
                    </label>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="enable_purchase_status" value="1" class="input-icheck" id="enable_purchase_status" {{ $business->enable_purchase_status ? 'checked' : '' }}>
                        {{ __('lang_v1.enable_purchase_status') }}
                    </label>
                    @show_tooltip(__('lang_v1.tooltip_enable_purchase_status'))
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="enable_lot_number" value="1" class="input-icheck" id="enable_lot_number" {{ $business->enable_lot_number ? 'checked' : '' }}>
                        {{ __('lang_v1.enable_lot_number') }}
                    </label>
                    @show_tooltip(__('lang_v1.tooltip_enable_lot_number'))
                </div>
            </div>
        </div>
    </div>
</div>
