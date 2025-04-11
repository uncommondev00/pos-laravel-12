<!-- Edit discount Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="posEditDiscountModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">@lang('sale.edit_discount')</h4>
            </div>
            <div class="modal-body">
                <!-- PIN Verification Section -->
                <div id="sec">
                    <center>
                        <div class="form-group row" align="center">
                            <div class="col-md-12 text-center">
                                <div class="col-md-4 text-center"></div>
                                <div class="col-md-4 text-center">
                                    <div id="CheckPIN" style="color: red"></div>
                                    <input type="password" 
                                        id="inputPIN" 
                                        name="RNPassword" 
                                        class="form-control text-center" 
                                        placeholder="Enter Pincode">
                                </div>
                                <div class="col-md-4 text-center"></div>
                            </div>
                        </div>
                        <div>
                            <button type="button" class="btn btn-success" id="dis_pin" onclick="myPin()">
                                Verify
                            </button>
                        </div>
                    </center>
                </div>

                <!-- Discount Form Section -->
                <div id="sec_1">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="discount_type_modal">
                                    @lang('sale.discount_type')<span class="required">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-info"></i>
                                    </span>
                                    <select name="discount_type_modal" 
                                        id="discount_type_modal" 
                                        class="form-control" 
                                        required>
                                        <option value="">@lang('messages.please_select')</option>
                                        <option value="fixed" {{ $discount_type == 'fixed' ? 'selected' : '' }}>
                                            @lang('lang_v1.fixed')
                                        </option>
                                        <option value="percentage" {{ $discount_type == 'percentage' ? 'selected' : '' }}>
                                            @lang('lang_v1.percentage')
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="discount_amount_modal">
                                    @lang('sale.discount_amount')<span class="required">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-info"></i>
                                    </span>
                                    <input type="text" 
                                        name="discount_amount_modal" 
                                        id="discount_amount_modal" 
                                        class="form-control input_number" 
                                        value="{{ @num_format($sales_discount) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="posEditDiscountModalUpdate">
                    @lang('messages.update')
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    @lang('messages.cancel')
                </button>
            </div>
        </div>
    </div>
</div>