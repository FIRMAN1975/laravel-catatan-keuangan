<form wire:submit.prevent="addCashflow">
    <div class="modal fade" id="addCashflowModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Jenis</label>
                            <select class="form-select" wire:model="addType">
                                <option value="">-- Pilih --</option>
                                <option value="inflow">Pemasukan</option>
                                <option value="outflow">Pengeluaran</option>
                            </select>
                            @error('addType') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Sumber</label>
                            <select class="form-select" wire:model="addSource">
                                <option value="">-- Pilih --</option>
                                <option value="cash">Cash</option>
                                <option value="savings">Tabungan</option>
                                <option value="loans">Pinjaman</option>
                            </select>
                            @error('addSource') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Label</label>
                            <input type="text" class="form-control" wire:model="addLabel">
                            @error('addLabel') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            {{-- Trix --}}
                            <input id="addDescription" type="hidden" data-trix-field="addDescription" wire:model.defer="addDescription">
                            <trix-editor input="addDescription"></trix-editor>
                            @error('addDescription') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nominal</label>
                            <input type="number" class="form-control" step="0.01" wire:model="addAmount">
                            @error('addAmount') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Bukti (opsional)</label>
                            <input type="file" class="form-control" wire:model="addCover" accept="image/*">
                            @error('addCover') <small class="text-danger">{{ $message }}</small> @enderror
                            @if ($addCover)
                                <div class="mt-2">
                                    <img src="{{ $addCover->temporaryUrl() }}" alt="preview" class="img-fluid" style="max-height:150px;">
                                </div>
                            @endif
                        </div>
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
