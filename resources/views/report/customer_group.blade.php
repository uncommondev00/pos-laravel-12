@extends('layouts.app')
@section('title', __('lang_v1.customer_groups_report'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>{{ __('lang_v1.customer_groups_report')}}</h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            @component('components.filters', ['title' => __('report.filters')])
            <form action="{{ route('reports.getCustomerGroup') }}" method="get" id="cg_report_filter_form">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="cg_customer_group_id">{{ __( 'lang_v1.customer_group_name' ) }}:</label>
                        <select name="cg_customer_group_id" class="form-control select2" style="width:100%" id="cg_customer_group_id">
                            @foreach ($customer_group as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="cg_location_id">{{ __('purchase.business_location') }}:</label>
                        <select name="cg_location_id" class="form-control select2" style="width:100%">
                            @foreach ($business_locations as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="form-group">
                        <label for="cg_date_range">{{ __('report.date_range') }}:</label>
                        @php
                        use Carbon\Carbon;

                        $startDate = Carbon::now()->startOfMonth()->format('Y-m-d'); // or your desired format
                        $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
                        @endphp

                        <input type="text" name="date_range" id="cg_date_range" class="form-control"
                            placeholder="{{ __('lang_v1.select_a_date_range') }}"
                            value="{{ $startDate . ' ~ ' . $endDate }}"
                            readonly>
                    </div>
                </div>
            </form>
            @endcomponent
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @component('components.widget', ['class' => 'box-primary'])
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="cg_report_table">
                    <thead>
                        <tr>
                            <th>@lang('lang_v1.customer_group')</th>
                            <th>@lang('report.total_sell')</th>
                        </tr>
                    </thead>
                </table>
            </div>
            @endcomponent
        </div>
    </div>
</section>
<!-- /.content -->

@endsection

@section('javascript')

<script type="text/javascript">
    $(document).ready(function() {
        if ($('#cg_date_range').length == 1) {
            $('#cg_date_range').daterangepicker({
                ranges: ranges,
                autoUpdateInput: false,
                locale: {
                    format: moment_date_format
                }
            });
            $('#cg_date_range').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format(moment_date_format) + ' ~ ' + picker.endDate.format(moment_date_format));
                cg_report_table.ajax.reload();
            });

            $('#cg_date_range').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                cg_report_table.ajax.reload();
            });
        }

        cg_report_table = $('#cg_report_table').DataTable({
            processing: true,
            serverSide: true,
            "ajax": {
                "url": "/reports/customer-group",
                "data": function(d) {
                    d.location_id = $('#cg_location_id').val();
                    d.customer_group_id = $('#cg_customer_group_id').val();
                    d.start_date = $('#cg_date_range').data('daterangepicker').startDate.format('YYYY-MM-DD');
                    d.end_date = $('#cg_date_range').data('daterangepicker').endDate.format('YYYY-MM-DD');
                }
            },
            columns: [{
                    data: 'name',
                    name: 'CG.name'
                },
                {
                    data: 'total_sell',
                    name: 'total_sell',
                    searchable: false
                }
            ],
            "fnDrawCallback": function(oSettings) {
                __currency_convert_recursively($('#cg_report_table'));
            }
        });
        //Customer Group report filter
        $('select#cg_location_id, select#cg_customer_group_id, #cg_date_range').change(function() {
            cg_report_table.ajax.reload();
        });
    })
</script>
@endsection
