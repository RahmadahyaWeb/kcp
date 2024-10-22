@extends('layouts.app')

@section('title', 'Upload File AOP')

@section('content')
    <div class="row">
        <livewire:aop-detail :invoiceAop="$invoiceAop" lazy />

    </div>
@endsection

@push('scripts')
    @livewireScripts()
    <script>
        Livewire.on('fakturPajakUpdate', () => {
            $('#editFakturPajakModal').modal('hide');
        })

        Livewire.on('openModal', () => {
            $('#editFakturPajakModal').modal('show');
        })
    </script>
@endpush
