<!-- Edit Shipping Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="confirmSuspendModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">@lang('lang_v1.suspend_sale')</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="cookie_note">@lang('lang_v1.suspend_note'):</label>
                            <textarea name="additional_notes"
                                id="cookie_note"
                                class="form-control mousetrap"
                                placeholder="Enter the name of customer"
                                rows="4"
                                readonly>{{ !empty($transaction->additional_notes) ? $transaction->additional_notes : '' }}</textarea>

                            <input type="hidden"
                                name="is_suspend"
                                id="is_suspend"
                                value="0">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="checkCookie2();" id="pos-suspend">
                    @lang('messages.save')
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    @lang('messages.close')
                </button>
            </div>
        </div>
    </div>
</div>
