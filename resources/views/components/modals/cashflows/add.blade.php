<form wire:submit.prevent="addCashflow">
    <div class="modal fade" tabindex="-1" id="addCashflowModal" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <!-- Jenis Transaksi -->
                    <div class="mb-3">
                        <label class="form-label">Jenis Transaksi</label>
                        <select class="form-select" wire:model="addType">
                            <option value="">-- Pilih --</option>
                            <option value="inflow">Pemasukan</option>
                            <option value="outflow">Pengeluaran</option>
                        </select>
                        @error('addType')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Sumber Dana -->
                    <div class="mb-3">
                        <label class="form-label">Sumber Dana</label>
                        <select class="form-select" wire:model="addSource">
                            <option value="">-- Pilih --</option>
                            <option value="cash">Cash</option>
                            <option value="savings">Tabungan</option>
                            <option value="loans">Pinjaman</option>
                        </select>
                        @error('addSource')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Label -->
                    <div class="mb-3">
                        <label class="form-label">Label</label>
                        <input type="text" class="form-control" wire:model="addLabel">
                        @error('addLabel')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" rows="3" wire:model="addDescription"></textarea>
                        @error('addDescription')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Nominal -->
                    <div class="mb-3">
                        <label class="form-label">Nominal</label>
                        <input type="number" class="form-control" wire:model="addAmount" step="0.01">
                        @error('addAmount')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Upload Cover -->
                    <div class="mb-3">
                        <label class="form-label">Bukti (opsional)</label>
                        <input type="file" class="form-control" wire:model="addCover">
                        @error('addCover')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        
                        <!-- Preview Image -->
                        @if ($addCover)
                            <div class="mt-2">
                                <p class="mb-1">Preview:</p>
                                <img src="{{ $addCover->temporaryUrl() }}" class="img-thumbnail" style="max-height: 150px;">
                            </div>
                        @endif
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