<div>
    <div class="row mb-3">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <b>Upload File AOP</b>
                    <hr>
                </div>
                <div class="card-body">
                    <div class="alert alert-success" x-data="{ show: false }" x-show="show" x-init="@this.on('file-uploaded', () => {
                        show = true;
                        setTimeout(() => { show = false; }, 2000)
                    })"
                        style="display: none">
                        <span>{{ $notification }}</span>
                    </div>

                    <form wire:submit="save">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label for="surat_tagihan" class="form-label">
                                    Surat Tagihan
                                </label>
                                <input type="file" id="surat_tagihan"
                                    class="form-control @error('surat_tagihan') is-invalid @enderror"
                                    wire:model="surat_tagihan" wire:loading.class="is-invalid">
                                @error('surat_tagihan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="invalid-feedback" wire:loading wire:target="surat_tagihan">
                                    Uploading...
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="rekap_tagihan" class="form-label">
                                    Rekap Tagihan
                                </label>
                                <input type="file" id="rekap_tagihan"
                                    class="form-control @error('rekap_tagihan') is-invalid @enderror"
                                    wire:model="rekap_tagihan" wire:loading.class="is-invalid">
                                @error('rekap_tagihan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="invalid-feedback" wire:loading wire:target="rekap_tagihan">
                                    Uploading...
                                </div>
                            </div>
                            <div class="col-md-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled"
                                    wire:target="rekap_tagihan, surat_tagihan">
                                    <span wire:loading.remove wire:target="save">Proses</span>
                                    <span wire:loading wire:target="save">Loading...</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
