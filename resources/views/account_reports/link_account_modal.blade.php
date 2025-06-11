<div class="modal-dialog" role="document">
  <div class="modal-content">

    <form action="{{ route('account.postLinkAccount') }}" method="POST" id="link_account_form">
      @csrf

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">@lang('account.link_account') - @lang('account.payment_ref_no'): - {{$payment->payment_ref_no}}</h4>
      </div>

      <div class="modal-body">
        <div class="form-group">
          <input type="hidden" name="transaction_payment_id" value="{{ $payment->id }}">
          <label for="account_id">@lang('account.account'):</label>
          <select name="account_id" id="account_id" class="form-control" required>
            @foreach($accounts as $key => $value)
            <option value="{{ $key }}" {{ $payment->account_id == $key ? 'selected' : '' }}>{{ $value }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
      </div>

    </form>

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
