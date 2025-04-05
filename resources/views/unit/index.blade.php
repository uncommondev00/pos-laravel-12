@extends('layouts.app')
@section('title', __( 'unit.units' ))

@section('content')

<livewire:unit-table />

@endsection

@section('javascript')
@livewireScripts
@endsection
