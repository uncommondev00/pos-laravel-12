<div class="row">
    <input type="hidden" class="payment_row_index" value="{{ $row_index }}">
    @php
    $col_class = 'col-md-6';
    if(!empty($accounts)){
    $col_class = 'col-md-4';
    }
    @endphp

    <!-- Amount Field -->
    <div class="{{ $col_class }}">
        <div class="form-group">
            <label for="amount_{{ $row_index }}">@lang('sale.amount')<span class="required">*</span></label>
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-money"></i>
                </span>
                <input type="text"
                    name="payment[{{ $row_index }}][amount]"
                    id="amount_{{ $row_index }}"
                    class="form-control payment-amount input_number"
                    value="{{ @num_format($payment_line['amount']) }}"
                    required
                    placeholder="@lang('sale.amount')">
            </div>
        </div>
    </div>

    <!-- Payment Method Field -->
    <div class="{{ $col_class }}">
        <div class="form-group">
            <label for="method_{{ $row_index }}">@lang('lang_v1.payment_method')<span class="required">*</span></label>
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-money"></i>
                </span>
                <select name="payment[{{ $row_index }}][method]"
                    id="method_{{ $row_index }}"
                    class="form-control col-md-12 payment_types_dropdown"
                    required
                    style="width:100%;">
                    @foreach($payment_types as $key => $value)
                    <option value="{{ $key }}" {{ $payment_line['method'] == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Payment Account Field -->
    @if(!empty($accounts))
    <div class="{{ $col_class }}">
        <div class="form-group">
            <label for="account_{{ $row_index }}">@lang('lang_v1.payment_account'):</label>
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-money"></i>
                </span>
                <select name="payment[{{ $row_index }}][account_id]"
                    id="account_{{ $row_index }}"
                    class="form-control select2"
                    style="width:100%;">
                    @foreach($accounts as $key => $value)
                    <option value="{{ $key }}" {{ !empty($payment_line['account_id']) && $payment_line['account_id'] == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    @endif

    <div class="clearfix"></div>
    @include('sale_pos.partials.payment_type_details')

    <!-- Payment Note Field -->
    <div class="col-md-12">
        <div class="form-group">
            <label for="note_{{ $row_index }}">@lang('sale.payment_note'):</label>
            <textarea name="payment[{{ $row_index }}][note]"
                id="note_{{ $row_index }}"
                class="form-control"
                rows="3">{{ $payment_line['note'] }}</textarea>
        </div>
    </div>

    <!-- Points Section -->
    <div class="points_section hide">
        <div class="col-md-6">
            <div class="form-group">
                <label for="input_total_points_{{ $row_index }}">@lang('Total Points'):</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-credit-card"></i>
                    </span>
                    <input type="text"
                        name="input_total_points"
                        id="input_total_points_{{ $row_index }}"
                        class="form-control input_total_points"
                        value="0">
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="points_to_use_{{ $row_index }}">@lang('Points to Use'):</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-credit-card"></i>
                    </span>
                    <input type="text"
                        name="points_to_use"
                        id="points_to_use_{{ $row_index }}"
                        class="form-control input_points_to_use"
                        value="0">
                </div>
            </div>
        </div>
    </div>
</div>
