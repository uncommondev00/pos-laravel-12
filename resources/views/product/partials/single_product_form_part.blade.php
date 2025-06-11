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

<div class="col-sm-9"><br>
  <div class="table-responsive">
    <table class="table table-bordered add-product-price-table table-condensed {{$class}}">
      <tr>
        <th>@lang('product.default_purchase_price')</th>
        <th>@lang('product.profit_percent') @show_tooltip(__('tooltip.profit_percent'))</th>
        <th>@lang('product.default_selling_price')</th>
      </tr>
      <tr>
        <td>
          <div class="col-sm-5">
            <label for="single_dpp">{{ trans('product.exc_of_tax') }}:*</label>

            <input type="text"
              name="single_dpp"
              id="single_dpp"
              value="{{ $default }}"
              class="form-control input-sm dpp input_number"
              placeholder="{{ __('product.exc_of_tax') }}"
              required>
          </div>

          <div class="col-sm-5">
            <label for="single_dpp_inc_tax">{{ trans('product.inc_of_tax') }}:*</label>

            <input type="text"
              name="single_dpp_inc_tax"
              id="single_dpp_inc_tax"
              value="{{ $default }}"
              class="form-control input-sm dpp_inc_tax input_number"
              placeholder="{{ __('product.inc_of_tax') }}"
              required>
          </div>

          <div class="col-sm-2">
            <label for="add_p">{{ trans('Add') }}</label>

            <input type="text"
              name="add_p"
              id="add_p"
              value="{{ $default }}"
              class="form-control input-sm add_p input_number"
              required>
          </div>
        </td>

        <td>
          <br />
          <input type="text"
            name="profit_percent"
            id="profit_percent"
            value="{{ @num_format($profit_percent) }}"
            class="form-control input-sm input_number"
            required>
        </td>

        <td>
          <label><span class="dsp_label">{{ __('product.exc_of_tax') }}</span></label>

          <input type="text"
            name="single_dsp"
            id="single_dsp"
            value="{{ $default }}"
            class="form-control input-sm dsp input_number"
            placeholder="{{ __('product.exc_of_tax') }}"
            required>

          <input type="text"
            name="single_dsp_inc_tax"
            id="single_dsp_inc_tax"
            value="{{ $default }}"
            class="form-control input-sm hide input_number"
            placeholder="{{ __('product.inc_of_tax') }}"
            required>
        </td>
      </tr>
    </table>
  </div>
</div>
