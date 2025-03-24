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

    Livewire.on('userDeleted', () => {
        Swal.fire(
            'Deleted!',
            'User has been deleted.',
            'success'
        );
    });
    </script>
@endsection
