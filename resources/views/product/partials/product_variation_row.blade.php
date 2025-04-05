
@if(!session('business.enable_price_tax')) 
    @php
        $default = 0;
        $class = 'hide';
    @endphp
@else
    @php
        $default = null;
        $class = '';
    @endphp
@endif

<tr class="variation_row">
    <td>
        <select name="product_variation[{{ $row_index }}][variation_template_id]" 
                class="form-control input-sm variation_template" 
                required>
            @foreach($variation_templates as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
        <input type="hidden" class="row_index" value="{{ $row_index }}">
    </td>

    <td>
        <table class="table table-condensed table-bordered blue-header variation_value_table">
            <thead>
            <tr>
                <th>@lang('product.sku') @show_tooltip(__('tooltip.sub_sku'))</th>
                <th>@lang('product.value')</th>
                <th class="{{ $class }}">
                    @lang('product.default_purchase_price')
                    <br/>
                    <span class="pull-left"><small><i>@lang('product.exc_of_tax')</i></small></span>
                    <span class="pull-right"><small><i>@lang('product.inc_of_tax')</i></small></span>
                </th>
                <th class="{{ $class }}">@lang('product.profit_percent')</th>
                <th class="{{ $class }}">
                    @lang('product.default_selling_price')
                    <br/>
                    <small><i><span class="dsp_label"></span></i></small>
                </th>
                <th><button type="button" class="btn btn-success btn-xs add_variation_value_row">+</button></th>
            </tr>
            </thead>

            <tbody>
            <tr>
                <td>
                    <input type="text" 
                           name="product_variation[{{ $row_index }}][variations][0][sub_sku]" 
                           class="form-control input-sm">
                </td>
                <td>
                    <input type="text" 
                           name="product_variation[{{ $row_index }}][variations][0][value]" 
                           class="form-control input-sm variation_value_name" 
                           required>
                </td>
                <td class="{{ $class }}">
                    <div class="col-sm-6">
                        <input type="text" 
                               name="product_variation[{{ $row_index }}][variations][0][default_purchase_price]" 
                               value="{{ $default }}" 
                               class="form-control input-sm variable_dpp input_number" 
                               placeholder="{{ __('product.exc_of_tax') }}" 
                               required>
                    </div>

                    <div class="col-sm-6">
                        <input type="text" 
                               name="product_variation[{{ $row_index }}][variations][0][dpp_inc_tax]" 
                               value="{{ $default }}" 
                               class="form-control input-sm variable_dpp_inc_tax input_number" 
                               placeholder="{{ __('product.inc_of_tax') }}" 
                               required>
                    </div>
                </td>
                <td class="{{ $class }}">
                    <input type="text" 
                           name="product_variation[{{ $row_index }}][variations][0][profit_percent]" 
                           value="{{ $profit_percent }}" 
                           class="form-control input-sm variable_profit_percent input_number" 
                           required>
                </td>
                <td class="{{ $class }}">
                    <input type="text" 
                           name="product_variation[{{ $row_index }}][variations][0][default_sell_price]" 
                           value="{{ $default }}" 
                           class="form-control input-sm variable_dsp input_number" 
                           placeholder="{{ __('product.exc_of_tax') }}" 
                           required>

                    <input type="text" 
                           name="product_variation[{{ $row_index }}][variations][0][sell_price_inc_tax]" 
                           value="{{ $default }}" 
                           class="form-control input-sm variable_dsp_inc_tax input_number" 
                           placeholder="{{ __('product.inc_of_tax') }}" 
                           required>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-xs remove_variation_value_row">-</button>
                    <input type="hidden" class="variation_row_index" value="0">
                </td>
            </tr>
            </tbody>
        </table>
    </td>
</tr>