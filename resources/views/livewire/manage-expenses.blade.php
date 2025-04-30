<div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped" style="width: 100%;">
            <thead>
                <tr>
                    <th>@lang('messages.date')</th>
                    <th>@lang('purchase.ref_no')</th>
                    <th>@lang('expense.expense_category')</th>
                    <th>@lang('business.location')</th>
                    <th>@lang('sale.payment_status')</th>
                    <th>@lang('sale.total_amount')</th>
                    <th>@lang('expense.expense_for')</th>
                    <th>@lang('expense.expense_note')</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expenses as $expense)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($expense->transaction_date)->format('d/m/Y') }}</td>
                    <td>{{ $expense->ref_no }}</td>
                    <td>{{ $expense->category }}</td>
                    <td>{{ $expense->location_name }}</td>
                    <td>{{ $expense->expense_for }}</td>
                    <td><span class="display_currency">{{ number_format($expense->final_total, 2) }}</span></td>
                    <td>{!! view('partials.payment-status', ['status' => $expense->payment_status, 'id' => $expense->id]) !!}</td>
                    <td><span class="display_currency">{{ number_format($expense->amount_paid, 2) }}</span></td>
                    <td><span class="display_currency">{{ number_format($expense->final_total - $expense->amount_paid, 2) }}</span></td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-sm btn-primary">Edit</a>
                            @if($expense->document)
                            <a href="{{ asset('uploads/documents/' . $expense->document) }}" class="btn btn-sm btn-success" download>Download</a>
                            @endif
                            <button wire:click="deleteExpense({{ $expense->id }})" class="btn btn-sm btn-danger">Delete</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-gray font-17 text-center footer-total">
                    <td colspan="4"><strong>@lang('sale.total'):</strong></td>
                    <td id="er_footer_payment_status_count"></td>
                    <td><span class="display_currency" id="footer_expense_total" data-currency_symbol="true"></span></td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
