@extends('layouts.app')
@section('title', __('lang_v1.selling_price_group'))

@section('content')

    <livewire:selling-price-group-table />
@stop
@section('javascript')
    @livewireScripts
    <script type="text/javascript">
        $(document).ready(function() {

            //selling_price_group_table
            var selling_price_group_table = $('#selling_price_group_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/selling-price-group',
                columnDefs: [{
                    "targets": 2,
                    "orderable": false,
                    "searchable": false
                }]
            });

            $(document).on('submit', 'form#selling_price_group_form', function(e) {
                e.preventDefault();
                var data = $(this).serialize();

                $.ajax({
                    method: "POST",
                    url: $(this).attr("action"),
                    dataType: "json",
                    data: data,
                    success: function(result) {
                        if (result.success == true) {
                            $('div.view_modal').modal('hide');
                            toastr.success(result.msg);
                            //selling_price_group_table.ajax.reload();
                            Livewire.dispatchTo('selling-price-group-table',
                            'refreshComponent');
                        } else {
                            toastr.error(result.msg);
                        }
                    }
                });
            });

            $(document).on('click', 'button.delete_spg_button', function() {
                Swal.fire({
                    title: LANG.sure,
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, remove it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    focusCancel: true,
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-default'
                    }
                }).then((result) => {
                    if (result.isConfirmed) { // Check if the user clicked "Confirm"
                        var href = $(this).data('href');
                        var data = $(this).serialize();
                        $.ajax({
                            method: "DELETE",
                            url: href,
                            dataType: "json",
                            data: data,
                            success: function(result) {
                                if (result.success == true) {
                                    toastr.success(result.msg);
                                    // Trigger Livewire table refresh
                                    Livewire.dispatchTo('selling-price-group-table',
                                        'refreshComponent');
                                } else {
                                    toastr.error(result.msg);
                                }
                            }
                        });
                    }
                    // No action needed for "Cancel" (result.isDismissed)
                });
            });

        });
    </script>
@endsection
