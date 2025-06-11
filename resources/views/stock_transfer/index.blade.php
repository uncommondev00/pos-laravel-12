@extends('layouts.app')
@section('title', __('lang_v1.stock_transfers'))

@section('content')

<livewire:stock-transfer-table />
@stop
@section('javascript')
@livewireScripts
<script src="{{ asset('js/stock_transfer.js?v=' . $asset_v) }}"></script>
@endsection
