<!-- Payment Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="modal_payment">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">@lang('lang_v1.payment')</h4>
                <h5 class="modal-title">Customer Points: <span id="c_p"></span> <span>point(s)</span></h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-9">
                        <div class="row">
                            <div id="payment_rows_div">
                                @foreach($payment_lines as $payment_line)
                                @if($payment_line['is_return'] == 1)
                                @php
                                $change_return = $payment_line;
                                @endphp
                                @continue
                                @endif
                                @include('sale_pos.partials.payment_row', ['removable' => !$loop->first, 'row_index' => $loop->index, 'payment_line' => $payment_line])
                                @endforeach
                            </div>
                            <input type="hidden" id="payment_row_index" value="{{ count($payment_lines) }}">
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary btn-block" id="add-payment-row">
                                    @lang('sale.add_payment_row')
                                </button>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sale_note">@lang('sale.sell_note'):</label>
                                    <textarea name="sale_note"
                                        id="sale_note"
                                        class="form-control"
                                        rows="3"
                                        placeholder="@lang('sale.sell_note')">{{ !empty($transaction) ? $transaction->additional_notes : '' }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="staff_note">@lang('sale.staff_note'):</label>
                                    <textarea name="staff_note"
                                        id="staff_note"
                                        class="form-control"
                                        rows="3"
                                        placeholder="@lang('sale.staff_note')">{{ !empty($transaction) ? $transaction->staff_note : '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="box box-solid bg-orange">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <strong>@lang('lang_v1.total_items'):</strong><br />
                                    <span class="lead text-bold total_quantity">0</span>
                                </div>

                                <div class="col-md-12">
                                    <hr>
                                    <strong>@lang('sale.total_payable'):</strong><br />
                                    <span class="lead text-bold total_payable_span">0</span>
                                </div>

                                <div class="col-md-12">
                                    <hr>
                                    <strong>@lang('lang_v1.total_paying'):</strong><br />
                                    <span class="lead text-bold total_paying">0</span>
                                    <input type="hidden" id="total_paying_input">
                                </div>

                                <div class="col-md-12">
                                    <hr>
                                    <strong>@lang('lang_v1.change_return'):</strong><br />
                                    <span class="lead text-bold change_return_span">0</span>
                                    <input type="hidden"
                                        name="change_return"
                                        id="change_return"
                                        class="form-control change_return input_number"
                                        value="{{ $change_return['amount'] }}"
                                        required
                                        readonly>
                                    @if(!empty($change_return['id']))
                                    <input type="hidden" name="change_return_id" value="{{ $change_return['id'] }}">
                                    @endif
                                </div>

                                <div class="col-md-12">
                                    <hr>
                                    <strong>@lang('lang_v1.balance'):</strong><br />
                                    <span class="lead text-bold balance_due">0</span>
                                    <input type="hidden" id="in_balance_due" value="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
                <button type="submit" class="btn btn-primary" id="pos-save" onclick="delete_cookie();">
                    @lang('sale.finalize_payment')
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Card Transaction Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="card_details_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">@lang('lang_v1.card_transaction_details')</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="card_number">@lang('lang_v1.card_no')</label>
                                <input type="text"
                                    name="card_number"
                                    id="card_number"
                                    class="form-control"
                                    placeholder="@lang('lang_v1.card_no')"
                                    autofocus>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="card_holder_name">@lang('lang_v1.card_holder_name')</label>
                                <input type="text"
                                    name="card_holder_name"
                                    id="card_holder_name"
                                    class="form-control"
                                    placeholder="@lang('lang_v1.card_holder_name')">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="card_transaction_number">@lang('lang_v1.card_transaction_no')</label>
                                <input type="text"
                                    name="card_transaction_number"
                                    id="card_transaction_number"
                                    class="form-control"
                                    placeholder="@lang('lang_v1.card_transaction_no')">
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="card_type">@lang('lang_v1.card_type')</label>
                                <select name="card_type" id="card_type" class="form-control select2">
                                    <option value="visa">Visa</option>
                                    <option value="master">MasterCard</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="card_month">@lang('lang_v1.month')</label>
                                <input type="text"
                                    name="card_month"
                                    id="card_month"
                                    class="form-control"
                                    placeholder="@lang('lang_v1.month')">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="card_year">@lang('lang_v1.year')</label>
                                <input type="text"
                                    name="card_year"
                                    id="card_year"
                                    class="form-control"
                                    placeholder="@lang('lang_v1.year')">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="card_security">@lang('lang_v1.security_code')</label>
                                <input type="text"
                                    name="card_security"
                                    id="card_security"
                                    class="form-control"
                                    placeholder="@lang('lang_v1.security_code')">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="pos-save-card">
                    @lang('sale.finalize_payment')
                </button>
            </div>
        </div>
    </div>
</div>
