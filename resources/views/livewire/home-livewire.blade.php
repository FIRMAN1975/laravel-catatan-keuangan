<div class="mt-3">
    <div class="card">
        <div class="card-header d-flex">
            <div class="flex-fill">
                <h3>Hai, {{ $auth->name }}</h3>
            </div>
            <div>
                <a href="{{ route('auth.logout') }}" class="btn btn-warning">Keluar</a>
            </div>
        </div>

        <div class="card-body">
            <div class="d-flex mb-2">
                <div class="flex-fill">
                    <h3>Daftar Cashflow</h3>
                </div>
                <div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCashflowModal">
                        Tambah Transaksi
                    </button>
                </div>
            </div>

            <table class="table table-striped align-middle">
                <tr class="table-light">
                    <th>No</th>
                    <th>Jenis</th>
                    <th>Sumber</th>
                    <th>Label</th>
                    <th>Nominal</th>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Bukti</th>
                    <th>Tindakan</th>
                </tr>

                @forelse ($cashflows as $key => $c)
                    <tr>
                        <td>{{ $key + 1 }}</td>

                        <td>
                            @if ($c->type === 'inflow')
                                <span class="badge bg-success">Pemasukan</span>
                            @else
                                <span class="badge bg-danger">Pengeluaran</span>
                            @endif
                        </td>

                        <td>
                            @switch($c->source)
                                @case('cash') Tunai @break
                                @case('savings') Tabungan @break
                                @case('loans') Pinjaman @break
                            @endswitch
                        </td>

                        <td>{{ $c->label }}</td>

                        <td>Rp {{ number_format($c->amount, 0, ',', '.') }}</td>

                        <td>{{ $c->created_at->format('d F Y') }}</td>

                        <td>{{ $c->description ?: '-' }}</td>

                        <td>
                            @if ($c->cover)
                                <img src="{{ asset('storage/' . $c->cover) }}" width="60" class="rounded">
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('app.cashflow.detail', $c->id) }}" class="btn btn-sm btn-info">
                                Detail
                            </a>
                            <button wire:click="prepareEdit({{ $c->id }})" class="btn btn-sm btn-warning">
                                Edit
                            </button>
                            <button wire:click="prepareDelete({{ $c->id }})" class="btn btn-sm btn-danger">
                                Hapus
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">Belum ada data cashflow.</td>
                    </tr>
                @endforelse

            </table>
        </div>
    </div>

    {{-- Modals --}}
    @include('components.modals.cashflows.add')
    @include('components.modals.cashflows.edit')
    @include('components.modals.cashflows.delete')
</div>
