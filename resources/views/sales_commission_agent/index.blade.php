@extends('layouts.app')
@section('title', __('lang_v1.sales_commission_agents'))

@section('css')
@livewireStyles
@endsection

@section('content')

<livewire:sales-commision-agent-table />

@endsection

@section('javascript')
    @livewireScripts
@endsection
