<div>
    {{-- Do your work, then step back. --}}
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'lang_v1.customer_groups' )</h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'lang_v1.all_your_customer_groups' )])
        @can('customer.create')
            @slot('tool')
                <div class="box-tools">
                    <button type="button" class="btn btn-block btn-primary btn-modal" 
                        data-href="{{route('customer-group.create')}}" 
                        data-container=".customer_groups_modal">
                        <i class="fa fa-plus"></i> @lang( 'messages.add' )</button>
                </div>
            @endslot
        @endcan
        @can('customer.view')
            <div class="table-responsive">
                <input type="text" wire:model.live="search" class="form-control mb-3" placeholder="Search customer groups...">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>@lang( 'lang_v1.customer_group_name' )</th>
                            <th>@lang( 'lang_v1.calculation_percentage' )</th>
                            <th>@lang( 'messages.action' )</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ( $customer_groups as $customer_group)
                            <tr>
                                <td>{{ $customer_group->name }}</td>
                                <td>{{ $customer_group->calculation_percentage }} %</td>
                                <td>
                                    @can("customer.update")
                                    <button data-href="{{route('customer-group.edit', [$customer_group->id])}}" class="btn btn-xs btn-primary edit_customer_group_button"><i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")</button>
                                @endcan

                                @can("customer.delete")
                                    <button data-href="{{action('customer-group.destroy', [$customer_group->id])}}" class="btn btn-xs btn-danger delete_customer_group_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>
                                @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No Data.</td>
                            </tr>
                        @endforelse
                </table>
                </div>
        @endcan
    @endcomponent

    <div class="modal fade customer_groups_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>

</section>
<!-- /.content -->
</div>
