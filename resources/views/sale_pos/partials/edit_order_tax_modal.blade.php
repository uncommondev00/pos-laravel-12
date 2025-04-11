<!-- Edit Order tax Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="posEditOrderTaxModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">@lang('sale.edit_order_tax')</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="order_tax_modal">
                                @lang('sale.order_tax')<span class="required">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-info"></i>
                                </span>
                                <select name="order_tax_modal" 
                                    id="order_tax_modal" 
                                    class="form-control"
                                >
                                    <option value="">@lang('messages.please_select')</option>
                                    @foreach($taxes['tax_rates'] as $id => $tax_rate)
                                        <option value="{{ $id }}" {{ $selected_tax == $id ? 'selected' : '' }}>
                                            {{ $tax_rate }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="posEditOrderTaxModalUpdate">
                    @lang('messages.update')
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    @lang('messages.cancel')
                </button>
            </div>
        </div>
    </div>
</div>