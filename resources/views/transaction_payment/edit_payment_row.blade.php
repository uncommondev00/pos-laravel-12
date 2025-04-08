<div class="modal-dialog" role="document">
  <div class="modal-content">
      <form action="{{ route('payments.update', [$payment_line->id]) }}" 
            method="POST" 
            id="transaction_payment_add_form" 
            enctype="multipart/form-data">
          @csrf
          @method('PUT')

          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title">@lang('purchase.edit_payment')</h4>
          </div>

          <div class="modal-body">
              <div class="row">
                  @if(!empty($transaction->contact))
                      <div class="col-md-4">
                          <div class="well">
                              <strong>@lang('purchase.supplier'): </strong>{{ $transaction->contact->name }}<br>
                              <strong>@lang('business.business'): </strong>{{ $transaction->contact->supplier_business_name }}
                          </div>
                      </div>
                  @endif
                  @if($transaction->type != 'opening_balance')
                      <div class="col-md-4">
                          <div class="well">
                              <strong>@lang('purchase.ref_no'): </strong>{{ $transaction->ref_no }}<br>
                              <strong>@lang('purchase.location'): </strong>{{ $transaction->location->name }}
                          </div>
                      </div>
                      <div class="col-md-4">
                          <div class="well">
                              <strong>@lang('purchase.purchase_total'): </strong>
                              <span class="display_currency" data-currency_symbol="true">{{ $transaction->final_total }}</span><br>
                              <strong>@lang('purchase.payment_note'): </strong>
                              @if(!empty($transaction->additional_notes))
                                  {{ $transaction->additional_notes }}
                              @else
                                  --
                              @endif
                          </div>
                      </div>
                  @endif
              </div>
              <div class="row payment_row">
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="amount">Amount:*</label>
                          <div class="input-group">
                              <span class="input-group-addon">
                                  <i class="fa fa-money"></i>
                              </span>
                              <input type="text" 
                                     name="amount" 
                                     id="amount"
                                     value="{{ @num_format($payment_line->amount) }}" 
                                     class="form-control input_number" 
                                     required 
                                     placeholder="Amount">
                          </div>
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="paid_on">Paid on:*</label>
                          <div class="input-group">
                              <span class="input-group-addon">
                                  <i class="fa fa-calendar"></i>
                              </span>
                              <input type="text" 
                                     name="paid_on" 
                                     id="paid_on"
                                     value="{{ date('m/d/Y', strtotime($payment_line->paid_on)) }}" 
                                     class="form-control" 
                                     readonly 
                                     required>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="method">Pay Via:*</label>
                          <div class="input-group">
                              <span class="input-group-addon">
                                  <i class="fa fa-money"></i>
                              </span>
                              <select name="method" 
                                      id="method"
                                      class="form-control select2 payment_types_dropdown" 
                                      required 
                                      style="width:100%;">
                                  @foreach($payment_types as $key => $value)
                                      <option value="{{ $key }}" {{ $payment_line->method == $key ? 'selected' : '' }}>
                                          {{ $value }}
                                      </option>
                                  @endforeach
                              </select>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="document">@lang('purchase.attach_document'):</label>
                          <input type="file" name="document" id="document">
                          <p class="help-block">@lang('lang_v1.previous_file_will_be_replaced')</p>
                      </div>
                  </div>
                  @if(!empty($accounts))
                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="account_id">@lang('lang_v1.payment_account'):</label>
                              <div class="input-group">
                                  <span class="input-group-addon">
                                      <i class="fa fa-money"></i>
                                  </span>
                                  <select name="account_id" 
                                          id="account_id"
                                          class="form-control select2" 
                                          style="width:100%;">
                                      @foreach($accounts as $key => $value)
                                          <option value="{{ $key }}" {{ (!empty($payment_line->account_id) && $payment_line->account_id == $key) ? 'selected' : '' }}>
                                              {{ $value }}
                                          </option>
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
                          <textarea name="note" 
                                    id="note"
                                    class="form-control" 
                                    rows="3">{{ $payment_line->note }}</textarea>
                      </div>
                  </div>
              </div>
          </div>

          <div class="modal-footer">
              <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
          </div>
      </form>
  </div>
</div>