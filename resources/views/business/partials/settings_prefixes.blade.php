<div class="pos-tab-content">
    <div class="row">
        @foreach ([
        'purchase' => __('lang_v1.purchase_order'),
        'purchase_return' => __('lang_v1.purchase_return'),
        'stock_transfer' => __('lang_v1.stock_transfer'),
        'stock_adjustment' => __('stock_adjustment.stock_adjustment'),
        'sell_return' => __('lang_v1.sell_return'),
        'expense' => __('expense.expenses'),
        'contacts' => __('contact.contacts'),
        'purchase_payment' => __('lang_v1.purchase_payment'),
        'sell_payment' => __('lang_v1.sell_payment'),
        'expense_payment' => __('lang_v1.expense_payment'),
        'business_location' => __('business.business_location'),
        'username' => __('business.username'),
        'subscription' => __('lang_v1.subscription_no')
        ] as $key => $label)
        <div class="col-sm-4">
            <div class="form-group">
                <label for="ref_no_prefixes[{{ $key }}]">{{ $label }}:</label>
                <input type="text" name="ref_no_prefixes[{{ $key }}]" value="{{ old('ref_no_prefixes.' . $key, $business->ref_no_prefixes[$key] ?? '') }}" class="form-control">
            </div>
        </div>
        @endforeach
    </div>
</div>
