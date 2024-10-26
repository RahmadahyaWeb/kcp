@extends('layouts.app')

@section('title', "Data AOP Final")

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('aop-upload.index') }}">Data Upload AOP</a></li>
        <li class="breadcrumb-item active"><a href="{{ route('aop-upload.index') }}">Data AOP Final</a></li>
    </ol>
@endsection

@section('content')
    <livewire:aop-final/>
@endsection