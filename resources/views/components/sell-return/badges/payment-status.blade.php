<a href="{{ route('payments.show', $id) }}" 
   class="view_payment_modal payment-status-label"
   data-orig-value="{{ $status }}"
   data-status-name="@lang('lang_v1.' . $status)">
    <span class="label @payment_status($status)">
        @lang('lang_v1.' . $status)
    </span>
</a>