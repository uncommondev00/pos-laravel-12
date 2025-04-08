<div class="col-md-3">
    <div class="form-group">
        <label for="sell_list_filter_location_id">{{ __('purchase.business_location') }}:</label>
        <select id="sell_list_filter_location_id" name="sell_list_filter_location_id" class="form-control select2" style="width:100%">
            <option value="">{{ __('lang_v1.all') }}</option>
            @foreach($business_locations as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="sell_list_filter_customer_id">{{ __('contact.customer') }}:</label>
        <select id="sell_list_filter_customer_id" name="sell_list_filter_customer_id" class="form-control select2" style="width:100%">
            <option value="">{{ __('lang_v1.all') }}</option>
            @foreach($customers as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="sell_list_filter_payment_status">{{ __('purchase.payment_status') }}:</label>
        <select id="sell_list_filter_payment_status" name="sell_list_filter_payment_status" class="form-control select2" style="width:100%">
            <option value="">{{ __('lang_v1.all') }}</option>
            <option value="paid">{{ __('lang_v1.paid') }}</option>
            <option value="due">{{ __('lang_v1.due') }}</option>
            <option value="partial">{{ __('lang_v1.partial') }}</option>
        </select>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="sell_list_filter_date_range">{{ __('report.date_range') }}:</label>
        <input type="text" id="sell_list_filter_date_range" name="sell_list_filter_date_range" class="form-control" placeholder="{{ __('lang_v1.select_a_date_range') }}" readonly>
    </div>
</div>
