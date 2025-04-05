@extends('layouts.app')
@section('title', __('product.variations'))

@section('content')

<livewire:variation-template-table />

@endsection
@section('javascript')
@livewireScripts
@endsection
