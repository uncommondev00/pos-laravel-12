<div class="payment_details_div {{ $payment_line->method !== 'card' ? 'hide' : '' }}" data-type="card">
    <div class="col-md-4">
        <div class="form-group">
            <label for="card_number">Card No</label>
            <input type="text" name="card_number" value="{{ $payment_line->card_number }}" class="form-control" placeholder="Card No">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="card_holder_name">Card Holder Name</label>
            <input type="text" name="card_holder_name" value="{{ $payment_line->card_holder_name }}" class="form-control" placeholder="Card Holder Name">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="card_transaction_number">Card Transaction Number</label>
            <input type="text" name="card_transaction_number" value="{{ $payment_line->card_transaction_number }}" class="form-control" placeholder="Card Transaction Number">
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="card_type">Card Type</label>
            <select name="card_type" class="form-control select2">
                <option value="credit" {{ $payment_line->card_type == 'credit' ? 'selected' : '' }}>Credit Card</option>
                <option value="debit" {{ $payment_line->card_type == 'debit' ? 'selected' : '' }}>Debit Card</option>
                <option value="visa" {{ $payment_line->card_type == 'visa' ? 'selected' : '' }}>Visa</option>
                <option value="master" {{ $payment_line->card_type == 'master' ? 'selected' : '' }}>MasterCard</option>
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="card_month">Month</label>
            <input type="text" name="card_month" value="{{ $payment_line->card_month }}" class="form-control" placeholder="Month">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="card_year">Year</label>
            <input type="text" name="card_year" value="{{ $payment_line->card_year }}" class="form-control" placeholder="Year">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="card_security">Security Code</label>
            <input type="text" name="card_security" value="{{ $payment_line->card_security }}" class="form-control" placeholder="Security Code">
        </div>
    </div>
    <div class="clearfix"></div>
</div>

<div class="payment_details_div {{ $payment_line->method !== 'cheque' ? 'hide' : '' }}" data-type="cheque">
    <div class="col-md-12">
        <div class="form-group">
            <label for="cheque_number">Cheque No</label>
            <input type="text" name="cheque_number" value="{{ $payment_line->cheque_number }}" class="form-control" placeholder="Check No">
        </div>
    </div>
</div>

<div class="payment_details_div {{ $payment_line->method !== 'bank_transfer' ? 'hide' : '' }}" data-type="bank_transfer">
    <div class="col-md-12">
        <div class="form-group">
            <label for="bank_account_number">Bank Account No</label>
            <input type="text" name="bank_account_number" value="{{ $payment_line->bank_account_number }}" class="form-control" placeholder="Bank Account No">
        </div>
    </div>
</div>

@foreach ([1, 2, 3] as $index)
    <div class="payment_details_div {{ $payment_line->method !== 'custom_pay_' . $index ? 'hide' : '' }}" data-type="custom_pay_{{ $index }}">
        <div class="col-md-12">
            <div class="form-group">
                <label for="transaction_no_{{ $index }}">{{ __('lang_v1.transaction_no') }}</label>
                <input type="text" name="transaction_no_{{ $index }}" value="{{ $payment_line->transaction_no }}" class="form-control" placeholder="{{ __('lang_v1.transaction_no') }}">
            </div>
        </div>
    </div>
@endforeach
