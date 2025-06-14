<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}

    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'lang_v1.sales_commission_agents' )
    </h1>
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary'])
        @can('user.create')
            @slot('tool')
                <div class="box-tools">
                    <button type="button" class="btn btn-primary btn-modal pull-right"
                        data-href="{{route('sales-commission-agents.create')}}" data-container=".commission_agent_modal"><i class="fa fa-plus"></i> @lang( 'messages.add' )</button>
                </div>
            @endslot
            
        @endcan
        
        @can('user.view')
            <div class="table-responsive">
                @include('includes.table-controls', [
                       'perPageOptions' => $perPageOptions,
                       'search' => $search
                   ])
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>@lang( 'user.name' )</th>
                            <th>@lang( 'business.email' )</th>
                            <th>@lang( 'lang_v1.contact_no' )</th>
                            <th>@lang( 'business.address' )</th>
                            <th>@lang( 'lang_v1.cmmsn_percent' )</th>
                            <th>@lang( 'messages.action' )</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $sales_commission_agent)
                            <tr>
                                <td>{{ $sales_commission_agent->full_name }}</td>
                                <td>{{ $sales_commission_agent->email }}</td>
                                <td>{{ $sales_commission_agent->contact_no }}</td>
                                <td>{{ $sales_commission_agent->address }}</td>
                                <td>{{ $sales_commission_agent->cmmsn_percent }} %</td>
                                <td>
                                    @can('user.update')
                                        <button type="button" class="btn btn-xs btn-primary btn-modal"
                                            data-href="{{route('sales-commission-agents.edit', $sales_commission_agent->id)}}"
                                            data-container=".commission_agent_modal"><i class="fa fa-edit"></i> @lang( 'messages.edit' )</button>
                                    @endcan
                                    @can('user.delete')
                                        <button type="button" class="btn btn-xs btn-danger delete_commsn_agnt_button" data-href="{{route('sales-commission-agents.destroy', $sales_commission_agent->id)}}"><i class="fa fa-trash"></i> @lang( 'messages.delete' )</button>                                        
                                    @endcan
                                </td>                               
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No Data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @include('includes.pagination', ['paginator' => $users])
        @endcan
    @endcomponent
    
    <div class="modal fade commission_agent_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>
</section>
<!-- /.content -->
</div>
