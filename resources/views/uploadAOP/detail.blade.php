@extends('layouts.app')

@section('title', 'Upload File AOP')

@section('content')
    <livewire:aop-detail :invoiceAop="$invoiceAop" lazy />
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

        Livewire.on('programSaved', () => {
            $('#createProgramModal').modal('hide');
        })

        Livewire.on('openModalProgram', () => {
            $('#createProgramModal').modal('show');
        })
    </script>
@endpush
