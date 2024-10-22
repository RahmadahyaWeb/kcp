@extends('layouts.app')

@section('title', 'Upload File AOP')

@section('content')
    <livewire:aop-detail :invoiceAop="$invoiceAop" lazy />
@endsection
