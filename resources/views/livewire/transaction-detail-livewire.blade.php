<div class="p-0 bg-body-tertiary">
    <div class="mt-4">
        {{-- Header & Aksi: Tampilan lebih bersih dengan bayangan kuat --}}
        {{-- Card Utama Menggunakan Warna Putih --}}
        <div class="card rounded-4 shadow-lg border-0 overflow-hidden">
            <div class="row g-0 align-items-center p-4 bg-white">
                <div class="col-md-8 d-flex align-items-center">
                    <div class="me-4">
                        {{-- Avatar menggunakan warna info --}}
                        <div class="avatar rounded-circle bg-info-subtle text-info-emphasis d-flex align-items-center justify-content-center" style="width:60px;height:60px;font-weight:700; font-size: 1.5rem;">
                            {{ strtoupper(substr($auth->name,0,1)) }}
                        </div>
                    </div>
                    <div>
                        <h3 class="mb-1 fw-bold text-dark">Halo, {{ $auth->name }}</h3>
                        <p class="text-secondary small mb-0">Kelola transaksi keuangan Anda dengan mudah di sini.</p>
                    </div>
                </div>

                <div class="col-md-4 text-md-end mt-4 mt-md-0">
                    <a href="{{ route('auth.logout') }}" class="btn btn-outline-secondary me-2 rounded-pill fw-semibold">Keluar</a>
                    {{-- Tombol utama menggunakan warna info (Cyan/Teal) --}}
                    <button class="btn btn-info text-white rounded-pill fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Transaksi
                    </button>
                </div>
            </div>

            {{-- Filter Area (Latar belakang light untuk pemisah visual) --}}
            <div class="card-body border-top p-4 bg-light">
                <div class="row g-3 align-items-center">
                    <div class="col-md-5">
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-info"></i></span>
                            <input type="text" class="form-control border-start-0" placeholder="Cari judul atau deskripsi transaksi..."
                                   wire:model.live.debounce.300ms="search" />
                        </div>
                    </div>

                    <div class="col-md-3">
                        <select class="form-select form-select-lg" wire:model.live="filterType">
                            <option value="all">Semua Tipe</option>
                            <option value="income">Pemasukan</option>
                            <option value="expense">Pengeluaran</option>
                        </select>
                    </div>

                    <div class="col-md-4 text-md-end">
                        @if($search || $filterType != 'all')
                            <button class="btn btn-outline-secondary fw-semibold" wire:click="resetFilters">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    ---

    ## 📊 Ringkasan Keuangan

    <div class="row g-4 my-3">
        {{-- Total Pemasukan --}}
        <div class="col-lg-4 col-md-6 col-12">
            {{-- Tambahkan border info di samping kiri --}}
            <div class="card shadow-sm border-start border-4 border-success rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="avatar flex-shrink-0 me-3">
                            {{-- Warna Hijau untuk Income --}}
                            <span class="p-3 rounded-circle bg-success bg-opacity-10 text-success fs-3 d-flex align-items-center justify-content-center">
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
            {{-- Tambahkan border info di samping kiri --}}
            <div class="card shadow-sm border-start border-4 border-danger rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="avatar flex-shrink-0 me-3">
                            {{-- Warna Merah untuk Expense --}}
                            <span class="p-3 rounded-circle bg-danger bg-opacity-10 text-danger fs-3 d-flex align-items-center justify-content-center">
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
            {{-- Tambahkan border info di samping kiri --}}
            <div class="card shadow-sm border-start border-4 border-info rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="avatar flex-shrink-0 me-3">
                            {{-- Warna info untuk Saldo Positif/Netral --}}
                            @if ($totalBalance >= 0)
                                <span class="p-3 rounded-circle bg-info bg-opacity-10 text-info fs-3 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-wallet2"></i>
                                </span>
                            @else
                                {{-- Warna Warning untuk Saldo Negatif --}}
                                <span class="p-3 rounded-circle bg-warning bg-opacity-10 text-warning fs-3 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-exclamation-triangle"></i>
                                </span>
                            @endif
                        </div>
                        <div class="me-auto">
                            <small class="text-secondary d-block mb-1">Total Saldo</small>
                            <h4 class="mb-0 fw-bold @if($totalBalance < 0) text-warning @else text-info @endif">
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

    ## 📈 Detail & Daftar Transaksi

    <div class="row g-4">
        {{-- Statistik Keuangan --}}
        <div class="col-12">
            <div class="card rounded-4 shadow-lg border-0">
                <div class="card-header bg-light border-bottom py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="mb-0 fw-semibold text-info">Statistik Keuangan</h5>
                        <small class="text-muted">Grafik Pemasukan vs Pengeluaran berdasarkan filter.</small>
                    </div>
                    <small class="text-muted">Periode: <strong>{{ $currentPeriod ?? 'Semua' }}</strong></small>
                </div>
                <div class="card-body p-4">
                    <div wire:ignore>
                        {{-- Grafik akan dimuat di sini --}}
                        <div id="finance-chart" style="min-height:260px;"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Daftar Transaksi --}}
        <div class="col-12">
            <div class="card rounded-4 shadow-lg border-0">
                <div class="card-header bg-light border-bottom py-3 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 fw-semibold text-info">Daftar Transaksi</h5>
                    <small class="text-muted">Menampilkan {{ $transactions->total() }} transaksi</small>
                </div>

                <div class="card-body p-0">
                    @if ($transactions->isEmpty())
                        <div class="p-5 text-center bg-white rounded-bottom-4">
                            <div class="mb-3">
                                <i class="bi bi-box-seam text-secondary" style="font-size:32px;"></i>
                            </div>
                            <h5 class="mb-1 text-muted">Data Kosong</h5>
                            <p class="small text-muted mb-0">Tidak ditemukan transaksi untuk kriteria pencarian atau filter saat ini.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            {{-- Menggunakan table-striped untuk visual pembeda baris --}}
                            <table class="table table-striped table-hover align-middle mb-0">
                                <thead class="table-light small text-secondary text-uppercase">
                                    <tr>
                                        <th style="width:50px">No</th>
                                        <th style="min-width:140px">Tanggal</th>
                                        <th>Tipe</th>
                                        <th class="text-end">Jumlah</th>
                                        <th>Deskripsi</th>
                                        <th class="text-center">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $key => $transaction)
                                        <tr class="small">
                                            <td class="text-muted">{{ $transactions->firstItem() + $loop->index }}</td>
                                            <td class="fw-medium text-nowrap">{{ $transaction->date->format('d F Y') }}</td>
                                            <td>
                                                @if ($transaction->type == 'income')
                                                    <span class="badge bg-success-subtle text-success fw-semibold border border-success">Pemasukan</span>
                                                @else
                                                    <span class="badge bg-danger-subtle text-danger fw-semibold border border-danger">Pengeluaran</span>
                                                @endif
                                            </td>
                                            <td class="text-end fw-bold text-nowrap">Rp {{ number_format($transaction->amount,0,',','.') }}</td>
                                            <td>
                                                <div class="text-truncate" style="max-width:280px;" title="{{ $transaction->description }}">
                                                    {{ $transaction->description }}
                                                </div>
                                            </td>
                                            <td class="text-center text-nowrap">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    {{-- Tombol Detail menggunakan outline-info --}}
                                                    <a href="{{ route('app.transactions.detail', ['transaction_id' => $transaction->id]) }}" class="btn btn-outline-info">Detail</a>
                                                    <button wire:click="prepareEditTransaction({{ $transaction->id }})" class="btn btn-outline-secondary">Edit</button>
                                                    <button wire:click="prepareDeleteTransaction({{ $transaction->id }})" class="btn btn-outline-danger">Hapus</button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex align-items-center justify-content-between p-4 border-top">
                            <small class="text-muted">Menampilkan **{{ $transactions->firstItem() }}** - **{{ $transactions->lastItem() }}** dari **{{ $transactions->total() }}**</small>
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
        {{-- FAB menggunakan warna info --}}
        <button class="btn btn-info rounded-circle shadow-lg d-flex align-items-center justify-content-center text-white" style="width:60px;height:60px;" data-bs-toggle="modal" data-bs-target="#addTransactionModal" aria-label="Tambah Transaksi">
            <i class="bi bi-plus-lg" style="font-size:24px"></i>
        </button>
    </div>

    {{-- Modals (Fungsionalitas Tidak Berubah) --}}
    @include('components.modals.transactions.add')
    @include('components.modals.transactions.edit')
</div>