@extends('layouts.app')
@section('title', 'Categories')

@section('content')

<livewire:category-table />

@endsection
@section('javascript')
@livewireScripts
@endsection
