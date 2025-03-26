@extends('layouts.app')
@section('title', __('lang_v1.'.$type.'s'))

@section('content')

<livewire:contacts-table />

@endsection

@section('javascript')
    @livewireScripts
    
@endsection
