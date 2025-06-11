<!-- Edit discount Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="recurringInvoiceModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">
                    @lang('lang_v1.subscribe')
                    @if(!empty($transaction->subscription_no))
                    - {{ $transaction->subscription_no }}
                    @endif
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="recur_interval">
                                @lang('lang_v1.subscription_interval')<span class="required">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number"
                                    name="recur_interval"
                                    id="recur_interval"
                                    class="form-control"
                                    required
                                    style="width: 50%;"
                                    value="{{ !empty($transaction->recur_interval) ? $transaction->recur_interval : '' }}">

                                <select name="recur_interval_type"
                                    id="recur_interval_type"
                                    class="form-control"
                                    required
                                    style="width: 50%;">
                                    <option value="days" {{ (!empty($transaction->recur_interval_type) && $transaction->recur_interval_type == 'days') ? 'selected' : '' }}>
                                        @lang('lang_v1.days')
                                    </option>
                                    <option value="months" {{ (!empty($transaction->recur_interval_type) && $transaction->recur_interval_type == 'months') ? 'selected' : '' }}>
                                        @lang('lang_v1.months')
                                    </option>
                                    <option value="years" {{ (!empty($transaction->recur_interval_type) && $transaction->recur_interval_type == 'years') ? 'selected' : '' }}>
                                        @lang('lang_v1.years')
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="recur_repetitions">@lang('lang_v1.no_of_repetitions'):</label>
                            <input type="number"
                                name="recur_repetitions"
                                id="recur_repetitions"
                                class="form-control"
                                value="{{ !empty($transaction->recur_repetitions) ? $transaction->recur_repetitions : '' }}">
                            <p class="help-block">@lang('lang_v1.recur_repetition_help')</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    @lang('messages.close')
                </button>
            </div>
        </div>
    </div>
</div>
