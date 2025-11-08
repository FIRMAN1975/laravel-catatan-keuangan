<div class="bg-dark text-white p-4 p-md-5 rounded-4 shadow-lg">
    {{-- Header Area: Tampilan Gelap --}}
    <div class="mt-3">
        <div class="card rounded-4 shadow-xl border border-warning border-opacity-25 overflow-hidden bg-dark-subtle">
            <div class="row g-0 align-items-center p-4">
                <div class="col-md-8 d-flex align-items-center">
                    <div class="me-4">
                        {{-- Avatar menggunakan warna warning (oranye) --}}
                        <div class="avatar rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center" style="width:64px;height:64px;font-weight:700; font-size: 1.5rem;">
                            {{ strtoupper(substr($auth->name,0,1)) }}
                        </div>
                    </div>
                    <div>
                        <h3 class="mb-1 text-white">Halo, <span class="fw-bold">{{ $auth->name }}</span></h3>
                        <p class="text-warning-light small mb-0">Kelola transaksi keuangan Anda di sini.</p>
                    </div>
                </div>

                <div class="col-md-4 text-md-end mt-4 mt-md-0">
                    <a href="{{ route('auth.logout') }}" class="btn btn-outline-danger me-2 rounded-pill">Keluar</a>
                    {{-- Tombol utama menggunakan warna warning --}}
                    <button class="btn btn-warning text-dark rounded-pill fw-bold" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
                        <i class="bi bi-plus-lg me-1"></i> Tambah
                    </button>
                </div>
            </div>

            {{-- Filter Area (Latar Belakang sedikit lebih terang) --}}
            <div class="card-body border-top border-secondary border-opacity-25 p-4 bg-dark">
                <div class="row g-3 align-items-center">
                    <div class="col-md-5">
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-secondary border-secondary text-white"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control bg-secondary text-white border-secondary" placeholder="Cari judul atau deskripsi transaksi..."
                                   wire:model.live.debounce.300ms="search" />
                        </div>
                    </div>

                    <div class="col-md-3">
                        <select class="form-select form-select-lg bg-secondary text-white border-secondary" wire:model.live="filterType">
                            <option value="all">Semua Tipe</option>
                            <option value="income">Pemasukan</option>
                            <option value="expense">Pengeluaran</option>
                        </select>
                    </div>

                    <div class="col-md-4 text-md-end">
                        @if($search || $filterType != 'all')
                            <button class="btn btn-outline-warning text-warning" wire:click="resetFilters">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    ---

    ## ✨ Ringkasan Keuangan

    <div class="row g-4 my-4">
        {{-- Total Pemasukan --}}
        <div class="col-lg-4 col-md-6 col-12">
            <div class="card shadow-lg border-0 rounded-4 bg-dark-subtle">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="p-2 rounded-circle bg-success bg-opacity-10 text-success fs-3 d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                                <i class="bi bi-arrow-down-left"></i>
                            </span>
                        </div>
                        <div class="me-auto">
                            <small class="text-secondary d-block mb-1">Total Pemasukan</small>
                            <h4 class="mb-0 fw-bold text-success">
                                Rp {{ number_format($totalIncome, 0, ',', '.') }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Pengeluaran --}}
        <div class="col-lg-4 col-md-6 col-12">
            <div class="card shadow-lg border-0 rounded-4 bg-dark-subtle">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="p-2 rounded-circle bg-danger bg-opacity-10 text-danger fs-3 d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                                <i class="bi bi-arrow-up-right"></i>
                            </span>
                        </div>
                        <div class="me-auto">
                            <small class="text-secondary d-block mb-1">Total Pengeluaran</small>
                            <h4 class="mb-0 fw-bold text-danger">
                                Rp {{ number_format($totalExpense, 0, ',', '.') }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Saldo Saat Ini (Total Semuanya) --}}
        <div class="col-lg-4 col-md-12 col-12">
            <div class="card shadow-lg border-0 rounded-4 bg-dark-subtle">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="avatar flex-shrink-0 me-3">
                            @if ($totalBalance >= 0)
                                {{-- Warna Warning (Oranye) untuk Saldo Positif/Netral --}}
                                <span class="p-2 rounded-circle bg-warning bg-opacity-10 text-warning fs-3 d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                                    <i class="bi bi-coin"></i>
                                </span>
                            @else
                                {{-- Warna Danger untuk Saldo Negatif --}}
                                <span class="p-2 rounded-circle bg-danger bg-opacity-10 text-danger fs-3 d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                                    <i class="bi bi-exclamation-triangle"></i>
                                </span>
                            @endif
                        </div>
                        <div class="me-auto">
                            <small class="text-secondary d-block mb-1">Total Saldo</small>
                            <h4 class="mb-0 fw-bold @if($totalBalance < 0) text-danger @else text-warning @endif">
                                @if ($totalBalance < 0)
                                    -Rp {{ number_format(abs($totalBalance), 0, ',', '.') }}
                                @else
                                    Rp {{ number_format($totalBalance, 0, ',', '.') }}
                                @endif
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    ---

    ## 🧾 Daftar Transaksi

    <div class="row my-4">
        <div class="col-12">
            {{-- Kartu Statistik --}}
            <div class="card rounded-4 shadow-lg border-0 mb-4 bg-dark-subtle">
                <div class="card-header bg-dark border-bottom border-secondary border-opacity-25 py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="mb-0 fw-semibold text-white">Statistik Keuangan</h5>
                        <small class="text-secondary">Grafik Pemasukan vs Pengeluaran berdasarkan filter saat ini</small>
                    </div>
                    <small class="text-secondary">Periode: <strong class="text-white">{{ $currentPeriod ?? 'Semua' }}</strong></small>
                </div>
                <div class="card-body">
                    <div wire:ignore>
                        <div id="finance-chart" style="min-height:260px;"></div>
                    </div>
                </div>
            </div>
            
            {{-- Kartu Daftar Transaksi --}}
            <div class="card rounded-4 shadow-lg border-0 bg-dark-subtle">
                <div class="card-header bg-dark border-bottom border-secondary border-opacity-25 py-3 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 text-white">Daftar Transaksi</h5>
                    <small class="text-secondary">Menampilkan {{ $transactions->total() }} transaksi</small>
                </div>

                <div class="card-body p-0">
                    @if ($transactions->isEmpty())
                        <div class="p-5 text-center bg-dark">
                            <div class="mb-3">
                                <i class="bi bi-box-seam text-secondary" style="font-size:32px;"></i>
                            </div>
                            <h6 class="mb-1 text-white">Tidak ada transaksi</h6>
                            <p class="small text-secondary mb-0">Tidak ditemukan transaksi untuk kriteria pencarian atau filter saat ini.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-borderless table-hover align-middle mb-0 table-dark">
                                <thead class="table-dark-header small text-secondary text-uppercase">
                                    <tr>
                                        <th style="width:56px">No</th>
                                        <th style="min-width:140px">Tanggal</th>
                                        <th>Tipe</th>
                                        <th class="text-end">Jumlah</th>
                                        <th>Deskripsi</th>
                                        <th class="text-end">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $key => $transaction)
                                        <tr class="border-top border-secondary border-opacity-25">
                                            <td class="small text-secondary">{{ $transactions->firstItem() + $loop->index }}</td>
                                            <td class="fw-medium text-white">{{ $transaction->date->format('d F Y') }}</td>
                                            <td>
                                                @if ($transaction->type == 'income')
                                                    <span class="badge rounded-pill bg-success-subtle text-success border border-success">Pemasukan</span>
                                                @else
                                                    <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger">Pengeluaran</span>
                                                @endif
                                            </td>
                                            <td class="text-end fw-bold text-white">Rp {{ number_format($transaction->amount,0,',','.') }}</td>
                                            <td class="small text-secondary">
                                                <div class="text-truncate" style="max-width:320px;" title="{{ $transaction->description }}">
                                                    {{ $transaction->description }}
                                                </div>
                                            </td>
                                            <td class="text-end text-nowrap">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    {{-- Tombol Detail menggunakan outline-warning --}}
                                                    <a href="{{ route('app.transactions.detail', ['transaction_id' => $transaction->id]) }}" class="btn btn-outline-warning">Detail</a>
                                                    {{-- Tombol Edit menggunakan outline-light --}}
                                                    <button wire:click="prepareEditTransaction({{ $transaction->id }})" class="btn btn-outline-light">Edit</button>
                                                    <button wire:click="prepareDeleteTransaction({{ $transaction->id }})" class="btn btn-outline-danger">Hapus</button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex align-items-center justify-content-between p-4 border-top border-secondary border-opacity-25">
                            <div>
                                <small class="text-secondary">Menampilkan **{{ $transactions->firstItem() }}** - **{{ $transactions->lastItem() }}** dari **{{ $transactions->total() }}**</small>
                            </div>
                            <div>
                                {{ $transactions->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Floating Action Button (Mobile) --}}
    <div class="position-fixed end-0 bottom-0 p-4 d-md-none" style="z-index:1050;">
        {{-- FAB menggunakan warna warning --}}
        <button class="btn btn-warning rounded-circle shadow-lg d-flex align-items-center justify-content-center text-dark" style="width:60px;height:60px;" data-bs-toggle="modal" data-bs-target="#addTransactionModal" aria-label="Tambah Transaksi">
            <i class="bi bi-plus-lg" style="font-size:24px"></i>
        </button>
    </div>

    {{-- Modals (Fungsionalitas Tidak Berubah) --}}
    @include('components.modals.transactions.add')
    @include('components.modals.transactions.edit')

    {{-- Style Tambahan untuk Dark Mode --}}
    <style>
        .bg-dark-subtle { background-color: #212529 !important; } /* Lebih terang dari bg-dark */
        .text-warning-light { color: #ffc107; } /* Warning color */
        .table-dark-header { background-color: #1a1e21 !important; } /* Header table lebih gelap */
        .table-dark tbody tr:hover { background-color: #2c3034 !important; }
    </style>
</div>