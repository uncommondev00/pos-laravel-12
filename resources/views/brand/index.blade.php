@extends('layouts.app')
@section('title', 'Brands')

@section('content')

<livewire:brand-table />

@endsection
@section('javascript')
@livewireScripts
@endsection