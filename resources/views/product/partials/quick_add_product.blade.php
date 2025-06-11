<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <form action="{{ route('products.saveQuickProduct') }}" method="post" id="quick_add_product_form">
            @csrf

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalTitle">@lang('product.add_new_product')</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="name">{{ __('product.product_name') }}:*</label>
                            <input type="text"
                                name="name"
                                value="{{ $product_name }}"
                                class="form-control mousetrap"
                                required
                                autofocus
                                placeholder="{{ __('product.product_name') }}">

                            <select name="type" class="hide" id="type">
                                <option value="single">Single</option>
                                <option value="variable">Variable</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="brand_id">{{ __('product.brand') }}:</label>
                            <select name="brand_id" class="form-control select2">
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach($brands as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="unit_id">{{ __('product.unit') }}:*</label>
                            <select name="unit_id" class="form-control select2" required>
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach($units as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="category_id">{{ __('product.category') }}:</label>
                            <select name="category_id" class="form-control select2">
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach($categories as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="sku">{{ __('product.sku') }}: @show_tooltip(__('tooltip.sku'))</label>
                            <input type="text"
                                name="sku"
                                class="form-control"
                                placeholder="{{ __('product.sku') }}">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="barcode_type">{{ __('product.barcode_type') }}:*</label>
                            <select name="barcode_type" class="form-control select2" required>
                                @foreach($barcode_types as $key => $value)
                                <option value="{{ $key }}" {{ $key == 'C128' ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <br>
                            <label>
                                <input type="checkbox"
                                    name="enable_stock"
                                    value="1"
                                    class="input-icheck"
                                    id="enable_stock"
                                    checked>
                                <strong>@lang('product.manage_stock')</strong>
                            </label>
                            @show_tooltip(__('tooltip.enable_stock'))
                            <p class="help-block"><i>@lang('product.enable_stock_help')</i></p>
                        </div>
                    </div>

                    <div class="col-sm-4" id="alert_quantity_div">
                        <div class="form-group">
                            <label for="alert_quantity">{{ __('product.alert_quantity') }}:*</label>
                            <input type="number"
                                name="alert_quantity"
                                class="form-control"
                                required
                                placeholder="{{ __('product.alert_quantity') }}"
                                min="0">
                        </div>
                    </div>

                    @if(session('business.enable_product_expiry'))
                    @php
                    $expiry_period = session('business.expiry_type') == 'add_expiry' ? 12 : null;
                    $hide = session('business.expiry_type') == 'add_expiry';
                    @endphp

                    <div class="col-sm-4 {{ $hide ? 'hide' : '' }}">
                        <div class="form-group">
                            <div class="multi-input">
                                <label for="expiry_period">{{ __('product.expires_in') }}:</label><br>
                                <input type="text"
                                    name="expiry_period"
                                    value="{{ $expiry_period }}"
                                    class="form-control pull-left input_number"
                                    placeholder="{{ __('product.expiry_period') }}"
                                    style="width:60%;">

                                <select name="expiry_period_type"
                                    class="form-control select2 pull-left"
                                    style="width:40%;"
                                    id="expiry_period_type">
                                    <option value="months">{{ __('product.months') }}</option>
                                    <option value="days">{{ __('product.days') }}</option>
                                    <option value="">{{ __('product.not_applicable') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="clearfix"></div>

                    <div class="col-sm-8">
                        <div class="form-group">
                            <label for="product_description">{{ __('lang_v1.product_description') }}:</label>
                            <textarea name="product_description"
                                class="form-control">{{ !empty($duplicate_product->product_description) ? $duplicate_product->product_description : '' }}</textarea>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="tax">{{ __('product.applicable_tax') }}:</label>
                            <select name="tax" class="form-control select2">
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach($taxes as $key => $value)
                                <option value="{{ $key }}"
                                    {{ isset($tax_attributes[$key]) ? 'data-attributes=' . json_encode($tax_attributes[$key]) : '' }}>
                                    {{ $value }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="tax_type">{{ __('product.selling_price_tax_type') }}:*</label>
                            <select name="tax_type" class="form-control select2" required>
                                <option value="inclusive">{{ __('product.inclusive') }}</option>
                                <option value="exclusive">{{ __('product.exclusive') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4 hide">
                        <div class="checkbox">
                            <br>
                            <label>
                                <input type="checkbox"
                                    name="enable_sr_no"
                                    value="1"
                                    class="input-icheck">
                                <strong>@lang('lang_v1.enable_imei_or_sr_no')</strong>
                            </label>
                            @show_tooltip(__('lang_v1.tooltip_sr_no'))
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    @if(!empty($module_form_parts))
                    @foreach($module_form_parts as $key => $value)
                    @if(!empty($value['template_path']))
                    @php
                    $template_data = $value['template_data'] ?: [];
                    @endphp
                    @include($value['template_path'], $template_data)
                    @endif
                    @endforeach
                    @endif
                </div>

                <div class="row">
                    <div class="form-group col-sm-11 col-sm-offset-1">
                        @include('product.partials.single_product_form_part', ['profit_percent' => $default_profit_percent])
                    </div>
                </div>

                @if(!empty($product_for) && $product_for == 'pos')
                @include('product.partials.quick_product_opening_stock', ['locations' => $locations])
                @endif
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="submit_quick_product">
                    @lang('messages.save')
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    @lang('messages.close')
                </button>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

        CKEDITOR.config.height = 60;
        CKEDITOR.replace('product_description');

        $("form#quick_add_product_form").validate({
            rules: {
                sku: {
                    remote: {
                        url: "/products/check_product_sku",
                        type: "post",
                        data: {
                            sku: function() {
                                return $("#sku").val();
                            },
                            product_id: function() {
                                if ($('#product_id').length > 0) {
                                    return $('#product_id').val();
                                } else {
                                    return '';
                                }
                            },
                        }
                    }
                },
                expiry_period: {
                    required: {
                        depends: function(element) {
                            return ($('#expiry_period_type').val().trim() != '');
                        }
                    }
                }
            },
            messages: {
                sku: {
                    remote: LANG.sku_already_exists
                }
            },
            submitHandler: function(form) {

                var form = $("form#quick_add_product_form");
                var url = form.attr('action');
                form.find('button[type="submit"]').attr('disabled', true);
                $.ajax({
                    method: "POST",
                    url: url,
                    dataType: 'json',
                    data: $(form).serialize(),
                    success: function(data) {
                        $('.quick_add_product_modal').modal('hide');
                        if (data.success) {
                            toastr.success(data.msg);
                            if (typeof get_purchase_entry_row !== 'undefined') {
                                get_purchase_entry_row(data.product.id, 0);
                            }
                            $(document).trigger({
                                type: "quickProductAdded",
                                'product': data.product,
                                'variation': data.variation
                            });
                            $('#search_product').focus();
                        } else {
                            toastr.error(data.msg);
                        }
                    }
                });
                return false;
            }
        });
    });
</script>
