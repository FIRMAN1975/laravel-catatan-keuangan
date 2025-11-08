<form wire:submit.prevent="editCoverCashflow">
    <div class="modal fade" id="editCoverCashflowModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Bukti Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih Bukti</label>
                        <input type="file" class="form-control" wire:model="editCoverCashflowFile" accept="image/*">
                        @error('editCoverCashflowFile') <small class="text-danger">{{ $message }}</small> @enderror

                        @if ($editCoverCashflowFile)
                            <div class="mt-3">
                                <img src="{{ $editCoverCashflowFile->temporaryUrl() }}" class="img-fluid rounded" style="max-height:200px;">
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" @if(!$editCoverCashflowFile) disabled @endif>Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    Livewire.on('showEditCoverModal', () => {
        const el = document.getElementById('editCoverCashflowModal');
        if (el) {
            const m = new bootstrap.Modal(el);
            m.show();
        }
    });
</script>
