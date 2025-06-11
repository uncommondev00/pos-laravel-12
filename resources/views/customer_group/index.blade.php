@extends('layouts.app')
@section('title', __( 'lang_v1.customer_groups' ))

@section('content')

<livewire:customer-group-table>

    @endsection

    @section('javascript')
    @livewireScripts
    @endsection
