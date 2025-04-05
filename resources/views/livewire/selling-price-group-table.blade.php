<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'lang_v1.selling_price_group' )
    </h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'lang_v1.all_selling_price_group' )])
        @slot('tool')
            <div class="box-tools">
                <button type="button" class="btn btn-block btn-primary btn-modal" 
                    data-href="{{route('selling-price-group.create')}}" 
                    data-container=".view_modal">
                    <i class="fa fa-plus"></i> @lang( 'messages.add' )</button>
            </div>
        @endslot
        <div class="table-responsive">
            <input type="text" wire:model.live="search" class="form-control mb-3" placeholder="Search selling price groups...">
            <table class="table table-bordered table-striped" id="">
                <thead>
                    <tr>
                        <th>@lang( 'lang_v1.name' )</th>
                        <th>@lang( 'lang_v1.description' )</th>
                        <th>@lang( 'messages.action' )</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($price_groups as $price_group)
                        <tr>
                            <td>{{ $price_group->name }}</td>
                            <td>{{ $price_group->description }}</td>
                            <td>
                                <button data-href="{{ route('selling-price-group.edit', [$price_group->id]) }}" 
                                        class="btn btn-xs btn-primary btn-modal" 
                                        data-container=".view_modal">
                                    <i class="glyphicon glyphicon-edit"></i> 
                                    {{ __("messages.edit") }}
                                </button>
                                &nbsp;
                                <button data-href="{{ route('selling-price-group.destroy', [$price_group->id]) }}" 
                                        class="btn btn-xs btn-danger delete_spg_button">
                                    <i class="glyphicon glyphicon-trash"></i> 
                                    {{ __("messages.delete") }}
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row">
                <div class="col-sm-12">
                    {{ $price_groups->links() }}
                </div>
            </div>
        </div>
    @endcomponent
    
    <div class="modal fade brands_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>

</section>
<!-- /.content -->
</div>
