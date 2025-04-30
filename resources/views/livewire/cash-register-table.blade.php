<div>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>{{ __('report.register_report') }}</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Filters Section -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ __('report.filters') }}</h3>
                    </div>
                    <div class="box-body">
                        <form action="{{ route('reports.getStockReport') }}" method="get" id="register_report_filter_form">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="register_user_id">{{ __('report.user') }}:</label>
                                        <select name="register_user_id" class="form-control select2" style="width: 100%" placeholder="{{ __('report.all_users') }}">
                                            <option value="">{{ __('report.all_users') }}</option>
                                            @foreach ($users as $userId => $userName)
                                            <option value="{{ $userId }}">{{ $userName }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="register_status">{{ __('sale.status') }}:</label>
                                        <select name="register_status" class="form-control select2" style="width: 100%" placeholder="{{ __('report.all') }}">
                                            <option value="">{{ __('report.all') }}</option>
                                            <option value="open">{{ __('cash_register.open') }}</option>
                                            <option value="close">{{ __('cash_register.close') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 text-right">
                                    <button type="submit" class="btn btn-primary">{{ __('messages.apply') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Table Section -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="register_report_table">
                                <thead>
                                    <tr>
                                        <th>@lang('report.open_time')</th>
                                        <th>@lang('report.close_time')</th>
                                        <th>@lang('report.user')</th>
                                        <th>@lang('cash_register.total_card_slips')</th>
                                        <th>@lang('cash_register.total_cheques')</th>
                                        <th>@lang('cash_register.total_cash')</th>
                                        <th>@lang('messages.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($registers as $register)
                                    <tr>
                                        <td>{{ $register->created_at }}</td>
                                        <td>{{ $register->closed_at }}</td>
                                        <td>{!! $register->user_name !!}</td>

                                        <td>
                                            @if($register->status == 'close')
                                            {{ $register->total_card_slips }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($register->status == 'close')
                                            {{ $register->total_cheques }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($register->status == 'close')
                                            <span class="display_currency" data-currency_symbol="true">{{ $register->closing_amount }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button wire:click="viewRegister({{ $register->id }})" class="btn btn-info btn-sm">
                                                <i class="fa fa-external-link"></i> @lang("messages.view")
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8">No records found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal (View Register Details) -->
    <div class="modal fade view_register" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
</div>
