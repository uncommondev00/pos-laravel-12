@extends('layouts.app')
@section('title', __('sale.products'))

@section('content')
    <livewire:products-table />
@endsection

@section('javascript')
    @livewireScripts
@endsection