<x-layouts.app>

<h3 class="mb-4">Dashboard Cashflow</h3>

<div class="d-flex gap-2 mb-3">
    <input type="text" wire:model.live="search" class="form-control" placeholder="Cari catatan...">

    <select wire:model.live="filterType" class="form-select" style="max-width: 200px;">
        <option value="">Semua</option>
        <option value="income">Pemasukan</option>
        <option value="expense">Pengeluaran</option>
    </select>

    <button class="btn btn-primary" wire:click="$dispatch('showCreate')">+ Tambah</button>
</div>

@include('components.cashflow-table', ['cashflows' => $cashflows])

<div id="chart" style="height: 350px;"></div>

<script>
function renderChart(data) {
    const chart = new ApexCharts(document.querySelector("#chart"), {
        chart: { type: 'pie' },
        series: Object.values(data),
        labels: Object.keys(data),
    });
    chart.render();
}

document.addEventListener("DOMContentLoaded", () => {
    renderChart(@json($chartData));
});
</script>

@script
Livewire.on('alert', ({type, message}) => {
    Swal.fire({ icon: type, title: message, timer: 1800, showConfirmButton: false });
});
@endScript

</x-layouts.app>
