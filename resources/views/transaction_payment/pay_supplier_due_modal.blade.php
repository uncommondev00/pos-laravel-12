<div class="modal-dialog" role="document">
  <div class="modal-content">

    <form action="{{ route('payments.postPayContactDue') }}" method="post" id="pay_contact_due_form" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="contact_id" value="{{ $contact_details->contact_id }}">
      <input type="hidden" name="due_payment_type" value="{{ $due_payment_type }}">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">@lang( 'purchase.add_payment' )</h4>
      </div>

      <div class="modal-body">
        <div class="row">
          @if($due_payment_type == 'purchase')
          <div class="col-md-6">
            <div class="well">
              <strong>@lang('purchase.supplier'): </strong>{{ $contact_details->name }}<br>
              <strong>@lang('business.business'): </strong>{{ $contact_details->supplier_business_name }}<br><br>
            </div>
          </div>
          <div class="col-md-6">
            <div class="well">
              <strong>@lang('report.total_purchase'): </strong><span class="display_currency" data-currency_symbol="true">{{ $contact_details->total_purchase }}</span><br>
              <strong>@lang('contact.total_paid'): </strong><span class="display_currency" data-currency_symbol="true">{{ $contact_details->total_paid }}</span><br>
              <strong>@lang('contact.total_purchase_due'): </strong><span class="display_currency" data-currency_symbol="true">{{ $contact_details->total_purchase - $contact_details->total_paid }}</span><br>
              @if(!empty($contact_details->opening_balance) || $contact_details->opening_balance != '0.00')
              <strong>@lang('lang_v1.opening_balance'): </strong>
              <span class="display_currency" data-currency_symbol="true">
                {{ $contact_details->opening_balance }}</span><br>
              <strong>@lang('lang_v1.opening_balance_due'): </strong>
              <span class="display_currency" data-currency_symbol="true">
                {{ $ob_due }}</span>
              @endif
            </div>
          </div>
          @elseif($due_payment_type == 'purchase_return')
          <div class="col-md-6">
            <div class="well">
              <strong>@lang('purchase.supplier'): </strong>{{ $contact_details->name }}<br>
              <strong>@lang('business.business'): </strong>{{ $contact_details->supplier_business_name }}<br><br>
            </div>
          </div>
          <div class="col-md-6">
            <div class="well">
              <strong>@lang('lang_v1.total_purchase_return'): </strong><span class="display_currency" data-currency_symbol="true">{{ $contact_details->total_purchase_return }}</span><br>
              <strong>@lang('lang_v1.total_purchase_return_paid'): </strong><span class="display_currency" data-currency_symbol="true">{{ $contact_details->total_return_paid }}</span><br>
              <strong>@lang('lang_v1.total_purchase_return_due'): </strong><span class="display_currency" data-currency_symbol="true">{{ $contact_details->total_purchase_return - $contact_details->total_return_paid }}</span>
            </div>
          </div>
          @elseif(in_array($due_payment_type, ['sell']))
          <div class="col-md-6">
            <div class="well">
              <strong>@lang('sale.customer_name'): </strong>{{ $contact_details->name }}<br>
              <br><br>
            </div>
          </div>
          <div class="col-md-6">
            <div class="well">
              <strong>@lang('report.total_sell'): </strong><span class="display_currency" data-currency_symbol="true">{{ $contact_details->total_invoice }}</span><br>
              <strong>@lang('contact.total_paid'): </strong><span class="display_currency" data-currency_symbol="true">{{ $contact_details->total_paid }}</span><br>
              <strong>@lang('contact.total_sale_due'): </strong><span class="display_currency" data-currency_symbol="true">{{ $contact_details->total_invoice - $contact_details->total_paid }}</span><br>
              @if(!empty($contact_details->opening_balance) || $contact_details->opening_balance != '0.00')
              <strong>@lang('lang_v1.opening_balance'): </strong>
              <span class="display_currency" data-currency_symbol="true">
                {{ $contact_details->opening_balance }}</span><br>
              <strong>@lang('lang_v1.opening_balance_due'): </strong>
              <span class="display_currency" data-currency_symbol="true">
                {{ $ob_due }}</span>
              @endif
            </div>
          </div>
          @elseif(in_array($due_payment_type, ['sell_return']))
          <div class="col-md-6">
            <div class="well">
              <strong>@lang('sale.customer_name'): </strong>{{ $contact_details->name }}<br>
              <br><br>
            </div>
          </div>
          <div class="col-md-6">
            <div class="well">
              <strong>@lang('lang_v1.total_sell_return'): </strong><span class="display_currency" data-currency_symbol="true">{{ $contact_details->total_sell_return }}</span><br>
              <strong>@lang('lang_v1.total_sell_return_paid'): </strong><span class="display_currency" data-currency_symbol="true">{{ $contact_details->total_return_paid }}</span><br>
              <strong>@lang('lang_v1.total_sell_return_due'): </strong><span class="display_currency" data-currency_symbol="true">{{ $contact_details->total_sell_return - $contact_details->total_return_paid }}</span>
            </div>
          </div>
          @endif
        </div>
        <div class="row payment_row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="amount">Amount:*</label>
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-money"></i></span>
                <input type="text" name="amount" value="{{ @num_format($payment_line->amount) }}" class="form-control input_number" required placeholder="Amount" data-rule-max-value="{{ $payment_line->amount }}" data-msg-max-value="@lang('lang_v1.max_amount_to_be_paid_is', ['amount' => $amount_formated])">
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="paid_on">Paid on:*</label>
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input type="text" name="paid_on" value="{{ date('m/d/Y', strtotime($payment_line->paid_on)) }}" class="form-control" readonly required>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="method">Pay Via:*</label>
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-money"></i></span>
                <select name="method" class="form-control select2 payment_types_dropdown" required style="width:100%;">
                  @foreach($payment_types as $key => $value)
                  <option value="{{ $key }}" {{ $payment_line->method == $key ? 'selected' : '' }}>{{ $value }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label for="document">@lang('purchase.attach_document'):</label>
              <input type="file" name="document">
            </div>
          </div>

          @if(!empty($accounts))
          <div class="col-md-6">
            <div class="form-group">
              <label for="account_id">@lang('lang_v1.payment_account'):</label>
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-money"></i></span>
                <select name="account_id" id="account_id" class="form-control select2" style="width:100%;">
                  @foreach($accounts as $key => $value)
                  <option value="{{ $key }}" {{ (!empty($payment_line->account_id) && $payment_line->account_id == $key) ? 'selected' : '' }}>{{ $value }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          @endif
          <div class="clearfix"></div>

          @include('transaction_payment.payment_type_details')
          <div class="col-md-12">
            <div class="form-group">
              <label for="note">Payment Note:</label>
              <textarea name="note" id="note" class="form-control" rows="3">{{ old('note', $payment_line->note) }}</textarea>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">@lang( 'messages.save' )</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
      </div>

    </form>

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
