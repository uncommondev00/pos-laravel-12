<div>

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('business.business_locations')
            <small>@lang('business.manage_your_business_locations')</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        @component('components.widget', ['class' => 'box-primary', 'title' => __('business.all_your_business_locations')])
        @slot('tool')
        <div class="box-tools">
            <button type="button" class="btn btn-block btn-primary btn-modal"
                data-href="{{ route('business-location.create') }}"
                data-container=".location_add_modal">
                <i class="fa fa-plus"></i> @lang('messages.add')
            </button>
        </div>
        @endslot

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>@lang('invoice.name')</th>
                        <th>@lang('lang_v1.location_id')</th>
                        <th>@lang('business.landmark')</th>
                        <th>@lang('business.city')</th>
                        <th>@lang('business.zip_code')</th>
                        <th>@lang('business.state')</th>
                        <th>@lang('business.country')</th>
                        <th>@lang('invoice.invoice_scheme')</th>
                        <th>@lang('invoice.invoice_layout')</th>
                        <th>@lang('messages.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($locations as $location)
                    <tr>
                        <td>{{ $location->name }}</td>
                        <td>{{ $location->location_id }}</td>
                        <td>{{ $location->landmark }}</td>
                        <td>{{ $location->city }}</td>
                        <td>{{ $location->zip_code }}</td>
                        <td>{{ $location->state }}</td>
                        <td>{{ $location->country }}</td>
                        <td>{{ $location->invoice_scheme }}</td>
                        <td>{{ $location->invoice_layout }}</td>
                        <td>
                            <button type="button" data-href="{{ route('business-location.edit', [$location->id]) }}"
                                class="btn btn-xs btn-primary btn-modal" data-container=".location_edit_modal">
                                <i class="glyphicon glyphicon-edit"></i> @lang('messages.edit')
                            </button>

                            <a href="{{ route('location.settings', [$location->id]) }}" class="btn btn-success btn-xs">
                                <i class="fa fa-wrench"></i> @lang('messages.settings')
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endcomponent

        <!-- Modals for adding and editing locations -->
        <div class="modal fade location_add_modal" tabindex="-1" role="dialog"
            aria-labelledby="gridSystemModalLabel">
        </div>
        <div class="modal fade location_edit_modal" tabindex="-1" role="dialog"
            aria-labelledby="gridSystemModalLabel">
        </div>

    </section>
</div>
