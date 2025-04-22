<div class="pos-tab-content">
    <h4>@lang('business.add_keyboard_shortcuts'):</h4>
    <p class="help-block">@lang('lang_v1.shortcut_help'); @lang('lang_v1.example'): <b>ctrl+shift+b</b>, <b>ctrl+h</b></p>
    <p class="help-block">
        <b>@lang('lang_v1.available_key_names_are'):</b>
        <br> shift, ctrl, alt, backspace, tab, enter, return, capslock, esc, escape, space, pageup, pagedown, end, home, <br>left, up, right, down, ins, del, and plus
    </p>
    <div class="row">
        <div class="col-sm-6">
            <table class="table table-striped">
                <tr>
                    <th>@lang('business.operations')</th>
                    <th>@lang('business.keyboard_shortcut')</th>
                </tr>
                <tr>
                    <td>{!! __('sale.express_finalize') !!}:</td>
                    <td>
                        <input type="text" name="shortcuts[pos][express_checkout]" value="{{ $shortcuts['pos']['express_checkout'] ?? '' }}" class="form-control">
                    </td>
                </tr>
                <tr>
                    <td>@lang('sale.finalize'):</td>
                    <td>
                        <input type="text" name="shortcuts[pos][pay_n_ckeckout]" value="{{ $shortcuts['pos']['pay_n_ckeckout'] ?? '' }}" class="form-control">
                    </td>
                </tr>
                <tr>
                    <td>@lang('sale.draft'):</td>
                    <td>
                        <input type="text" name="shortcuts[pos][draft]" value="{{ $shortcuts['pos']['draft'] ?? '' }}" class="form-control">
                    </td>
                </tr>
                <tr>
                    <td>@lang('messages.cancel'):</td>
                    <td>
                        <input type="text" name="shortcuts[pos][cancel]" value="{{ $shortcuts['pos']['cancel'] ?? '' }}" class="form-control">
                    </td>
                </tr>
                <tr>
                    <td>@lang('lang_v1.recent_product_quantity'):</td>
                    <td>
                        <input type="text" name="shortcuts[pos][recent_product_quantity]" value="{{ $shortcuts['pos']['recent_product_quantity'] ?? '' }}" class="form-control">
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-sm-6">
            <table class="table table-striped">
                <tr>
                    <th>@lang('business.operations')</th>
                    <th>@lang('business.keyboard_shortcut')</th>
                </tr>
                <tr>
                    <td>@lang('sale.edit_discount'):</td>
                    <td>
                        <input type="text" name="shortcuts[pos][edit_discount]" value="{{ $shortcuts['pos']['edit_discount'] ?? '' }}" class="form-control">
                    </td>
                </tr>
                <tr>
                    <td>@lang('sale.edit_order_tax'):</td>
                    <td>
                        <input type="text" name="shortcuts[pos][edit_order_tax]" value="{{ $shortcuts['pos']['edit_order_tax'] ?? '' }}" class="form-control">
                    </td>
                </tr>
                <tr>
                    <td>@lang('sale.add_payment_row'):</td>
                    <td>
                        <input type="text" name="shortcuts[pos][add_payment_row]" value="{{ $shortcuts['pos']['add_payment_row'] ?? '' }}" class="form-control">
                    </td>
                </tr>
                <tr>
                    <td>@lang('sale.finalize_payment'):</td>
                    <td>
                        <input type="text" name="shortcuts[pos][finalize_payment]" value="{{ $shortcuts['pos']['finalize_payment'] ?? '' }}" class="form-control">
                    </td>
                </tr>
                <tr>
                    <td>@lang('lang_v1.add_new_product'):</td>
                    <td>
                        <input type="text" name="shortcuts[pos][add_new_product]" value="{{ $shortcuts['pos']['add_new_product'] ?? '' }}" class="form-control">
                    </td>
                </tr>
            </table>
        </div>
    </div>


    <div class="row">
        <div class="col-sm-12">
            <h4>@lang('lang_v1.pos_settings'):</h4>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <div class="checkbox">
                    <br>
                    <label>
                        <input type="checkbox" name="pos_settings[disable_pay_checkout]" value="1"
                            {{ $pos_settings['disable_pay_checkout'] ? 'checked' : '' }} class="input-icheck">
                        {{ __( 'lang_v1.disable_pay_checkout' ) }}
                    </label>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <div class="checkbox">
                    <br>
                    <label>
                        <input type="checkbox" name="pos_settings[disable_draft]" value="1"
                            {{ $pos_settings['disable_draft'] ? 'checked' : '' }} class="input-icheck">
                        {{ __( 'lang_v1.disable_draft' ) }}
                    </label>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <div class="checkbox">
                    <br>
                    <label>
                        <input type="checkbox" name="pos_settings[disable_express_checkout]" value="1"
                            {{ $pos_settings['disable_express_checkout'] ? 'checked' : '' }} class="input-icheck">
                        {{ __( 'lang_v1.disable_express_checkout' ) }}
                    </label>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <div class="checkbox">
                    <br>
                    <label>
                        <input type="checkbox" name="pos_settings[hide_product_suggestion]" value="1"
                            {{ $pos_settings['hide_product_suggestion'] ? 'checked' : '' }} class="input-icheck">
                        {{ __( 'lang_v1.hide_product_suggestion' ) }}
                    </label>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <div class="checkbox">
                    <br>
                    <label>
                        <input type="checkbox" name="pos_settings[hide_recent_trans]" value="1"
                            {{ $pos_settings['hide_recent_trans'] ? 'checked' : '' }} class="input-icheck">
                        {{ __( 'lang_v1.hide_recent_trans' ) }}
                    </label>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <div class="checkbox">
                    <br>
                    <label>
                        <input type="checkbox" name="pos_settings[disable_discount]" value="1"
                            {{ $pos_settings['disable_discount'] ? 'checked' : '' }} class="input-icheck">
                        {{ __( 'lang_v1.disable_discount' ) }}
                    </label>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <div class="checkbox">
                    <br>
                    <label>
                        <input type="checkbox" name="pos_settings[disable_order_tax]" value="1"
                            {{ $pos_settings['disable_order_tax'] ? 'checked' : '' }} class="input-icheck">
                        {{ __( 'lang_v1.disable_order_tax' ) }}
                    </label>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <div class="checkbox">
                    <br>
                    <label>
                        <input type="checkbox" name="pos_settings[is_pos_subtotal_editable]" value="1"
                            {{ empty($pos_settings['is_pos_subtotal_editable']) ? '' : 'checked' }} class="input-icheck">
                        {{ __( 'lang_v1.subtotal_editable' ) }}
                    </label>
                    @show_tooltip(__('lang_v1.subtotal_editable_help_text'))
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <div class="checkbox">
                    <br>
                    <label>
                        <input type="checkbox" name="pos_settings[disable_suspend]" value="1"
                            {{ empty($pos_settings['disable_suspend']) ? '' : 'checked' }} class="input-icheck">
                        {{ __( 'lang_v1.disable_suspend_sale' ) }}
                    </label>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <div class="checkbox">
                    <br>
                    <label>
                        <input type="checkbox" name="pos_settings[inline_service_staff]" value="1"
                            {{ !empty($pos_settings['inline_service_staff']) ? 'checked' : '' }} class="input-icheck">
                        {{ __( 'lang_v1.enable_service_staff_in_product_line' ) }}
                    </label>
                    @show_tooltip(__('lang_v1.inline_service_staff_tooltip'))
                </div>
            </div>
        </div>

    </div>

</div>
