@extends('layouts.app')

@section('title', 'DKS Scan')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <b>DKS Monitoring</b>
                </div>
            </div>
            <hr>
        </div>
        <div class="card-body">
            <livewire:report-dks-table lazy />
        </div>
    </div>
@endsection
