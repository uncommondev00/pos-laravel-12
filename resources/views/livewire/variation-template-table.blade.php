<div>
    {{-- In work, do what you enjoy. --}}
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang('product.variations')
        <small>@lang('lang_v1.manage_product_variations')</small>
    </h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __('lang_v1.all_variations')])
        @slot('tool')
            <div class="box-tools">
                <button type="button" class="btn btn-block btn-primary btn-modal" 
                data-href="{{route('variation-templates.create')}}" 
                data-container=".variation_modal">
                <i class="fa fa-plus"></i> @lang('messages.add')</button>
            </div>
        @endslot
        <div class="table-responsive">
           @include('includes.table-controls', [
                'perPageOptions' => $perPageOptions,
                'search' => $search
           ])
            <table class="table table-bordered table-striped" id="">
                <thead>
                    <tr>
                        <th>@lang('product.variations')</th>
                        <th>@lang('lang_v1.values')</th>
                        <th>@lang('messages.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($variations as $variation)
                        <tr>
                            <td>{{ $variation->name }}</td>
                            <td>
                                {{ $variation->values->pluck('name')->implode(', ') }}
                            </td>
                            <td>
                                <button data-href="{{ route('variation-templates.edit', [$variation->id]) }}" 
                                        class="btn btn-xs btn-primary edit_variation_button">
                                    <i class="glyphicon glyphicon-edit"></i> 
                                    @lang("messages.edit")
                                </button>
                                
                                @if(empty($variation->total_pv))
                                    <button data-href="{{ route('variation-templates.destroy', [$variation->id]) }}" 
                                            class="btn btn-xs btn-danger delete_variation_button">
                                        <i class="glyphicon glyphicon-trash"></i> 
                                        @lang("messages.delete")
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @include('includes.pagination', ['paginator' => $variations])
    @endcomponent

    <div class="modal fade variation_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>

</section>
<!-- /.content -->
</div>
