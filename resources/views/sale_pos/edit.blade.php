@extends('layouts.app')

@section('title', 'POS')

@section('content')

    <!-- Content Header (Page header) -->
    <!-- <section class="content-header">
        <h1>Add Purchase</h1> -->
    <!-- <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol> -->
    <!-- </section> -->

    <!-- Main content -->
    <section class="content no-print">
        <div class="row">
            <div class="@if (!empty($pos_settings['hide_product_suggestion']) && !empty($pos_settings['hide_recent_trans'])) col-md-10 col-md-offset-1 @else col-md-7 @endif col-sm-12">
                <div class="box box-success">
                    <!-- Header Section -->
                    <div class="box-header with-border col-sm-12">
                        <div class="col-md-4">
                            <h3 class="box-title">
                                Editing
                                @if ($transaction->status == 'draft' && $transaction->is_quotation == 1)
                                    @lang('lang_v1.quotation')
                                @elseif($transaction->status == 'draft')
                                    Draft
                                @elseif($transaction->status == 'final')
                                    Invoice
                                @endif
                                <span class="text-success">#{{ $transaction->invoice_no }}</span>
                                <i class="fa fa-keyboard-o hover-q text-muted" aria-hidden="true" data-container="body"
                                    data-toggle="popover" data-placement="bottom"
                                    data-content="@include('sale_pos.partials.keyboard_shortcuts_details')" data-html="true" data-trigger="hover">
                                </i>
                            </h3>
                        </div>

                        <div class="col-md-7 text-right">
                            @include('layouts.partials.header-pos')
                        </div>

                        <div class="text-right box-tools col-md-1">
                            <a class="btn btn-success btn-sm" onclick="checkCookie2();"
                                href="{{ route('pos.create') }}">
                                <strong><i class="fa fa-plus"></i> POS</strong>
                            </a>
                        </div>
                    </div>

                    <!-- Main Form -->
                    <input type="hidden" id="item_addition_method" value="{{ $business_details->item_addition_method }}">

                    <form action="{{ route('pos.update', [$transaction->id]) }}" method="POST"
                        id="edit_pos_sell_form">
                        @csrf
                        @method('PUT')

                        <!-- Hidden Fields -->
                        <input type="hidden" id="location_id" name="location_id" value="{{ $transaction->location_id }}"
                            data-receipt_printer_type="{{ !empty($location_printer_type) ? $location_printer_type : 'browser' }}">

                        <!-- Points System Fields -->
                        <input type="hidden" name="prev_points" id="prev_points"
                            value="{{ $transaction->contact->points_value }}">
                        <input type="hidden" name="points_value" id="points_value"
                            value="{{ $transaction->contact->points_value }}">
                        <input type="hidden" name="points_amount" id="points_amount" value="{{ $points->points_amount }}">
                        <input type="hidden" name="points" id="points" value="{{ $points->points }}">
                        <input type="hidden" name="points_tobe_added" id="points_tobe_added" value="0">
                        <input type="hidden" name="points_status" id="points_status"
                            value="{{ $transaction->contact->points_status }}">
                        <input type="hidden" id="points_is_suspend" value="{{ $transaction->is_suspend }}">

                        <div class="box-body">
                            <div class="row">
                                <!-- Currency Exchange Section -->
                                @if (config('constants.enable_sell_in_diff_currency') == true)
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-exchange"></i>
                                                </span>
                                                <input type="text" name="exchange_rate" id="exchange_rate"
                                                    class="form-control input-sm input_number"
                                                    value="{{ @num_format($transaction->exchange_rate) }}"
                                                    placeholder="@lang('lang_v1.currency_exchange_rate')">
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Price Groups Section -->
                                @if (!empty($price_groups))
                                    @if (count($price_groups) > 1)
                                        <div class="col-md-4 col-sm-6">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-money"></i>
                                                    </span>
                                                    <input type="hidden" name="hidden_price_group" id="hidden_price_group"
                                                        value="{{ $transaction->selling_price_group_id }}">
                                                    <select name="price_group" id="price_group" class="form-control select2"
                                                        style="width: 100%;">
                                                        @foreach ($price_groups as $key => $value)
                                                            <option value="{{ $key }}"
                                                                {{ $transaction->selling_price_group_id == $key ? 'selected' : '' }}>
                                                                {{ $value }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="input-group-addon">
                                                        @show_tooltip(__('lang_v1.price_group_help_text'))
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <input type="hidden" name="price_group" id="price_group"
                                            value="{{ $transaction->selling_price_group_id }}">
                                    @endif
                                @endif

                                <!-- Continue with the rest of the form... -->
                                <!-- Subscription Section -->
                                @if (in_array('subscription', $enabled_modules))
                                    <div class="col-md-4 pull-right col-sm-6">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="is_recurring" id="is_recurring"
                                                    class="input-icheck" value="1"
                                                    {{ $transaction->is_recurring ? 'checked' : '' }}>
                                                @lang('lang_v1.subscribe')?
                                            </label>
                                            <button type="button" class="btn btn-link" data-toggle="modal"
                                                data-target="#recurringInvoiceModal">
                                                <i class="fa fa-external-link"></i>
                                            </button>
                                            @show_tooltip(__('lang_v1.recurring_invoice_help'))
                                        </div>
                                    </div>
                                @endif

                                <!-- Customer Information Section -->
                                <div class="row">
                                    <div class="@if (!empty($commission_agent)) col-sm-4 @else col-sm-6 @endif">
                                        <div class="form-group hide">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </span>
                                                <input type="hidden" id="default_customer_id"
                                                    value="{{ $transaction->contact->id }}">
                                                <input type="hidden" id="default_customer_name"
                                                    value="{{ $transaction->contact->name }}">
                                                <select name="contact_id" id="customer_id" class="form-control mousetrap"
                                                    required readonly style="width: 100%;"
                                                    placeholder="Enter Customer name / phone">
                                                </select>
                                                <span class="input-group-btn">
                                                    <button type="button"
                                                        class="btn btn-default bg-white btn-flat add_new_customer"
                                                        data-name="" disabled>
                                                        <i class="fa fa-plus-circle text-primary fa-lg"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </span>
                                                <input type="text" class="form-control mousetrap" style="width:100%;"
                                                    value="{{ $transaction->contact->name }}" readonly>
                                                <span class="input-group-btn">
                                                    <button type="button"
                                                        class="btn btn-default bg-white btn-flat add_new_customer"
                                                        data-name="" disabled>
                                                        <i class="fa fa-plus-circle text-primary fa-lg"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Payment Terms -->
                                    <input type="hidden" name="pay_term_number" id="pay_term_number"
                                        value="{{ $transaction->pay_term_number }}">
                                    <input type="hidden" name="pay_term_type" id="pay_term_type"
                                        value="{{ $transaction->pay_term_type }}">

                                    <!-- Commission Agent Section -->
                                    @if (!empty($commission_agent))
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <select name="commission_agent" id="commission_agent"
                                                    class="form-control select2">
                                                    <option value="">@lang('lang_v1.commission_agent')</option>
                                                    @foreach ($commission_agent as $key => $agent)
                                                        <option value="{{ $key }}"
                                                            {{ $transaction->commission_agent == $key ? 'selected' : '' }}>
                                                            {{ $agent }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Product Search Section -->
                                    <div class="@if (!empty($commission_agent)) col-sm-4 @else col-sm-6 @endif">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-barcode"></i>
                                                </span>
                                                <input type="text" name="search_product" id="search_product"
                                                    class="form-control mousetrap" placeholder="@lang('lang_v1.search_product_placeholder')"
                                                    autofocus>
                                                <span class="input-group-btn">
                                                    <button type="button"
                                                        class="btn btn-default bg-white btn-flat pos_add_quick_product"
                                                        data-href="{{ route('products.quickAdd') }}"
                                                        data-container=".quick_add_product_modal"
                                                        {{ !auth()->user()->can('product.create') ? 'disabled' : '' }}>
                                                        <i class="fa fa-plus-circle text-primary fa-lg"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Restaurant Module Section -->
                                    @if (in_array('tables', $enabled_modules) || in_array('service_staff', $enabled_modules))
                                        <span id="restaurant_module_span" data-transaction_id="{{ $transaction->id }}">
                                            <div class="col-md-3"></div>
                                        </span>
                                    @endif
                                </div>
                                <!-- Product Table Section -->
                                <div class="row col-sm-12 pos_product_div">
                                    <input type="hidden" name="sell_price_tax" id="sell_price_tax"
                                        value="{{ $business_details->sell_price_tax }}">
                                    <input type="hidden" id="product_row_count" value="{{ count($sell_details) }}">
                                    <input type="hidden" class="date_trans" name="date_trans"
                                        value="{{ $tdate->created_at }}">

                                    <table class="table table-condensed table-bordered table-striped table-responsive"
                                        id="pos_table">
                                        <thead>
                                            <tr>
                                                <th
                                                    class="text-center @if (!empty($pos_settings['inline_service_staff'])) col-md-3 @else col-md-4 @endif">
                                                    @lang('sale.product')
                                                </th>
                                                <th class="text-center col-md-3">
                                                    @lang('sale.qty')
                                                </th>
                                                @if (!empty($pos_settings['inline_service_staff']))
                                                    <th class="text-center col-md-2">
                                                        @lang('restaurant.service_staff')
                                                    </th>
                                                @endif
                                                <th
                                                    class="text-center col-md-2 {{ session()->get('business.enable_inline_tax') == 0 ? 'hide' : '' }}">
                                                    @lang('sale.price_inc_tax')
                                                </th>
                                                <th class="text-center col-md-3">
                                                    @lang('sale.subtotal')
                                                </th>
                                                <th class="text-center">
                                                    <i class="fa fa-close" aria-hidden="true"></i>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($sell_details as $sell_line)
                                                @include('sale_pos.product_row', [
                                                    'product' => $sell_line,
                                                    'row_count' => $loop->index,
                                                    'tax_dropdown' => $taxes,
                                                    'sub_units' => !empty($sell_line->unit_details)
                                                        ? $sell_line->unit_details
                                                        : [],
                                                ])
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- POS Details Section -->
                                @include('sale_pos.partials.pos_details', ['edit' => true])

                                <!-- Payment Modal -->
                                @include('sale_pos.partials.payment_modal')

                                <!-- Suspend Note Modal -->
                                @if (empty($pos_settings['disable_suspend']))
                                    @include('sale_pos.partials.suspend_note_modal')
                                @endif

                                <!-- Recurring Invoice Modal -->
                                @if (empty($pos_settings['disable_recurring_invoice']))
                                    @include('sale_pos.partials.recurring_invoice_modal')
                                @endif

                            </div>
                            <!-- /.box-body -->
                    </form>
                </div>
                <!-- /.box -->
            </div>

            <!-- Right Side Division -->
            <div class="col-md-5 col-sm-12">
                @include('sale_pos.partials.right_div')
            </div>
        </div>
    </section>

    <!-- This will be printed -->
    <section class="invoice print_section" id="receipt_section">
    </section>
    <div class="modal fade contact_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        @include('contact.create', ['quick_add' => true])
    </div>
    <!-- /.content -->
    <div class="modal fade register_details_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>
    <div class="modal fade close_register_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>
    <!-- quick product modal -->
    <div class="modal fade quick_add_product_modal" tabindex="-1" role="dialog" aria-labelledby="modalTitle"></div>

@stop
@section('javascript')
    <script src="{{ asset('js/pos.js?v=' . $asset_v) }}"></script>
    <script src="{{ asset('js/printer.js?v=' . $asset_v) }}"></script>
    <script src="{{ asset('js/product.js?v=' . $asset_v) }}"></script>
    <script src="{{ asset('js/opening_stock.js?v=' . $asset_v) }}"></script>


    @include('sale_pos.partials.keyboard_shortcuts')

    <!-- Call restaurant module if defined -->
    @if (in_array('tables', $enabled_modules) ||
            in_array('modifiers', $enabled_modules) ||
            in_array('service_staff', $enabled_modules))
        <script src="{{ asset('js/restaurant.js?v=' . $asset_v) }}"></script>
    @endif

    <script type="text/javascript">
        $(document).ready(function() {

            document.onkeydown = function(e) {
                if (e.ctrlKey) {
                    e.preventDefault();
                    return false;
                }
            }

            $('#discount_value_modal').attr('readonly', true);
            var an = "<?php echo $transaction->additional_notes; ?>";
            var suspend = "<?php echo $transaction->is_suspend; ?>";

            if (suspend == 1) {
                if (an != "") {
                    //alert(an);
                    $('div#posAddDiscountModal').modal('show');
                    $("#add_discount_section_1").hide();
                    $("button#btn_ok").hide();
                    $("button#btn_cancel").hide();
                    $("#customer_id").hide();
                } else {
                    $("i#pos-edit-discount").hide();
                }
            } else {
                $('.edit-disc').hide();
            }

        });



        var id = "<?php echo $transaction->s_id; ?>"
        var name1 = "<?php echo $transaction->s_name; ?>"
        var addr = "<?php echo $transaction->s_addr; ?>"

        if (id == "" || name1 == "" || addr == "") {
            document.getElementById("s_id").value = "";
            document.getElementById("s_name").value = "";
            document.getElementById("s_addr").value = "";
        } else {
            document.getElementById("s_id").value = id;
            document.getElementById("s_name").value = name1;
            document.getElementById("s_addr").value = addr;
        }

        var coc = "<?php echo $transaction->additional_notes; ?>"


        if (coc == "no discount" || coc == "") {
            document.getElementById("total_discount_val").innerHTML = 0;
            document.getElementById("discount_value_modal").value = 0;
        } else {
            document.getElementById("total_discount_val").innerHTML = coc;
            document.getElementById("discount_value_modal").value = coc;
        }




        function setCookie(cname, cvalue, exdays) {
            var d = new Date();
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
            var expires = "expires=" + d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

        function getCookie(cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }

        function checkCookie() {

            var disc = getCookie("discount");
            if (disc != "") {
                //alert("Your Discount is " + disc);
                disc = document.getElementById("discount_value_modal").value;
                document.getElementById("total_discount_val").innerHTML = disc;
                if (disc != "" && disc != null) {
                    setCookie("discount", disc, 999);
                }

            } else {
                disc = document.getElementById("discount_value_modal").value;
                document.getElementById("total_discount_val").innerHTML = disc;
                if (disc != "" && disc != null) {
                    setCookie("discount", disc, 999);
                }
            }

            if (document.getElementById("discount_value_modal").value == "") {
                document.getElementById("total_discount_val").innerHTML = 0;
            }
        }

        function delete_cookie() {
            setCookie("discount", 0, 999);
            document.getElementById("total_discount_val").innerHTML = 0;
        };

        function delete_cookie_final() {
            setCookie("discount", 0, 999);
            document.getElementById("total_discount_val").innerHTML = 0;
        };
    </script>

    <script type="text/javascript">
        function setCookie2(cname2, cvalue2, exdays2) {
            var d2 = new Date();
            d2.setTime(d2.getTime() + (exdays2 * 24 * 60 * 60 * 1000));
            var expires2 = "expires2=" + d2.toUTCString();
            document.cookie = cname2 + "=" + cvalue2 + ";" + expires2 + ";path=/";
        }

        function getCookie2(cname2) {
            var name2 = cname2 + "=";
            var ca2 = document.cookie.split(';');
            for (var i = 0; i < ca2.length; i++) {
                var c2 = ca2[i];
                while (c2.charAt(0) == ' ') {
                    c2 = c2.substring(1);
                }
                if (c2.indexOf(name2) == 0) {
                    return c2.substring(name2.length, c2.length);
                }
            }
            return "";
        }

        function checkCookie2() {
            disc25 = document.getElementById("cookie_note").value;
            var disc2 = getCookie2(disc25);
            if (disc2 != "") {
                //alert("Your Discount is " + disc);

                var vd = getCookie("discount");
                document.getElementById("total_discount_val").innerHTML = vd;
                if (disc2 != "" && disc2 != null) {
                    setCookie2(disc2, vd, 999);
                    setCookie2("discount", 0, 999);
                    document.getElementById("total_discount_val").innerHTML = 0;
                }

            } else {
                disc2 = document.getElementById("cookie_note").value;
                var vd = getCookie("discount");
                document.getElementById("total_discount_val").innerHTML = vd;
                if (disc2 != "" && disc2 != null) {
                    setCookie2(disc2, vd, 999);
                    setCookie2("discount", 0, 999);
                    document.getElementById("total_discount_val").innerHTML = 0;
                }
            }
        }

        function delete_cookie() {
            setCookie("discount", 0, 999);
            document.getElementById("total_discount_val").innerHTML = 0;
        };
    </script>
    <script type="text/javascript">
        $(document).ready(function() {

            var p = $('input#points_value').val();
            $('.input_total_points').val(p);

            //cannot repeat payment method
            var payment_type_value = [];

            $('div#payment_rows_div #tr_row').each(function() {
                var p_t = $(this).find('.payment_types_dropdown').val();
                payment_type_value.push(p_t)

                //alert(p_t);

            });

            //alert(payment_type_value);


            $('div#payment_rows_div #tr_row').find("option").prop("disabled", false);
            $('div#payment_rows_div #tr_row').find("option").css({
                "color": "black"
            });
            for (var index in payment_type_value) {

                //$('option[value="'+payment_type_value[index]+'"]').prop("disabled", true); 
                $('div#payment_rows_div #tr_row').find('option[value="' + payment_type_value[index] +
                        '"]:not(:selected)')
                    .prop("disabled", true);

                $('div#payment_rows_div #tr_row').find('option[value="' + payment_type_value[index] +
                    '"]:not(:selected)').css({
                    "color": "#C8C8C8"
                });

            }

            //payment_types_dropdown
            //payment_row_index
            $('div#payment_rows_div').on('change', '.payment_types_dropdown', function() {
                var select_val = $(this).val();
                var div = $(this).parents('#tr_row');

                var indx = div.find('.payment_row_index').val();

                var amount = div.find('.payment-amount').val();

                var n_amnt = amount.replace(",", "");

                if (select_val == 'points') {
                    var rp = $('input#points_value').val();
                    $('input.input_total_points').val(rp);

                    var points = div.find('.input_total_points').val();

                    //$('input.input_total_points').val(rp);
                    var diff_p = points - n_amnt;
                    if (diff_p < 0) {
                        toastr.error('Insufficient points.');
                        $('#pos-save').attr('disabled', 'true');
                        //alert(diff_p);
                    } else {
                        $('input.input_total_points').val(diff_p);
                        $('input#points_value').val(diff_p);
                        $('#pos-save').removeAttr('disabled');
                    }

                    //alert(points);
                } else {
                    $('#pos-save').removeAttr('disabled');
                }

                //cannot repeat payment method
                var payment_type_value = [];

                $('div#payment_rows_div #tr_row').each(function() {
                    var p_t = $(this).find('.payment_types_dropdown').val();
                    payment_type_value.push(p_t)

                    //alert(p_t);

                });

                //alert(payment_type_value);


                $('div#payment_rows_div #tr_row').find("option").prop("disabled", false);
                $('div#payment_rows_div #tr_row').find("option").css({
                    "color": "black"
                });
                for (var index in payment_type_value) {

                    //$('option[value="'+payment_type_value[index]+'"]').prop("disabled", true); 
                    $('div#payment_rows_div #tr_row').find('option[value="' + payment_type_value[index] +
                            '"]:not(:selected)')
                        .prop("disabled", true);

                    $('div#payment_rows_div #tr_row').find('option[value="' + payment_type_value[index] +
                        '"]:not(:selected)').css({
                        "color": "#C8C8C8"
                    });

                }

            });
        });
    </script>

@endsection

@section('css')
    <style type="text/css">
        /*CSS to print receipts*/
        .print_section {
            display: none;
        }

        @media print {
            .print_section {
                display: block !important;
            }
        }

        @page {
            size: 3.1in auto;
            /* width height */
            height: auto !important;
            margin-top: 0mm;
            margin-bottom: 0mm;
        }
    </style>
@endsection
