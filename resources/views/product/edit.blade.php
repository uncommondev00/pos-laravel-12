@extends('layouts.app')
@section('title', __('product.edit_product'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang('product.edit_product')</h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    <form action="{{ route('products.update', $product->id) }}"
        method="POST"
        id="product_add_form"
        class="product_form"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" id="product_id" value="{{ $product->id }}">

        @component('components.widget', ['class' => 'box-primary'])
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="name">{{ __('product.product_name') }}:*</label>
                    <input type="text"
                        name="name"
                        value="{{ $product->name }}"
                        class="form-control"
                        required
                        placeholder="{{ __('product.product_name') }}">
                </div>
            </div>

            <div class="col-sm-4 {{ !session('business.enable_brand') ? 'hide' : '' }}">
                <div class="form-group">
                    <label for="brand_id">{{ __('product.brand') }}:</label>
                    <div class="input-group">
                        <select name="brand_id" class="form-control select2">
                            <option value="">{{ __('messages.please_select') }}</option>
                            @foreach($brands as $key => $value)
                            <option value="{{ $key }}" {{ $product->brand_id == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                            @endforeach
                        </select>
                        <span class="input-group-btn">
                            <button type="button"
                                class="btn btn-default bg-white btn-flat btn-modal"
                                {{ !auth()->user()->can('brand.create') ? 'disabled' : '' }}
                                data-href="{{ route('brands.create', ['quick_add' => true]) }}"
                                title="@lang('brand.add_brand')"
                                data-container=".view_modal">
                                <i class="fa fa-plus-circle text-primary fa-lg"></i>
                            </button>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="unit_id">{{ __('product.unit') }}:*</label>
                    <div class="input-group">
                        <select name="unit_id" class="form-control select2" required>
                            <option value="">{{ __('messages.please_select') }}</option>
                            @foreach($units as $key => $value)
                            <option value="{{ $key }}" {{ $product->unit_id == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                            @endforeach
                        </select>
                        <span class="input-group-btn">
                            <button type="button"
                                class="btn btn-default bg-white btn-flat quick_add_unit btn-modal"
                                {{ !auth()->user()->can('unit.create') ? 'disabled' : '' }}
                                data-href="{{ route('units.create', ['quick_add' => true]) }}"
                                title="@lang('unit.add_unit')"
                                data-container=".view_modal">
                                <i class="fa fa-plus-circle text-primary fa-lg"></i>
                            </button>
                        </span>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>

            <!-- Category Section -->
            <div class="col-sm-4 {{ !session('business.enable_category') ? 'hide' : '' }}">
                <div class="form-group">
                    <label for="category_id">{{ __('product.category') }}:</label>
                    <select name="category_id" class="form-control select2">
                        <option value="">{{ __('messages.please_select') }}</option>
                        @foreach($categories as $key => $value)
                        <option value="{{ $key }}" {{ $product->category_id == $key ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Sub Category Section -->
            <div class="col-sm-4 {{ !(session('business.enable_category') && session('business.enable_sub_category')) ? 'hide' : '' }}">
                <div class="form-group">
                    <label for="sub_category_id">{{ __('product.sub_category') }}:</label>
                    <select name="sub_category_id" class="form-control select2">
                        <option value="">{{ __('messages.please_select') }}</option>
                        @foreach($sub_categories as $key => $value)
                        <option value="{{ $key }}" {{ $product->sub_category_id == $key ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- SKU Section -->
            <div class="col-sm-4 {{ !(session('business.enable_category') && session('business.enable_sub_category')) ? 'hide' : '' }}">
                <div class="form-group">
                    <label for="sku">{{ __('product.sku') }}:* @show_tooltip(__('tooltip.sku'))</label>
                    <input type="text"
                        name="sku"
                        value="{{ $product->sku }}"
                        class="form-control"
                        placeholder="{{ __('product.sku') }}"
                        required
                        readonly>
                </div>
            </div>

            <!-- Continue with the rest of the form in the same pattern... -->

            <!-- For brevity, I'm showing the pattern. The rest of the form would follow
                   the same conversion pattern, replacing Form:: helpers with native HTML elements -->
            <!-- Barcode Type Section -->
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="barcode_type">{{ __('product.barcode_type') }}:*</label>
                    <select name="barcode_type" class="form-control select2" required>
                        <option value="">{{ __('messages.please_select') }}</option>
                        @foreach($barcode_types as $key => $value)
                        <option value="{{ $key }}" {{ $product->barcode_type == $key ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Stock Management Section -->
            <div class="col-sm-4">
                <div class="form-group">
                    <br>
                    <label>
                        <input type="checkbox"
                            name="enable_stock"
                            value="1"
                            class="input-icheck"
                            id="enable_stock"
                            {{ $product->enable_stock ? 'checked' : '' }}>
                        <strong>@lang('product.manage_stock')</strong>
                    </label>
                    @show_tooltip(__('tooltip.enable_stock'))
                    <p class="help-block"><i>@lang('product.enable_stock_help')</i></p>
                </div>
            </div>

            <!-- Alert Quantity Section -->
            <div class="col-sm-4" id="alert_quantity_div" style="{{ !$product->enable_stock ? 'display:none' : '' }}">
                <div class="form-group">
                    <label for="alert_quantity">
                        {{ __('product.alert_quantity') }}:*
                        @show_tooltip(__('tooltip.alert_quantity'))
                    </label>
                    <input type="number"
                        name="alert_quantity"
                        value="{{ $product->alert_quantity }}"
                        class="form-control"
                        required
                        placeholder="{{ __('product.alert_quantity') }}"
                        min="0">
                </div>
            </div>

            <!-- Product Description -->
            <div class="clearfix"></div>
            <div class="col-sm-8">
                <div class="form-group">
                    <label for="product_description">{{ __('lang_v1.product_description') }}:</label>
                    <textarea id="product_description" name="product_description"
                        class="form-control">{{ $product->product_description }}</textarea>
                </div>
            </div>

            <!-- Product Image -->
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="image">{{ __('lang_v1.product_image') }}:</label>
                    <input type="file"
                        name="image"
                        id="upload_image"
                        accept="image/*">
                    <small>
                        <p class="help-block">
                            @lang('purchase.max_file_size', ['size' => (config('constants.document_size_limit') / 1000000)])
                            @lang('lang_v1.aspect_ratio_should_be_1_1')
                            @if(!empty($product->image))
                            <br> @lang('lang_v1.previous_image_will_be_replaced')
                            @endif
                        </p>
                    </small>
                </div>
            </div>

            <!-- Product Expiry Section -->
            @if(session('business.enable_product_expiry'))
            @php
            $expiry_period = session('business.expiry_type') == 'add_expiry' ? 12 : null;
            $hide = session('business.expiry_type') == 'add_expiry';
            $disabled = empty($product->expiry_period_type) || empty($product->enable_stock);
            $disabled_period = empty($product->enable_stock);
            @endphp

            <div class="col-sm-4 {{ $hide ? 'hide' : '' }}">
                <div class="form-group">
                    <div class="multi-input">
                        <label for="expiry_period">{{ __('product.expires_in') }}:</label><br>
                        <input type="text"
                            name="expiry_period"
                            value="{{ @num_format($product->expiry_period) }}"
                            class="form-control pull-left input_number"
                            placeholder="{{ __('product.expiry_period') }}"
                            style="width:60%;"
                            {{ $disabled ? 'disabled' : '' }}>

                        <select name="expiry_period_type"
                            class="form-control select2 pull-left"
                            style="width:40%;"
                            id="expiry_period_type"
                            {{ $disabled_period ? 'disabled' : '' }}>
                            <option value="months" {{ $product->expiry_period_type == 'months' ? 'selected' : '' }}>
                                {{ __('product.months') }}
                            </option>
                            <option value="days" {{ $product->expiry_period_type == 'days' ? 'selected' : '' }}>
                                {{ __('product.days') }}
                            </option>
                            <option value="" {{ $product->expiry_period_type == '' ? 'selected' : '' }}>
                                {{ __('product.not_applicable') }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            @endif

            <!-- Serial Number Section -->
            <div class="col-sm-4">
                <div class="checkbox">
                    <label>
                        <input type="checkbox"
                            name="enable_sr_no"
                            value="1"
                            class="input-icheck"
                            {{ $product->enable_sr_no ? 'checked' : '' }}>
                        <strong>@lang('lang_v1.enable_imei_or_sr_no')</strong>
                    </label>
                    @show_tooltip(__('lang_v1.tooltip_sr_no'))
                </div>
            </div>

            <!-- Rack Details Section -->
            @if(session('business.enable_racks') || session('business.enable_row') || session('business.enable_position'))
            <div class="col-md-12">
                <h4>
                    @lang('lang_v1.rack_details'):
                    @show_tooltip(__('lang_v1.tooltip_rack_details'))
                </h4>
            </div>
            @foreach($business_locations as $id => $location)
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="rack_{{ $id }}">{{ $location }}:</label>

                    @if(!empty($rack_details[$id]))
                    @if(session('business.enable_racks'))
                    <input type="text"
                        name="product_racks_update[{{ $id }}][rack]"
                        value="{{ $rack_details[$id]['rack'] }}"
                        class="form-control"
                        id="rack_{{ $id }}">
                    @endif

                    @if(session('business.enable_row'))
                    <input type="text"
                        name="product_racks_update[{{ $id }}][row]"
                        value="{{ $rack_details[$id]['row'] }}"
                        class="form-control">
                    @endif

                    @if(session('business.enable_position'))
                    <input type="text"
                        name="product_racks_update[{{ $id }}][position]"
                        value="{{ $rack_details[$id]['position'] }}"
                        class="form-control">
                    @endif
                    @else
                    <input type="text"
                        name="product_racks[{{ $id }}][rack]"
                        class="form-control"
                        id="rack_{{ $id }}"
                        placeholder="{{ __('lang_v1.rack') }}">

                    <input type="text"
                        name="product_racks[{{ $id }}][row]"
                        class="form-control"
                        placeholder="{{ __('lang_v1.row') }}">

                    <input type="text"
                        name="product_racks[{{ $id }}][position]"
                        class="form-control"
                        placeholder="{{ __('lang_v1.position') }}">
                    @endif
                </div>
            </div>
            @endforeach
            @endif

            <!-- Weight -->
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="weight">{{ __('lang_v1.weight') }}:</label>
                    <input type="text"
                        name="weight"
                        value="{{ $product->weight }}"
                        class="form-control"
                        placeholder="{{ __('lang_v1.weight') }}">
                </div>
            </div>

            <!-- Custom Fields -->
            <div class="clearfix"></div>
            @for($i = 1; $i <= 4; $i++)
                <div class="col-sm-3">
                <div class="form-group">
                    <label for="product_custom_field{{ $i }}">
                        {{ __('lang_v1.product_custom_field' . $i) }}:
                    </label>
                    <input type="text"
                        name="product_custom_field{{ $i }}"
                        value="{{ $product->{'product_custom_field' . $i} }}"
                        class="form-control"
                        placeholder="{{ __('lang_v1.product_custom_field' . $i) }}">
                </div>
        </div>
        @endfor

        @include('layouts.partials.module_form_part')
        </div>
        </div>
        @endcomponent

        @component('components.widget', ['class' => 'box-primary'])
        <div class="row">
            <!-- Tax Section -->
            <div class="col-sm-4 {{ !session('business.enable_price_tax') ? 'hide' : '' }}">
                <div class="form-group">
                    <label for="tax">{{ __('product.applicable_tax') }}:</label>
                    <select name="tax" class="form-control select2">
                        <option value="">{{ __('messages.please_select') }}</option>
                        @foreach($taxes as $key => $value)
                        <option value="{{ $key }}"
                            {{ $product->tax == $key ? 'selected' : '' }}
                            @if(isset($tax_attributes[$key]))
                            data-attributes="{{ json_encode($tax_attributes[$key]) }}"
                            @endif>
                            {{ $value }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Tax Type Section -->
            <div class="col-sm-4 {{ !session('business.enable_price_tax') ? 'hide' : '' }}">
                <div class="form-group">
                    <label for="tax_type">{{ __('product.selling_price_tax_type') }}:*</label>
                    <select name="tax_type" class="form-control select2" required>
                        <option value="inclusive" {{ $product->tax_type == 'inclusive' ? 'selected' : '' }}>
                            {{ __('product.inclusive') }}
                        </option>
                        <option value="exclusive" {{ $product->tax_type == 'exclusive' ? 'selected' : '' }}>
                            {{ __('product.exclusive') }}
                        </option>
                    </select>
                </div>
            </div>

            <div class="clearfix"></div>

            <!-- Product Type Section -->
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="type">
                        {{ __('product.product_type') }}:*
                        @show_tooltip(__('tooltip.product_type'))
                    </label>
                    <select name="type"
                        class="form-control select2"
                        required
                        disabled
                        data-action="edit"
                        data-product_id="{{ $product->id }}">
                        <option value="single" {{ $product->type == 'single' ? 'selected' : '' }}>Single</option>
                        <option value="variable" {{ $product->type == 'variable' ? 'selected' : '' }}>Variable</option>
                    </select>
                </div>
            </div>

            <!-- Product Form Part -->
            <div class="form-group col-sm-11 col-sm-offset-1" id="product_form_part">
                <!-- Dynamic content will be loaded here -->
            </div>

            <!-- Hidden Fields -->
            <input type="hidden" id="variation_counter" value="0">
            <input type="hidden" id="default_profit_percent" value="{{ $default_profit_percent }}">
        </div>
        @endcomponent

        <!-- Form buttons -->
        <div class="row">
            <input type="hidden" name="submit_type" id="submit_type">
            <div class="col-sm-12">
                <div class="text-center">
                    <div class="btn-group">
                        @if($selling_price_group_count)
                        <button type="submit"
                            value="submit_n_add_selling_prices"
                            class="btn btn-warning submit_product_form">
                            @lang('lang_v1.save_n_add_selling_price_group_prices')
                        </button>
                        @endif

                        <button type="submit"
                            id="opening_stock_button"
                            value="update_n_edit_opening_stock"
                            class="btn bg-purple submit_product_form"
                            {{ empty($product->enable_stock) ? 'disabled' : '' }}>
                            @lang('lang_v1.update_n_edit_opening_stock')
                        </button>

                        <button type="submit"
                            value="save_n_add_another"
                            class="btn bg-maroon submit_product_form">
                            @lang('lang_v1.update_n_add_another')
                        </button>

                        <button type="submit"
                            value="submit"
                            class="btn btn-primary submit_product_form">
                            @lang('messages.update')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>
<!-- /.content -->

@endsection

@section('javascript')
<script src="{{ asset('js/product.js?v=' . $asset_v) }}"></script>
@endsection
