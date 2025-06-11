@extends('layouts.app')

@section('title', 'Users')

@section('css')
@livewireStyles
@endsection

@section('content')
<livewire:users-table />
@endsection

@section('javascript')
@livewireScripts
<!-- SweetAlert Event Listener -->
<script>
    $(document).on('click', 'button.delete_user_button', function() {
        Swal.fire({
            title: LANG.sure,
            text: LANG.confirm_delete_user,
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
                            Livewire.dispatchTo('users-table', 'refreshComponent');
                        } else {
                            toastr.error(result.msg);
                        }
                    }
                });
            }
            // No action needed for "Cancel" (result.isDismissed)
        });
    });
</script>
@endsection
