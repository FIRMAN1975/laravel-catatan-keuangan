<form wire:submit.prevent="editCoverCashflow">
    <div class="modal fade" tabindex="-1" id="editCoverCashflowModal" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Ubah Bukti Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih Bukti</label>
                        <input type="file" class="form-control" wire:model="editCoverCashflowFile">
                        @error('editCoverCashflowFile')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                        <!-- Preview jika ingin -->
                        @if ($editCoverCashflowFile)
                            <div class="mt-3">
                                <p class="fw-bold">Preview:</p>
                                <img src="{{ $editCoverCashflowFile->temporaryUrl() }}" class="img-fluid rounded">
                            </div>
                        @endif
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" @if (!$editCoverCashflowFile) disabled @endif>
                        Simpan
                    </button>
                </div>

            </div>
        </div>
    </div>
</form>
