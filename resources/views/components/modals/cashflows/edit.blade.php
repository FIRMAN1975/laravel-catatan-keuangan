<form wire:submit.prevent="editCashflow">
    <div class="modal fade" id="editCashflowModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Jenis</label>
                            <select class="form-select" wire:model="editType">
                                <option value="inflow">Pemasukan</option>
                                <option value="outflow">Pengeluaran</option>
                            </select>
                            @error('editType') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Sumber</label>
                            <select class="form-select" wire:model="editSource">
                                <option value="cash">Cash</option>
                                <option value="savings">Tabungan</option>
                                <option value="loans">Pinjaman</option>
                            </select>
                            @error('editSource') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Label</label>
                            <input type="text" class="form-control" wire:model="editLabel">
                            @error('editLabel') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <input id="editDescription" type="hidden" data-trix-field="editDescription" wire:model.defer="editDescription">
                            <trix-editor input="editDescription"></trix-editor>
                            @error('editDescription') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nominal</label>
                            <input type="number" class="form-control" step="0.01" wire:model="editAmount">
                            @error('editAmount') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    // When Livewire shows the edit modal, we should place current editDescription into Trix editor
    Livewire.on('showEditModal', () => {
        // Wait a tick for modal to be shown and trix editor to init
        setTimeout(() => {
            const input = document.getElementById('editDescription');
            if (input) {
                // set trix editor content from value
                const editor = input.nextElementSibling; // trix-editor
                if (editor) {
                    editor.editor?.loadHTML(input.value || '');
                }
            }
        }, 150);
    });

    // Similarly for add modal, ensure Trix is empty
    document.querySelectorAll('#addCashflowModal, #editCashflowModal').forEach(modalEl => {
        modalEl.addEventListener('hidden.bs.modal', event => {
            // clear trix editors when modal closed (optional)
            const inputs = modalEl.querySelectorAll('input[type="hidden"][data-trix-field]');
            inputs.forEach(i => {
                i.value = '';
                const ed = i.nextElementSibling;
                if (ed && ed.editor) ed.editor.loadHTML('');
            });
        });
    });
</script>
