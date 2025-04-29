@extends('layouts.app')
@section('title', __('sale.products'))

@section('css')
@livewireStyles
@endsection

@section('content')
    <livewire:products-table />
@endsection

@section('javascript')
    @livewireScripts
@endsection