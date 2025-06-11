@extends('layouts.app')
@section('title', __('sale.products'))

@section('content')
<livewire:products-table />
@endsection

@section('javascript')
@livewireScripts

<script src="{{ asset('js/product.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('js/opening_stock.js?v=' . $asset_v) }}"></script>
<script type="text/javascript">
    $(document).ready(function() {

        // Array to track the ids of the details displayed rows
        var detailRows = [];

        $('#product_table tbody').on('click', 'tr i.rack-details', function() {
            var i = $(this);
            var tr = $(this).closest('tr');
            var row = product_table.row(tr);
            var idx = $.inArray(tr.attr('id'), detailRows);

            if (row.child.isShown()) {
                i.addClass('fa-plus-circle text-success');
                i.removeClass('fa-minus-circle text-danger');

                row.child.hide();

                // Remove from the 'open' array
                detailRows.splice(idx, 1);
            } else {
                i.removeClass('fa-plus-circle text-success');
                i.addClass('fa-minus-circle text-danger');

                row.child(get_product_details(row.data())).show();

                // Add to the 'open' array
                if (idx === -1) {
                    detailRows.push(tr.attr('id'));
                }
            }
        });

        $('table#product_table tbody').on('click', 'a.delete-product', function(e) {
            e.preventDefault();
            swal({
                title: LANG.sure,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    var href = $(this).attr('href');
                    $.ajax({
                        method: "DELETE",
                        url: href,
                        dataType: "json",
                        success: function(result) {
                            if (result.success == true) {
                                toastr.success(result.msg);
                                product_table.ajax.reload();
                            } else {
                                toastr.error(result.msg);
                            }
                        }
                    });
                }
            });
        });

        $(document).on('click', '#delete-selected', function(e) {
            e.preventDefault();
            var selected_rows = [];
            var i = 0;
            $('.row-select:checked').each(function() {
                selected_rows[i++] = $(this).val();
            });

            if (selected_rows.length > 0) {
                $('input#selected_rows').val(selected_rows);
                swal({
                    title: LANG.sure,
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        $('form#mass_delete_form').submit();
                    }
                });
            } else {
                $('input#selected_rows').val('');
                swal('@lang("lang_v1.no_row_selected")');
            }
        });

        $(document).on('click', '#deactivate-selected', function(e) {
            e.preventDefault();
            var selected_rows = [];
            var i = 0;
            $('.row-select:checked').each(function() {
                selected_rows[i++] = $(this).val();
            });

            if (selected_rows.length > 0) {
                $('input#selected_products').val(selected_rows);
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
                $('input#selected_products').val('');
                swal('@lang("lang_v1.no_row_selected")');
            }
        })

        $('table#product_table tbody').on('click', 'a.activate-product', function(e) {
            e.preventDefault();
            var href = $(this).attr('href');
            $.ajax({
                method: "get",
                url: href,
                dataType: "json",
                success: function(result) {
                    if (result.success == true) {
                        toastr.success(result.msg);
                        product_table.ajax.reload();
                    } else {
                        toastr.error(result.msg);
                    }
                }
            });
        });

        $(document).on('change', '#product_list_filter_type, #product_list_filter_category_id, #product_list_filter_brand_id, #product_list_filter_unit_id, #product_list_filter_tax_id',
            function() {
                product_table.ajax.reload();
            });
    });

    $(document).on('shown.bs.modal', 'div.view_product_modal, div.view_modal', function() {
        __currency_convert_recursively($(this));
    });
</script>
@endsection
