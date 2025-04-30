@extends('layouts.app')
@section('title', __( 'tax_rate.tax_rates' ))

@section('content')

<!-- lioverwire -->\
<livewire:tax-rates-table />
@endsection
@section('javascript')
@livewireScripts
@endsection
