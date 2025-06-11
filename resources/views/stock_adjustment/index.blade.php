@extends('layouts.app')
@section('title', __('stock_adjustment.stock_adjustments'))

@section('content')

<livewire:stock-adjustment-table />
@stop
@section('javascript')
@livewireScripts
<script src="{{ asset('js/stock_adjustment.js?v=' . $asset_v) }}"></script>
@endsection
