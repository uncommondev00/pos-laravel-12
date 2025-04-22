@extends('layouts.app')
@section('title', __('invoice.add_invoice_layout'))

@section('content')
<style type="text/css">



</style>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>@lang('invoice.add_invoice_layout')</h1>
</section>

<!-- Main content -->
<section class="content">

  <form action="{{ route('invoice-layouts.store') }}" method="POST" id="add_invoice_layout_form" enctype="multipart/form-data">
    @csrf

    <div class="box box-solid">
      <div class="box-body">
        <div class="row">

          <div class="col-sm-6">
            <div class="form-group">
              <label for="name">{{ __('invoice.layout_name') }}:* </label>
              <input type="text" name="name" class="form-control" required placeholder="{{ __('invoice.layout_name') }}" value="{{ old('name') }}">
            </div>
          </div>

          <div class="col-sm-6">
            <div class="form-group">
              <label for="design">{{ __('lang_v1.design') }}:* </label>
              <select name="design" class="form-control">
                @foreach($designs as $key => $value)
                <option value="{{ $key }}" {{ old('design', 'classic') == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
              </select>
              <span class="help-block">@lang('lang_v1.design_help')</span>
            </div>

            <div class="form-group hide" id="columnize-taxes">
              <div class="col-md-3">
                <input type="text" class="form-control" name="table_tax_headings[]" required="required" placeholder="tax 1 name" disabled>
                @show_tooltip(__('lang_v1.tooltip_columnize_taxes_heading'))
              </div>
              <div class="col-md-3">
                <input type="text" class="form-control" name="table_tax_headings[]" placeholder="tax 2 name" disabled>
              </div>
              <div class="col-md-3">
                <input type="text" class="form-control" name="table_tax_headings[]" placeholder="tax 3 name" disabled>
              </div>
              <div class="col-md-3">
                <input type="text" class="form-control" name="table_tax_headings[]" placeholder="tax 4 name" disabled>
              </div>
            </div>

          </div>

          <!-- Logo -->
          <div class="col-sm-6">
            <div class="form-group">
              <label for="logo">{{ __('invoice.invoice_logo') }}:</label>
              <input type="file" name="logo">
              <span class="help-block">@lang('lang_v1.invoice_logo_help', ['max_size' => '1 MB'])</span>
            </div>
          </div>

          <div class="col-sm-6">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="show_logo" class="input-icheck" value="1" {{ old('show_logo') ? 'checked' : '' }}> @lang('invoice.show_logo')
                </label>
              </div>
            </div>
          </div>

          <div class="col-sm-12">
            <div class="form-group">
              <label for="header_text">{{ __('invoice.header_text') }}:</label>
              <textarea name="header_text" class="form-control" placeholder="{{ __('invoice.header_text') }}" rows="3">{{ old('header_text') }}</textarea>
            </div>
          </div>

          <div class="clearfix"></div>

          <div class="col-sm-3">
            <div class="form-group">
              <label for="sub_heading_line1">{{ __('lang_v1.sub_heading_line', ['_number_' => 1]) }}:</label>
              <input type="text" name="sub_heading_line1" class="form-control" placeholder="{{ __('lang_v1.sub_heading_line', ['_number_' => 1]) }}" value="{{ old('sub_heading_line1') }}">
            </div>
          </div>

          <div class="col-sm-3">
            <div class="form-group">
              <label for="sub_heading_line2">{{ __('lang_v1.sub_heading_line', ['_number_' => 2]) }}:</label>
              <input type="text" name="sub_heading_line2" class="form-control" placeholder="{{ __('lang_v1.sub_heading_line', ['_number_' => 2]) }}" value="{{ old('sub_heading_line2') }}">
            </div>
          </div>

          <div class="col-sm-3">
            <div class="form-group">
              <label for="sub_heading_line3">{{ __('lang_v1.sub_heading_line', ['_number_' => 3]) }}:</label>
              <input type="text" name="sub_heading_line3" class="form-control" placeholder="{{ __('lang_v1.sub_heading_line', ['_number_' => 3]) }}" value="{{ old('sub_heading_line3') }}">
            </div>
          </div>

          <div class="col-sm-3">
            <div class="form-group">
              <label for="sub_heading_line4">{{ __('lang_v1.sub_heading_line', ['_number_' => 4]) }}:</label>
              <input type="text" name="sub_heading_line4" class="form-control" placeholder="{{ __('lang_v1.sub_heading_line', ['_number_' => 4]) }}" value="{{ old('sub_heading_line4') }}">
            </div>
          </div>

          <div class="clearfix"></div>

          <div class="col-sm-3">
            <div class="form-group">
              <label for="sub_heading_line5">{{ __('lang_v1.sub_heading_line', ['_number_' => 5]) }}:</label>
              <input type="text" name="sub_heading_line5" class="form-control" placeholder="{{ __('lang_v1.sub_heading_line', ['_number_' => 5]) }}" value="{{ old('sub_heading_line5') }}">
            </div>
          </div>

        </div>
      </div>
    </div>


    <div class="box box-solid">
      <div class="box-body">
        <div class="row">
          <div class="col-sm-3">
            <div class="form-group">
              <label for="invoice_heading">{{ __('invoice.invoice_heading') }}:</label>
              <input type="text" name="invoice_heading" value="Invoice" class="form-control" placeholder="{{ __('invoice.invoice_heading') }}">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="invoice_heading_not_paid">{{ __('invoice.invoice_heading_not_paid') }}:</label>
              <input type="text" name="invoice_heading_not_paid" class="form-control" placeholder="{{ __('invoice.invoice_heading_not_paid') }}">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="invoice_heading_paid">{{ __('invoice.invoice_heading_paid') }}:</label>
              <input type="text" name="invoice_heading_paid" class="form-control" placeholder="{{ __('invoice.invoice_heading_paid') }}">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="quotation_heading">{{ __('lang_v1.quotation_heading') }}:</label>
              @show_tooltip(__('lang_v1.tooltip_quotation_heading'))
              <input type="text" name="quotation_heading" value="Quotation" class="form-control" placeholder="{{ __('lang_v1.quotation_heading') }}">
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="invoice_no_prefix">{{ __('invoice.invoice_no_prefix') }}:</label>
              <input type="text" name="invoice_no_prefix" value="Invoice No." class="form-control" placeholder="{{ __('invoice.invoice_no_prefix') }}">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="quotation_no_prefix">{{ __('lang_v1.quotation_no_prefix') }}:</label>
              <input type="text" name="quotation_no_prefix" value="Quotation No." class="form-control" placeholder="{{ __('lang_v1.quotation_no_prefix') }}">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="date_label">{{ __('lang_v1.date_label') }}:</label>
              <input type="text" name="date_label" value="Date" class="form-control" placeholder="{{ __('lang_v1.date_label') }}">
            </div>
          </div>

          <div class="col-sm-3">
            <div class="form-group">
              <label for="date_time_format">{{ __('lang_v1.date_time_format') }}:</label>
              <input type="text" name="date_time_format" class="form-control" placeholder="{{ __('lang_v1.date_time_format') }}">
              <p class="help-block">{{ __('lang_v1.date_time_format_help') }}</p>
            </div>
          </div>

          <div class="col-sm-3">
            <div class="form-group">
              <label for="sales_person_label">{{ __('lang_v1.sales_person_label') }}:</label>
              <input type="text" name="sales_person_label" class="form-control" placeholder="{{ __('lang_v1.sales_person_label') }}">
            </div>
          </div>
          <div class="clearfix"></div>

          <div class="col-sm-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="show_business_name" value="1" class="input-icheck"> @lang('invoice.show_business_name')
                </label>
              </div>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="show_location_name" value="1" checked class="input-icheck"> @lang('invoice.show_location_name')
                </label>
              </div>
            </div>
          </div>

          <div class="col-sm-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="show_sales_person" value="1" class="input-icheck"> @lang('lang_v1.show_sales_person')
                </label>
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="col-sm-12">
            <h4>@lang('lang_v1.fields_for_customer_details'):</h4>
          </div>
          <div class="clearfix"></div>
          <div class="col-sm-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="show_customer" value="1" checked class="input-icheck"> @lang('invoice.show_customer')
                </label>
              </div>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="customer_label">{{ __('invoice.customer_label') }}:</label>
              <input type="text" name="customer_label" value="Customer" class="form-control" placeholder="{{ __('invoice.customer_label') }}">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="show_client_id" value="1" class="input-icheck"> @lang('lang_v1.show_client_id')
                </label>
              </div>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="client_id_label">{{ __('lang_v1.client_id_label') }}:</label>
              <input type="text" name="client_id_label" class="form-control" placeholder="{{ __('lang_v1.client_id_label') }}">
            </div>
          </div>

          <div class="col-sm-3">
            <div class="form-group">
              <label for="client_tax_label">{{ __('lang_v1.client_tax_label') }}:</label>
              <input type="text" name="client_tax_label" class="form-control" placeholder="{{ __('lang_v1.client_tax_label') }}">
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="col-sm-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="contact_custom_fields[]" value="custom_field1" class="input-icheck"> @lang('lang_v1.custom_field', ['number' => 1])
                </label>
              </div>
            </div>
          </div>

          <div class="col-sm-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="contact_custom_fields[]" value="custom_field2" class="input-icheck"> @lang('lang_v1.custom_field', ['number' => 2])
                </label>
              </div>
            </div>
          </div>

          <div class="col-sm-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="contact_custom_fields[]" value="custom_field3" class="input-icheck"> @lang('lang_v1.custom_field', ['number' => 3])
                </label>
              </div>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="contact_custom_fields[]" value="custom_field4" class="input-icheck"> @lang('lang_v1.custom_field', ['number' => 4])
                </label>
              </div>
            </div>
          </div>

          <div class="clearfix"></div>
          <div class="col-sm-12">
            <h4>@lang('invoice.fields_to_be_shown_in_address'):</h4>
          </div>
          <div class="clearfix"></div>
          <div class="col-sm-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="show_landmark" value="1" checked class="input-icheck"> @lang('business.landmark')
                </label>
              </div>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="show_city" value="1" checked class="input-icheck"> @lang('business.city')
                </label>
              </div>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="show_state" value="1" checked class="input-icheck"> @lang('business.state')
                </label>
              </div>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="show_zip_code" value="1" checked class="input-icheck"> @lang('business.zip_code')
                </label>
              </div>
            </div>
          </div>

          <div class="clearfix"></div>
          <div class="col-sm-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="show_country" value="1" checked class="input-icheck"> @lang('business.country')
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


    <div class="box box-solid">
      <div class="box-body">
        <div class="row">
          <div class="col-sm-3">
            <div class="form-group">
              <label for="table_product_label">@lang('lang_v1.product_label'):</label>
              <input type="text" name="table_product_label" value="Product" class="form-control" placeholder="@lang('lang_v1.product_label')">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="table_qty_label">@lang('lang_v1.qty_label'):</label>
              <input type="text" name="table_qty_label" value="Quantity" class="form-control" placeholder="@lang('lang_v1.qty_label')">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="table_unit_price_label">@lang('lang_v1.unit_price_label'):</label>
              <input type="text" name="table_unit_price_label" value="Unit Price" class="form-control" placeholder="@lang('lang_v1.unit_price_label')">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="table_subtotal_label">@lang('lang_v1.subtotal_label'):</label>
              <input type="text" name="table_subtotal_label" value="Subtotal" class="form-control" placeholder="@lang('lang_v1.subtotal_label')">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="cat_code_label">@lang('lang_v1.cat_code_label'):</label>
              <input type="text" name="cat_code_label" value="HSN" class="form-control" placeholder="HSN or Category Code">
            </div>
          </div>

          <div class="col-sm-12">
            <h4>@lang('lang_v1.product_details_to_be_shown'):</h4>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="show_brand" value="1" class="input-icheck"> @lang('lang_v1.show_brand')
                </label>
              </div>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="show_sku" value="1" checked class="input-icheck"> @lang('lang_v1.show_sku')
                </label>
              </div>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="show_cat_code" value="1" class="input-icheck"> @lang('lang_v1.show_cat_code')
                </label>
              </div>
            </div>
          </div>

          <div class="col-sm-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="show_sale_description" value="1" class="input-icheck"> @lang('lang_v1.show_sale_description')
                </label>
                <p class="help-block">@lang('lang_v1.product_imei_or_sn')</p>
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="col-sm-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="product_custom_fields[]" value="product_custom_field1" class="input-icheck"> @lang('lang_v1.product_custom_field1')
                </label>
              </div>
            </div>
          </div>

          <div class="col-sm-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="product_custom_fields[]" value="product_custom_field2" class="input-icheck"> @lang('lang_v1.product_custom_field2')
                </label>
              </div>
            </div>
          </div>

          <div class="col-sm-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="product_custom_fields[]" value="product_custom_field3" class="input-icheck"> @lang('lang_v1.product_custom_field3')
                </label>
              </div>
            </div>
          </div>

          <div class="col-sm-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="product_custom_fields[]" value="product_custom_field4" class="input-icheck"> @lang('lang_v1.product_custom_field4')
                </label>
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
          @if(request()->session()->get('business.enable_product_expiry') == 1)
          <div class="col-sm-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="show_expiry" value="1" class="input-icheck"> @lang('lang_v1.show_product_expiry')
                </label>
              </div>
            </div>
          </div>
          @endif
          @if(request()->session()->get('business.enable_lot_number') == 1)
          <div class="col-sm-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="show_lot" value="1" class="input-icheck"> @lang('lang_v1.show_lot_number')
                </label>
              </div>
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>


    <div class="box box-solid">
      <div class="box-body">
        <div class="row">
          <!-- Subtotal Field -->
          <div class="col-sm-3">
            <div class="form-group">
              <label for="sub_total_label">@lang('invoice.sub_total_label'):</label>
              <input type="text" name="sub_total_label" id="sub_total_label" class="form-control" placeholder="@lang('invoice.sub_total_label')" value="Subtotal">
            </div>
          </div>

          <!-- Discount Field -->
          <div class="col-sm-3">
            <div class="form-group">
              <label for="discount_label">@lang('invoice.discount_label'):</label>
              <input type="text" name="discount_label" id="discount_label" class="form-control" placeholder="@lang('invoice.discount_label')" value="Discount">
            </div>
          </div>

          <!-- Tax Field -->
          <div class="col-sm-3">
            <div class="form-group">
              <label for="tax_label">@lang('invoice.tax_label'):</label>
              <input type="text" name="tax_label" id="tax_label" class="form-control" placeholder="@lang('invoice.tax_label')" value="Tax">
            </div>
          </div>

          <!-- Total Field -->
          <div class="col-sm-3">
            <div class="form-group">
              <label for="total_label">@lang('invoice.total_label'):</label>
              <input type="text" name="total_label" id="total_label" class="form-control" placeholder="@lang('invoice.total_label')" value="Total">
            </div>
          </div>

          <!-- Total Due Field -->
          <div class="col-sm-3">
            <div class="form-group">
              <label for="total_due_label">@lang('invoice.total_due_label') (@lang('lang_v1.current_sale')):</label>
              <input type="text" name="total_due_label" id="total_due_label" class="form-control" placeholder="@lang('invoice.total_due_label')" value="Total Due">
            </div>
          </div>

          <!-- Paid Field -->
          <div class="col-sm-3">
            <div class="form-group">
              <label for="paid_label">@lang('invoice.paid_label'):</label>
              <input type="text" name="paid_label" id="paid_label" class="form-control" placeholder="@lang('invoice.paid_label')" value="Total Paid">
            </div>
          </div>

          <!-- Show Payments Checkbox -->
          <div class="col-sm-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="show_payments" id="show_payments" value="1" checked> @lang('invoice.show_payments')
                </label>
              </div>
            </div>
          </div>

          <!-- Show Barcode Checkbox -->
          <div class="col-sm-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="show_barcode" id="show_barcode" value="1"> @lang('invoice.show_barcode')
                </label>
              </div>
            </div>
          </div>

          <div class="clearfix"></div>

          <!-- Previous Balance Label -->
          <div class="col-sm-3">
            <div class="form-group">
              <label for="prev_bal_label">@lang('invoice.total_due_label') (@lang('lang_v1.all_sales')):</label>
              <input type="text" name="prev_bal_label" id="prev_bal_label" class="form-control" placeholder="@lang('invoice.total_due_label')" value="All Balance Due">
            </div>
          </div>

          <!-- Show Previous Balance Checkbox -->
          <div class="col-sm-5">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="show_previous_bal" id="show_previous_bal" value="1"> @lang('lang_v1.show_previous_bal_due')
                </label>
                <span data-toggle="tooltip" title="@lang('lang_v1.previous_bal_due_help')">?</span>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>


    <div class="box box-solid">
      <div class="box-body">
        <div class="row">

          <!-- Highlight Color Field (hidden) -->
          <div class="col-sm-6 hide">
            <div class="form-group">
              <label for="highlight_color">@lang('invoice.highlight_color'):</label>
              <input type="text" name="highlight_color" id="highlight_color" class="form-control" placeholder="@lang('invoice.highlight_color')" value="#000000">
            </div>
          </div>

          <div class="clearfix"></div>

          <!-- Horizontal Line (hidden) -->
          <div class="col-md-12 hide">
            <hr />
          </div>

          <!-- Footer Text Field -->
          <div class="col-sm-12">
            <div class="form-group">
              <label for="footer_text">@lang('invoice.footer_text'):</label>
              <textarea name="footer_text" id="footer_text" class="form-control" placeholder="@lang('invoice.footer_text')" rows="3"></textarea>
            </div>
          </div>

          <!-- Set as Default Checkbox -->
          <div class="col-sm-6">
            <div class="form-group">
              <br>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="is_default" id="is_default" value="1"> @lang('barcode.set_as_default')
                </label>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>


    <!-- Call restaurant module if defined -->
    @include('restaurant.partials.invoice_layout')

    <div class="box box-solid">
      <div class="box-header with-border">
        <h3 class="box-title">@lang('lang_v1.layout_credit_note')</h3>
      </div>

      <div class="box-body">
        <div class="row">

          <!-- Credit Note Heading Field -->
          <div class="col-sm-3">
            <div class="form-group">
              <label for="cn_heading">@lang('lang_v1.cn_heading'):</label>
              <input type="text" name="cn_heading" id="cn_heading" class="form-control" placeholder="@lang('lang_v1.cn_heading')" value="Credit Note">
            </div>
          </div>

          <!-- Credit Note Number Label Field -->
          <div class="col-sm-3">
            <div class="form-group">
              <label for="cn_no_label">@lang('lang_v1.cn_no_label'):</label>
              <input type="text" name="cn_no_label" id="cn_no_label" class="form-control" placeholder="@lang('lang_v1.cn_no_label')" value="Ref. No.">
            </div>
          </div>

          <!-- Credit Amount Label Field -->
          <div class="col-sm-3">
            <div class="form-group">
              <label for="cn_amount_label">@lang('lang_v1.cn_amount_label'):</label>
              <input type="text" name="cn_amount_label" id="cn_amount_label" class="form-control" placeholder="@lang('lang_v1.cn_amount_label')" value="Credit Amount">
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-12">
        <button type="submit" class="btn btn-primary pull-right">@lang('messages.save')</button>
      </div>
    </div>

  </form>
</section>
<!-- /.content -->
@endsection
