<div>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'unit.units' )
        <small>@lang( 'unit.manage_your_units' )</small>
    </h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'unit.all_your_units' )])
        @can('unit.create')
            @slot('tool')
                <div class="box-tools">
                    <button type="button" class="btn btn-block btn-primary btn-modal" 
                        data-href="{{route('units.create')}}" 
                        data-container=".unit_modal">
                        <i class="fa fa-plus"></i> @lang( 'messages.add' )</button>
                </div>
            @endslot
        @endcan
        @can('unit.view')
            <div class="table-responsive">
                @include('includes.table-controls', [
                    'perPageOptions' => $perPageOptions,
                    'search' => $search,
                ])
                <table class="table table-bordered table-striped" id="">
                    <thead>
                        <tr>
                            <th>@lang( 'unit.name' )</th>
                            <th>@lang( 'unit.short_name' )</th>
                            <th>@lang( 'unit.allow_decimal' ) @show_tooltip(__('tooltip.unit_allow_decimal'))</th>
                            <th>@lang( 'messages.action' )</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($units as $unit)
                            <tr>
                                <td>{{ $this->getFormattedName($unit) }}</td>
                                <td>{{ $unit->short_name }}</td>
                                <td>{{ $unit->allow_decimal ? __('messages.yes') : __('messages.no') }}</td>
                                <td>
                                    @can('unit.update')
                                        <button data-href="{{ route('units.edit', [$unit->id]) }}" 
                                                class="btn btn-xs btn-primary edit_unit_button">
                                            <i class="glyphicon glyphicon-edit"></i> 
                                            {{ __("messages.edit") }}
                                        </button>
                                        &nbsp;
                                    @endcan
                                    
                                    @can('unit.delete')
                                        <button data-href="{{ route('units.destroy', [$unit->id]) }}" 
                                                class="btn btn-xs btn-danger delete_unit_button">
                                            <i class="glyphicon glyphicon-trash"></i> 
                                            {{ __("messages.delete") }}
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
            </div>

            @include('includes.pagination', ['paginator' => $units])
        @endcan
    @endcomponent

    <div class="modal fade unit_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>

</section>
<!-- /.content -->
</div>
