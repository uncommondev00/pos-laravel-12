<div>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang( 'tax_rate.tax_rates' )
            <small>@lang( 'tax_rate.manage_your_tax_rates' )</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        @component('components.widget', ['class' => 'box-primary', 'title' => __( 'tax_rate.all_your_tax_rates' )])
        @can('tax_rate.create')
        @slot('tool')
        <div class="box-tools">
            <button type="button" class="btn btn-block btn-primary btn-modal"
                data-href="{{route('tax-rates.create')}}"
                data-container=".tax_rate_modal">
                <i class="fa fa-plus"></i> @lang( 'messages.add' )</button>
        </div>
        @endslot
        @endcan
        @can('tax_rate.view')
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>@lang( 'tax_rate.name' )</th>
                        <th>@lang( 'tax_rate.rate' )</th>
                        <th>@lang( 'messages.action' )</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tax_rates as $tax_rate)
                    <tr>
                        <td>{{$tax_rate->name}}</td>
                        <td>{{$tax_rate->amount}}%</td>
                        <td>
                            @can('tax_rate.update')
                            <button data-href="{{route('tax-rates.edit', [$tax_rate->id])}}"
                                class="btn btn-xs btn-primary edit_tax_rate_button"><i
                                    class="glyphicon glyphicon-edit"></i> @lang( 'messages.edit' )</button>
                            @endcan
                            @can('tax_rate.delete')
                            <button data-href="{{route('tax-rates.destroy', [$tax_rate->id])}}"
                                class="btn btn-xs btn-danger delete_tax_rate_button"><i
                                    class="glyphicon glyphicon-trash"></i> @lang( 'messages.delete' )</button>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @include('includes.pagination', ['paginator' => $tax_rates])
        </div>
        @endcan
        @endcomponent

        @component('components.widget', ['class' => 'box-primary'])
        @slot('title')
        @lang( 'tax_rate.tax_groups' ) ( @lang('lang_v1.combination_of_taxes') ) @show_tooltip(__('tooltip.tax_groups'))
        @endslot
        @can('tax_rate.create')
        @slot('tool')
        <div class="box-tools">
            <button type="button" class="btn btn-block btn-primary btn-modal"
                data-href="{{route('group-taxes.create')}}"
                data-container=".tax_group_modal">
                <i class="fa fa-plus"></i> @lang( 'messages.add' )</button>
        </div>
        @endslot
        @endcan
        @can('tax_rate.view')
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>@lang( 'tax_rate.name' )</th>
                        <th>@lang( 'tax_rate.rate' )</th>
                        <th>@lang( 'tax_rate.sub_taxes' )</th>
                        <th>@lang( 'messages.action' )</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tax_groups as $tax_group)
                    <tr>
                        <td>{{ $tax_group->name }}</td>

                        <td>{{ $tax_group->sub_taxes->sum('amount') }}%</td>

                        <td>

                            @foreach($tax_group->sub_taxes as $tax)
                            {{ $tax->name }} ({{ $tax->amount }}%)
                            @endforeach
                        </td>
                        <td>
                            @can('tax_rate.update')
                            <button data-href="{{route('group-taxes.edit', [$tax_group->id])}}"
                                class="btn btn-xs btn-primary btn-modal" data-container=".tax_group_modal"><i
                                    class="glyphicon glyphicon-edit"></i> @lang( 'messages.edit' )</button>
                            @endcan
                            @can('tax_rate.delete')
                            <button data-href="{{route('group-taxes.destroy', [$tax_group->id])}}"
                                class="btn btn-xs btn-danger delete_tax_group_button"><i
                                    class="glyphicon glyphicon-trash"></i> @lang( 'messages.delete' )</button>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>


        </div>
        @endcan
        @endcomponent

        <div class="modal fade tax_rate_modal" tabindex="-1" role="dialog"
            aria-labelledby="gridSystemModalLabel">
        </div>
        <div class="modal fade tax_group_modal" tabindex="-1" role="dialog"
            aria-labelledby="gridSystemModalLabel">
        </div>

    </section>
    <!-- /.content -->
</div>
