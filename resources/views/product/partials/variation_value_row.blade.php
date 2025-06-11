@php
$variation_name = !empty($variation_name) ? $variation_name : null;
$variation_value_id = !empty($variation_value_id) ? $variation_value_id : null;

$name = (empty($row_type) || $row_type == 'add') ? 'product_variation' : 'product_variation_edit';

$readonly = !empty($variation_value_id) ? 'readonly' : '';
@endphp

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

<tr>
    <td>
        <input type="text"
            name="{{ $name }}[{{ $variation_index }}][variations][{{ $value_index }}][sub_sku]"
            class="form-control input-sm">

        <input type="hidden"
            name="{{ $name }}[{{ $variation_index }}][variations][{{ $value_index }}][variation_value_id]"
            value="{{ $variation_value_id }}">
    </td>

    <td>
        <input type="text"
            name="{{ $name }}[{{ $variation_index }}][variations][{{ $value_index }}][value]"
            value="{{ $variation_name }}"
            class="form-control input-sm variation_value_name"
            required
            {{ $readonly }}>
    </td>

    <td class="{{ $class }}">
        <div class="col-sm-6">
            <input type="text"
                name="{{ $name }}[{{ $variation_index }}][variations][{{ $value_index }}][default_purchase_price]"
                value="{{ $default }}"
                class="form-control input-sm variable_dpp input_number"
                placeholder="{{ __('product.exc_of_tax') }}"
                required>
        </div>

        <div class="col-sm-6">
            <input type="text"
                name="{{ $name }}[{{ $variation_index }}][variations][{{ $value_index }}][dpp_inc_tax]"
                value="{{ $default }}"
                class="form-control input-sm variable_dpp_inc_tax input_number"
                placeholder="{{ __('product.inc_of_tax') }}"
                required>
        </div>
    </td>

    <td class="{{ $class }}">
        <input type="text"
            name="{{ $name }}[{{ $variation_index }}][variations][{{ $value_index }}][profit_percent]"
            value="{{ $profit_percent }}"
            class="form-control input-sm variable_profit_percent input_number"
            required>
    </td>

    <td class="{{ $class }}">
        <input type="text"
            name="{{ $name }}[{{ $variation_index }}][variations][{{ $value_index }}][default_sell_price]"
            value="{{ $default }}"
            class="form-control input-sm variable_dsp input_number"
            placeholder="{{ __('product.exc_of_tax') }}"
            required>

        <input type="text"
            name="{{ $name }}[{{ $variation_index }}][variations][{{ $value_index }}][sell_price_inc_tax]"
            value="{{ $default }}"
            class="form-control input-sm variable_dsp_inc_tax input_number"
            placeholder="{{ __('product.inc_of_tax') }}"
            required>
    </td>

    <td>
        <button type="button" class="btn btn-danger btn-xs remove_variation_value_row">-</button>
        <input type="hidden"
            class="variation_row_index"
            value="{{ $value_index }}">
    </td>
</tr>
