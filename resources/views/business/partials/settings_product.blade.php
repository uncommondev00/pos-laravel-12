<div class="pos-tab-content">
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label for="sku_prefix">{{ __('business.sku_prefix') }}:</label>
                <input type="text" name="sku_prefix" value="{{ old('sku_prefix', $business->sku_prefix) }}" class="form-control text-uppercase">
            </div>
        </div>

        @if(!config('constants.disable_expiry', true))
        <div class="col-sm-4">
            <label for="enable_product_expiry">{{ __('product.enable_product_expiry') }}:</label>
            @show_tooltip(__('lang_v1.tooltip_enable_expiry'))

            <div class="input-group">
                <span class="input-group-addon">
                    <input type="checkbox" name="enable_product_expiry" value="1" {{ $business->enable_product_expiry ? 'checked' : '' }}>
                </span>

                <select class="form-control" id="expiry_type" name="expiry_type" @if(!$business->enable_product_expiry) disabled @endif>
                    <option value="add_expiry" @if($business->expiry_type == 'add_expiry') selected @endif>
                        {{ __('lang_v1.add_expiry') }}
                    </option>
                    <option value="add_manufacturing" @if($business->expiry_type == 'add_manufacturing') selected @endif>
                        {{ __('lang_v1.add_manufacturing_auto_expiry') }}
                    </option>
                </select>
            </div>
        </div>

        <div class="col-sm-4 @if(!$business->enable_product_expiry) hide @endif" id="on_expiry_div">
            <div class="form-group">
                <div class="multi-input">
                    <label for="on_product_expiry">{{ __('lang_v1.on_product_expiry') }}:</label>
                    @show_tooltip(__('lang_v1.tooltip_on_product_expiry'))
                    <br>

                    <select name="on_product_expiry" class="form-control pull-left" style="width:60%;">
                        <option value="keep_selling" @if($business->on_product_expiry == 'keep_selling') selected @endif>
                            {{ __('lang_v1.keep_selling') }}
                        </option>
                        <option value="stop_selling" @if($business->on_product_expiry == 'stop_selling') selected @endif>
                            {{ __('lang_v1.stop_selling') }}
                        </option>
                    </select>

                    @php
                    $disabled = ($business->on_product_expiry == 'keep_selling') ? 'disabled' : '';
                    @endphp

                    <input type="number" name="stop_selling_before" value="{{ $business->stop_selling_before }}" class="form-control pull-left" placeholder="stop n days before" style="width:40%;" {{ $disabled }} required id="stop_selling_before">
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="enable_brand" value="1" {{ $business->enable_brand ? 'checked' : '' }} class="input-icheck">
                        {{ __('lang_v1.enable_brand') }}
                    </label>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="enable_category" value="1" {{ $business->enable_category ? 'checked' : '' }} class="input-icheck" id="enable_category">
                        {{ __('lang_v1.enable_category') }}
                    </label>
                </div>
            </div>
        </div>

        <div class="col-sm-4 enable_sub_category @if($business->enable_category != 1) hide @endif">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="enable_sub_category" value="1" {{ $business->enable_sub_category ? 'checked' : '' }} class="input-icheck" id="enable_sub_category">
                        {{ __('lang_v1.enable_sub_category') }}
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="enable_price_tax" value="1" {{ $business->enable_price_tax ? 'checked' : '' }} class="input-icheck">
                        {{ __('lang_v1.enable_price_tax') }}
                    </label>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label for="default_unit">{{ __('lang_v1.default_unit') }}:</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-balance-scale"></i>
                    </span>
                    <select name="default_unit" class="form-control select2" style="width: 100%;">
                        @foreach($units_dropdown as $key => $unit)
                        <option value="{{ $key }}" @if($business->default_unit == $key) selected @endif>
                            {{ $unit }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="col-sm-4">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="enable_racks" value="1" {{ $business->enable_racks ? 'checked' : '' }} class="input-icheck">
                        {{ __('lang_v1.enable_racks') }}
                    </label>
                    @show_tooltip(__('lang_v1.tooltip_enable_racks'))
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="enable_row" value="1" {{ $business->enable_row ? 'checked' : '' }} class="input-icheck">
                        {{ __('lang_v1.enable_row') }}
                    </label>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="enable_position" value="1" {{ $business->enable_position ? 'checked' : '' }} class="input-icheck">
                        {{ __('lang_v1.enable_position') }}
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
