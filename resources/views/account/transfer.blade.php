<div class="modal-dialog" role="document">
  <div class="modal-content">

    <form action="{{ route('account.postFundTransfer') }}" method="POST" id="fund_transfer_form">
      @csrf

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">@lang('account.fund_transfer')</h4>
      </div>

      <div class="modal-body">
        <div class="form-group">
          <strong>@lang('account.selected_account')</strong>:
          {{$from_account->name}}
          <input type="hidden" name="from_account" value="{{ $from_account->id }}">
        </div>

        <div class="form-group">
          <label for="to_account">@lang('account.transfer_to')*</label>
          <select name="to_account" id="to_account" class="form-control" required>
            @foreach($to_accounts as $key => $value)
            <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label for="amount">@lang('sale.amount')*</label>
          <input type="text" name="amount" id="amount" value="0" class="form-control input_number" required placeholder="@lang('sale.amount')">
        </div>

        <div class="form-group">
          <label for="operation_date">@lang('messages.date')*</label>
          <div class="input-group date" id="od_datetimepicker">
            <input type="text" name="operation_date" id="operation_date" value="0" class="form-control" required placeholder="@lang('messages.date')">
            <span class="input-group-addon">
              <span class="glyphicon glyphicon-calendar"></span>
            </span>
          </div>
        </div>

        <div class="form-group">
          <label for="note">@lang('brand.note')</label>
          <textarea name="note" id="note" class="form-control" rows="4" placeholder="@lang('brand.note')"></textarea>
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">@lang('messages.submit')</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
      </div>
    </form>

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

<script type="text/javascript">
  $(document).ready(function() {
    $('#od_datetimepicker').datetimepicker({
      format: moment_date_format + ' ' + moment_time_format
    });
  });
</script>
