<div>
    {{-- Care about people's approval and you will be their prisoner. --}}
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'category.categories' )
        <small>@lang( 'category.manage_your_categories' )</small>
    </h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'category.manage_your_categories' )])
        @can('category.create')
            @slot('tool')
                <div class="box-tools">
                    <button type="button" class="btn btn-block btn-primary btn-modal" 
                    data-href="{{route('categories.create')}}" 
                    data-container=".category_modal">
                    <i class="fa fa-plus"></i> @lang( 'messages.add' )</button>
                </div>
            @endslot
        @endcan
        @can('category.view')
            <div class="table-responsive">
                <input type="text" wire:model.live="search" class="form-control mb-3" placeholder="Search category...">
                <table class="table table-bordered table-striped" id="">
                    <thead>
                        <tr>
                            <th>@lang( 'category.category' )</th>
                            <th>@lang( 'category.code' )</th>
                            <th>@lang( 'messages.action' )</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>{{ $this->getFormattedName($category) }}</td>
                                <td>{{ $category->short_code }}</td>
                                <td>
                                    @can('category.update')
                                        <button data-href="{{ route('categories.edit', [$category->id]) }}" 
                                                class="btn btn-xs btn-primary edit_category_button">
                                            <i class="glyphicon glyphicon-edit"></i> 
                                            {{ __("messages.edit") }}
                                        </button>
                                        &nbsp;
                                    @endcan
                                    
                                    @can('category.delete')
                                        <button data-href="{{ route('categories.destroy', [$category->id]) }}" 
                                                class="btn btn-xs btn-danger delete_category_button">
                                            <i class="glyphicon glyphicon-trash"></i> 
                                            {{ __("messages.delete") }}
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-sm-12">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        @endcan
    @endcomponent

    <div class="modal fade category_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>

</section>
<!-- /.content -->
</div>
