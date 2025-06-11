@extends('layouts.app')
@section('title', __('sale.discount'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'sale.discount' )
    </h1>

</section>

<!-- Main content -->
<section class="content">

    <div class="box">
        <div class="box-header">
            <h3 class="box-title">@lang( 'lang_v1.all_your_discounts' )</h3>
            @can('brand.create')
            <div class="box-tools">
                <button type="button" class="btn btn-block btn-primary btn-modal"
                    data-href="{{route('discount.create')}}"
                    data-container=".discount_modal">
                    <i class="fa fa-plus"></i> @lang( 'messages.add' )</button>
            </div>
            @endcan
        </div>
        <div class="box-body">
            @can('brand.view')
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="discounts_table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all-row"></th>
                            <th>@lang( 'unit.name' )</th>
                            <th>@lang( 'lang_v1.starts_at' )</th>
                            <th>@lang( 'lang_v1.ends_at' )</th>
                            <th>@lang( 'lang_v1.priority' )</th>
                            <th>@lang( 'product.brand' )</th>
                            <th>@lang( 'product.category' )</th>
                            <th>@lang( 'sale.location' )</th>
                            <th>@lang( 'messages.action' )</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <td colspan="9">
                                <div style="display: flex; width: 100%;">
                                    <form action="{{ route('discount.massDeactivate') }}" method="POST" id="mass_deactivate_form">
                                        @csrf
                                        <input type="hidden" name="selected_discounts" id="selected_discounts">
                                        <button type="submit" class="btn btn-xs btn-warning" id="deactivate-selected">
                                            {{ __('lang_v1.deactivate_selected') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @endcan
        </div>
    </div>

    <div class="modal fade discount_modal" tabindex="-1" role="dialog"
        aria-labelledby="gridSystemModalLabel">
    </div>

</section>
<!-- /.content -->
@stop
@section('javascript')
<script type="text/javascript">
    $(document).on('click', '#deactivate-selected', function(e) {
        e.preventDefault();
        var selected_rows = [];
        var i = 0;
        $('.row-select:checked').each(function() {
            selected_rows[i++] = $(this).val();
        });

        if (selected_rows.length > 0) {
            $('input#selected_discounts').val(selected_rows);
            swal({
                title: LANG.sure,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $('form#mass_deactivate_form').submit();
                }
            });
        } else {
            $('input#selected_discounts').val('');
            swal('@lang("lang_v1.no_row_selected")');
        }
    });

    $('table#discounts_table tbody').on('click', '.activate-discount', function(e) {
        e.preventDefault();
        var href = $(this).data('href');
        $.ajax({
            method: "get",
            url: href,
            dataType: "json",
            success: function(result) {
                if (result.success == true) {
                    toastr.success(result.msg);
                    discounts_table.ajax.reload();
                } else {
                    toastr.error(result.msg);
                }
            }
        });
    });
</script>
@endsection
