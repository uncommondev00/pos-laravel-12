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
                <div class="row mb-3">
                    <div class="col-sm-12 col-md-6">
                        <div class="dataTables_length">
                            <label>
                                Show 
                                <select wire:model.live="perPage" class="form-control form-control-sm" style="width: auto; display: inline-block;">
                                    @foreach($perPageOptions as $option)
                                        @if($option === -1)
                                            <option value="{{ $option }}">All</option>
                                        @else
                                            <option value="{{ $option }}">{{ $option }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                entries
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-6 text-right">
                        <div class="dataTables_filter">
                            <label>
                                Search:
                                <div class="input-group" style="display: inline-flex; width: auto;">
                                    <input type="search" 
                                        wire:model.live.debounce.500ms="search" 
                                        class="form-control form-control-sm" 
                                        placeholder="Type to search..."
                                        style="width: 200px;">
                                    @if($search)
                                        <div class="input-group-append">
                                            <button wire:click="$set('search', '')" class="btn btn-sm btn-default">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
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
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" role="status" aria-live="polite">
                            Showing {{ $customer_groups->firstItem() ?? 0 }} to {{ $customer_groups->lastItem() ?? 0 }} of {{ $customer_groups->total() }} entries
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="dataTables_paginate paging_simple_numbers">
                            <ul class="pagination" style="margin: 2px 0; white-space: nowrap;">
                                {{-- Previous Page Link --}}
                                <li class="paginate_button page-item {{ $customer_groups->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" wire:click.prevent="previousPage" href="#" tabindex="-1">Previous</a>
                                </li>

                                {{-- Pagination Elements --}}
                                @for ($i = 1; $i <= $customer_groups->lastPage(); $i++)
                                    @if ($i == $customer_groups->currentPage())
                                        <li class="paginate_button page-item active">
                                            <a class="page-link" href="#">{{ $i }}</a>
                                        </li>
                                    @elseif ($i == 1 || $i == $customer_groups->lastPage() || abs($customer_groups->currentPage() - $i) <= 2)
                                        <li class="paginate_button page-item">
                                            <a class="page-link" wire:click.prevent="gotoPage({{ $i }})" href="#">{{ $i }}</a>
                                        </li>
                                    @elseif (abs($customer_groups->currentPage() - $i) == 3)
                                        <li class="paginate_button page-item disabled">
                                            <a class="page-link" href="#">...</a>
                                        </li>
                                    @endif
                                @endfor

                                {{-- Next Page Link --}}
                                <li class="paginate_button page-item {{ !$customer_groups->hasMorePages() ? 'disabled' : '' }}">
                                    <a class="page-link" wire:click.prevent="nextPage" href="#">Next</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
        @endcan
    @endcomponent

    <div class="modal fade customer_groups_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>

</section>
<!-- /.content -->
</div>
