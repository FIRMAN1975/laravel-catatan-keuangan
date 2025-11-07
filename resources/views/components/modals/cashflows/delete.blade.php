<form wire:submit.prevent="deleteCashflow">
    <div class="modal fade" tabindex="-1" id="deleteCashflowModal" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Hapus Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-danger">
                        Apakah kamu yakin ingin menghapus transaksi dengan label
                        <strong>"{{ $deleteCashflowLabel }}"</strong>?
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ketik ulang nama label untuk konfirmasi</label>
                        <input type="text" class="form-control" wire:model="deleteCashflowConfirmLabel">
                        @error('deleteCashflowConfirmLabel')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>

            </div>
        </div>
    </div>
</form>
