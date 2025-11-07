<form wire:submit.prevent="editCashflow">
    <div class="modal fade" tabindex="-1" id="editCashflowModal" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Ubah Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <!-- Type -->
                    <div class="mb-3">
                        <label class="form-label">Jenis Transaksi</label>
                        <select class="form-select" wire:model="editType">
                            <option value="inflow">Pemasukan</option>
                            <option value="outflow">Pengeluaran</option>
                        </select>
                        @error('editType')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Source -->
                    <div class="mb-3">
                        <label class="form-label">Sumber Dana</label>
                        <select class="form-select" wire:model="editSource">
                            <option value="cash">Cash</option>
                            <option value="savings">Tabungan</option>
                            <option value="loans">Pinjaman</option>
                        </select>
                        @error('editSource')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Label -->
                    <div class="mb-3">
                        <label class="form-label">Label</label>
                        <input type="text" class="form-control" wire:model="editLabel">
                        @error('editLabel')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" rows="4" wire:model="editDescription"></textarea>
                        @error('editDescription')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Amount -->
                    <div class="mb-3">
                        <label class="form-label">Nominal</label>
                        <input type="number" class="form-control" wire:model="editAmount" step="0.01">
                        @error('editAmount')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>

            </div>
        </div>
    </div>
</form>
