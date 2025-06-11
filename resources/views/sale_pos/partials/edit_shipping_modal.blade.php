<!-- Edit Shipping Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="posShippingModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">@lang('sale.shipping')</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="shipping_details_modal">
                                @lang('sale.shipping_details')<span class="required">*</span>
                            </label>
                            <textarea name="shipping_details_modal"
                                id="shipping_details_modal"
                                class="form-control"
                                rows="4"
                                placeholder="@lang('sale.shipping_details')"
                                required>{{ $shipping_details }}</textarea>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="shipping_charges_modal">
                                @lang('sale.shipping_charges')<span class="required">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-info"></i>
                                </span>
                                <input type="text"
                                    name="shipping_charges_modal"
                                    id="shipping_charges_modal"
                                    class="form-control input_number"
                                    placeholder="@lang('sale.shipping_charges')"
                                    value="{{ @num_format($shipping_charges) }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="posShippingModalUpdate">
                    @lang('messages.update')
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    @lang('messages.cancel')
                </button>
            </div>
        </div>
    </div>
</div>
