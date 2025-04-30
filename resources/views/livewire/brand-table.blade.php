<div>
    {{-- Success is as dangerous as failure. --}}
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'brand.brands' )
        <small>@lang( 'brand.manage_your_brands' )</small>
    </h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'brand.all_your_brands' )])
        @can('brand.create')
            @slot('tool')
                <div class="box-tools">
                    <button type="button" class="btn btn-block btn-primary btn-modal" 
                        data-href="{{route('brands.create')}}" 
                        data-container=".brands_modal">
                        <i class="fa fa-plus"></i> @lang( 'messages.add' )</button>
                </div>
            @endslot
        @endcan
        @can('brand.view')
            <div class="table-responsive">
                @include('includes.table-controls', [
                    'perPageOptions' => $perPageOptions,
                    'search' => $search,
                ])
                <table class="table table-bordered table-striped" id="">
                    <thead>
                        <tr>
                            <th>@lang( 'brand.brands' )</th>
                            <th>@lang( 'brand.note' )</th>
                            <th>@lang( 'messages.action' )</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($brands as $brand)
                            <tr>
                                <td>{{ $brand->name }}</td>
                                <td>{{ $brand->description }}</td>
                                <td>
                                    @can('brand.update')
                                        <button data-href="{{ route('brands.edit', [$brand->id]) }}" 
                                                class="btn btn-xs btn-primary edit_brand_button">
                                            <i class="glyphicon glyphicon-edit"></i> 
                                            {{ __("messages.edit") }}
                                        </button>
                                        &nbsp;
                                    @endcan
                                    
                                    @can('brand.delete')
                                        <button data-href="{{ route('brands.destroy', [$brand->id]) }}" 
                                                class="btn btn-xs btn-danger delete_brand_button">
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
            @include('includes.pagination', ['paginator' => $brands])
        @endcan
    @endcomponent

    <div class="modal fade brands_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>

</section>
<!-- /.content -->
</div>
