@extends('layouts.app')

@section('title', "Detail $invoiceAop")

@section('content')
    <livewire:aop-detail :invoiceAop="$invoiceAop" lazy />
@endsection

@push('scripts')
    @livewireScripts()
@endpush
