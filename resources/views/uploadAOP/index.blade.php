@extends('layouts.app')

@section('title', 'Upload File AOP')

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="{{ route('aop-upload.index') }}">Data Upload AOP</a></li>
    </ol>
@endsection

@section('content')
    <livewire:aop-upload />
@endsection
