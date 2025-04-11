<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">{{ $product->product_name }} - {{ $product->sub_sku }}</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <!-- Unit Price -->
                <div class="form-group col-xs-12 @if(!auth()->user()->can('edit_product_price_from_sale_screen')) hide @endif">
                    <label for="unit_price_{{ $row_count }}">@lang('sale.unit_price')</label>
                    <input type="text" 
                        name="products[{{ $row_count }}][unit_price]" 
                        id="unit_price_{{ $row_count }}"
                        class="form-control pos_unit_price input_number mousetrap" 
                        value="{{ @num_format(!empty($product->unit_price_before_discount) ? $product->unit_price_before_discount : $product->default_sell_price) }}" 
                        readonly>
                </div>

                @php
                    $dis_val = 0;
                    if (isset($_COOKIE['discount'])) {
                        $main_discount = $_COOKIE["discount"];
                    } else {
                        $main_discount = 0;
                    }

                    if (isset($_GET['name'])) {
                        $cokie = $_GET['name'];
                        if (isset($_COOKIE[$cokie])) {
                            $coc = $_COOKIE[$cokie];
                            $dis_val = $coc;
                        } else {
                            $coc = 0;
                            $dis_val = $coc;    
                        }
                    } else {
                        $dis_val = $main_discount;
                    }

                    $item_discount = $dis_val;
                    $discount_type = !empty($product->line_discount_type) ? $product->line_discount_type : 'percentage';
                    $discount_amount = !empty($product->line_discount_amount) ? $product->line_discount_amount : $item_discount;
                    
                    if(!empty($discount)) {
                        $discount_type = $discount->discount_type;
                        $discount_amount = $discount->discount_amount;
                    }
                @endphp

                @if(!empty($discount))
                    <input type="hidden" 
                        name="products[{{ $row_count }}][discount_id]" 
                        id="discount_id_{{ $row_count }}"
                        value="{{ $discount->id }}">
                @endif

                <!-- Discount Type -->
                <div class="form-group col-xs-12 col-sm-6 hide @if(!auth()->user()->can('edit_product_discount_from_sale_screen')) hide @endif">
                    <label for="line_discount_type_{{ $row_count }}">@lang('sale.discount_type')</label>
                    <select name="products[{{ $row_count }}][line_discount_type]" 
                        id="line_discount_type_{{ $row_count }}"
                        class="form-control row_discount_type">
                        <option value="percentage" {{ $discount_type == 'percentage' ? 'selected' : '' }}>
                            @lang('lang_v1.percentage')
                        </option>
                        <option value="fixed" {{ $discount_type == 'fixed' ? 'selected' : '' }}>
                            @lang('lang_v1.fixed')
                        </option>
                    </select>
                    @if(!empty($discount))
                        <p class="help-block">
                            {!! __('lang_v1.applied_discount_text', [
                                'discount_name' => $discount->name, 
                                'starts_at' => $discount->formated_starts_at, 
                                'ends_at' => $discount->formated_ends_at
                            ]) !!}
                        </p>
                    @endif
                </div>

                <!-- Discount Amount -->
                <div class="form-group col-xs-12 col-sm-6 @if(!auth()->user()->can('edit_product_discount_from_sale_screen')) hide @endif">
                    <label for="line_discount_amount_{{ $row_count }}">@lang('sale.discount_amount')</label>
                    <input type="text" 
                        name="products[{{ $row_count }}][line_discount_amount]" 
                        id="line_discount_amount_{{ $row_count }}"
                        class="form-control input_number row_discount_amount" 
                        value="{{ @num_format($discount_amount) }}"
                        readonly>
                </div>

                <!-- Tax -->
                <div class="form-group col-xs-12 {{ $hide_tax }}">
                    <label for="tax_id_{{ $row_count }}">@lang('sale.tax')</label>
                    <input type="hidden" 
                        name="products[{{ $row_count }}][item_tax]" 
                        id="item_tax_{{ $row_count }}"
                        class="item_tax" 
                        value="{{ @num_format($item_tax) }}">
                    
                    <select name="products[{{ $row_count }}][tax_id]" 
                        id="tax_id_{{ $row_count }}"
                        class="form-control tax_id">
                        <option value="">Select</option>
                        @foreach($tax_dropdown['tax_rates'] as $tax_key => $tax_value)
                            <option value="{{ $tax_key }}" 
                                {{ $tax_id == $tax_key ? 'selected' : '' }}
                                    data-{{ $tax_key }}="{{ $tax_value }}"
                            >
                                {{ $tax_value }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">
                @lang('messages.close')
            </button>
        </div>
    </div>
</div>