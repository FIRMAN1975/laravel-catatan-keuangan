<div class="mt-3">
    <div class="card">
        <div class="card-header d-flex">
            <div class="flex-fill">
                <a href="{{ route('app.home') }}" class="text-decoration-none">
                    <small class="text-muted">&lt; Kembali</small>
                </a>

                <h3 class="mt-2">
                    {{ $cashflow->label }}
                    @if ($cashflow->type === 'inflow')
                        <span class="badge bg-success">Pemasukan</span>
                    @else
                        <span class="badge bg-danger">Pengeluaran</span>
                    @endif
                </h3>

                <p class="mt-1 text-muted">
                    Tanggal: {{ $cashflow->created_at->format('d F Y') }}
                </p>
            </div>

            <div>
                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editCoverCashflowModal">
                    Ubah Bukti / Nota
                </button>
            </div>
        </div>

        <div class="card-body">
            <h5>Nominal:</h5>
            <p class="fw-bold" style="font-size: 20px;">
                Rp {{ number_format($cashflow->amount, 0, ',', '.') }}
            </p>

            <h5>Sumber Dana:</h5>
            <p>
                @switch($cashflow->source)
                    @case('cash') Tunai @break
                    @case('savings') Tabungan @break
                    @case('loans') Pinjaman @break
                @endswitch
            </p>

            @if ($cashflow->cover)
                <h5>Bukti Transaksi:</h5>
                <img src="{{ asset('storage/' . $cashflow->cover) }}" alt="Bukti Transaksi" class="img-fluid rounded" style="max-width: 400px;">
                <hr>
            @endif

            <h5>Keterangan:</h5>
            <p style="font-size: 18px;">
                {{ $cashflow->description ?: '-' }}
            </p>
        </div>
    </div>

    {{-- Modal Ubah Bukti --}}
    <form wire:submit.prevent="editCoverCashflow">
        <div class="modal fade" tabindex="-1" id="editCoverCashflowModal" wire:ignore.self>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ubah Bukti Transaksi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Pilih Bukti Baru</label>
                            <input type="file" class="form-control" wire:model="editCoverCashflowFile" accept="image/*">
                            @error('editCoverCashflowFile')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror

                            @if ($editCoverCashflowFile)
                                <div class="mt-3">
                                    <p class="fw-bold">Preview:</p>
                                    <img src="{{ $editCoverCashflowFile->temporaryUrl() }}" class="img-fluid rounded" style="max-height: 200px;">
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" @if(!$editCoverCashflowFile) disabled @endif>
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>