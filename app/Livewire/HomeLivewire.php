<?php
// app/Livewire/HomeLivewire.php

namespace App\Livewire;

use App\Models\Cashflow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class HomeLivewire extends Component
{
    use WithFileUploads, WithPagination;

    public $auth;
    public $layout = 'layouts.app';

    // ================================
    // PENCARIAN & FILTER
    // ================================
    public $search = '';
    public $filterType = '';
    public $filterSource = '';
    public $startDate = '';
    public $endDate = '';

    // ================================
    // TAMBAH CASHFLOW
    // ================================
    public $addType;
    public $addSource;
    public $addLabel;
    public $addAmount;
    public $addDescription;
    public $addCover;
    public $addCreatedDate;

    // ================================
    // EDIT CASHFLOW
    // ================================
    public $editId;
    public $editType;
    public $editSource;
    public $editLabel;
    public $editAmount;
    public $editDescription;
    public $editCreatedDate;

    // ================================
    // EDIT COVER
    // ================================
    public $editCoverCashflowId;
    public $editCoverCashflowFile;

    // ================================
    // DELETE CASHFLOW
    // ================================
    public $deleteCashflowId, $deleteCashflowLabel, $deleteCashflowConfirmLabel;

    // ================================
    // STATISTIK
    // ================================
    public $chartData = [];
    public $summary = [];

    public function mount()
    {
        $this->auth = Auth::user();
        $this->addCreatedDate = now()->format('Y-m-d');
        $this->calculateSummary();
        $this->generateChartData();
    }

    public function updated($property)
    {
        if (in_array($property, ['search', 'filterType', 'filterSource', 'startDate', 'endDate'])) {
            $this->resetPage();
            $this->calculateSummary();
            $this->generateChartData();
        }
    }

    public function calculateSummary()
    {
        $query = Cashflow::where('user_id', $this->auth->id)
            ->search($this->search)
            ->type($this->filterType)
            ->source($this->filterSource)
            ->dateRange($this->startDate, $this->endDate);

        $this->summary = [
            'total_inflow' => (clone $query)->where('type', 'inflow')->sum('amount'),
            'total_outflow' => (clone $query)->where('type', 'outflow')->sum('amount'),
            'balance' => (clone $query)->where('type', 'inflow')->sum('amount') - 
                         (clone $query)->where('type', 'outflow')->sum('amount'),
            'total_transactions' => $query->count()
        ];
    }

    public function generateChartData()
    {
        $data = Cashflow::where('user_id', $this->auth->id)
            ->whereBetween('created_date', [
                $this->startDate ?: now()->subDays(30)->format('Y-m-d'),
                $this->endDate ?: now()->format('Y-m-d')
            ])
            ->selectRaw('DATE(created_date) as date, type, SUM(amount) as total')
            ->groupBy('date', 'type')
            ->orderBy('date')
            ->get();

        $inflowData = [];
        $outflowData = [];
        $dates = [];

        foreach ($data as $item) {
            $date = $item->date;
            if (!in_array($date, $dates)) {
                $dates[] = $date;
            }

            if ($item->type === 'inflow') {
                $inflowData[$date] = (float)$item->total;
            } else {
                $outflowData[$date] = (float)$item->total;
            }
        }

        $this->chartData = [
            'series' => [
                [
                    'name' => 'Pemasukan',
                    'data' => array_map(function($date) use ($inflowData) {
                        return $inflowData[$date] ?? 0;
                    }, $dates)
                ],
                [
                    'name' => 'Pengeluaran',
                    'data' => array_map(function($date) use ($outflowData) {
                        return $outflowData[$date] ?? 0;
                    }, $dates)
                ]
            ],
            'categories' => $dates
        ];
    }

    public function render()
    {
        $cashflows = Cashflow::where('user_id', $this->auth->id)
            ->search($this->search)
            ->type($this->filterType)
            ->source($this->filterSource)
            ->dateRange($this->startDate, $this->endDate)
            ->orderBy('created_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('livewire.home-livewire', [
            'cashflows' => $cashflows,
        ]);
    }

    // ================================
    // TAMBAH CASHFLOW
    // ================================
    public function addCashflow()
    {
        $this->validate([
            'addType' => 'required|in:inflow,outflow',
            'addSource' => 'required|in:cash,savings,loans',
            'addLabel' => 'required|string|max:255',
            'addAmount' => 'required|numeric|min:1',
            'addDescription' => 'nullable|string',
            'addCover' => 'nullable|image|max:2048',
            'addCreatedDate' => 'required|date',
        ]);

        $coverPath = null;
        if ($this->addCover) {
            $filename = $this->auth->id . "_" . now()->format('YmdHis') . "." . $this->addCover->getClientOriginalExtension();
            $coverPath = $this->addCover->storeAs('cashflow', $filename, 'public');
        }

        Cashflow::create([
            'user_id' => $this->auth->id,
            'type' => $this->addType,
            'source' => $this->addSource,
            'label' => $this->addLabel,
            'amount' => $this->addAmount,
            'description' => $this->addDescription,
            'cover' => $coverPath,
            'created_date' => $this->addCreatedDate,
        ]);

        $this->reset(['addType', 'addSource', 'addLabel', 'addAmount', 'addDescription', 'addCover']);
        $this->dispatch('closeModal', id: 'addCashflowModal');
        $this->dispatch('swal:success', message: 'Transaksi berhasil ditambahkan!');
        
        $this->calculateSummary();
        $this->generateChartData();
    }

    // ================================
    // EDIT COVER CASHFLOW
    // ================================
    public function prepareEditCover($id)
    {
        $this->editCoverCashflowId = $id;
        $this->editCoverCashflowFile = null;
        $this->dispatch('showModal', id: 'editCoverCashflowModal');
    }

    public function editCoverCashflow()
    {
        $this->validate([
            'editCoverCashflowFile' => 'required|image|max:2048',
        ]);

        $cashflow = Cashflow::where('id', $this->editCoverCashflowId)
            ->where('user_id', $this->auth->id)
            ->first();

        if (!$cashflow) {
            $this->dispatch('swal:error', message: 'Transaksi tidak ditemukan!');
            return;
        }

        if ($cashflow->cover && Storage::disk('public')->exists($cashflow->cover)) {
            Storage::disk('public')->delete($cashflow->cover);
        }

        $filename = $this->auth->id . "_" . now()->format('YmdHis') . "." . $this->editCoverCashflowFile->getClientOriginalExtension();
        $path = $this->editCoverCashflowFile->storeAs('cashflow', $filename, 'public');

        $cashflow->update(['cover' => $path]);

        $this->reset(['editCoverCashflowId', 'editCoverCashflowFile']);
        $this->dispatch('closeModal', id: 'editCoverCashflowModal');
        $this->dispatch('swal:success', message: 'Bukti transaksi berhasil diperbarui!');
    }

    // ================================
    // EDIT CASHFLOW
    // ================================
    public function prepareEdit($id)
    {
        $data = Cashflow::where('id', $id)->first();
        if (!$data) return;

        $this->editId = $data->id;
        $this->editType = $data->type;
        $this->editSource = $data->source;
        $this->editLabel = $data->label;
        $this->editAmount = $data->amount;
        $this->editDescription = $data->description;
        $this->editCreatedDate = $data->created_date->format('Y-m-d');

        $this->dispatch('showModal', id: 'editCashflowModal');
    }

    public function editCashflow()
    {
        $this->validate([
            'editType' => 'required|in:inflow,outflow',
            'editSource' => 'required|in:cash,savings,loans',
            'editLabel' => 'required|string|max:255',
            'editAmount' => 'required|numeric|min:1',
            'editDescription' => 'nullable|string',
            'editCreatedDate' => 'required|date',
        ]);

        $data = Cashflow::find($this->editId);
        if (!$data) return;

        $data->update([
            'type' => $this->editType,
            'source' => $this->editSource,
            'label' => $this->editLabel,
            'amount' => $this->editAmount,
            'description' => $this->editDescription,
            'created_date' => $this->editCreatedDate,
        ]);

        $this->reset(['editId', 'editType', 'editSource', 'editLabel', 'editAmount', 'editDescription', 'editCreatedDate']);
        $this->dispatch('closeModal', id: 'editCashflowModal');
        $this->dispatch('swal:success', message: 'Transaksi berhasil diperbarui!');
        
        $this->calculateSummary();
        $this->generateChartData();
    }

    // ================================
    // DELETE CASHFLOW
    // ================================
    public function prepareDelete($id)
    {
        $data = Cashflow::where('id', $id)->where('user_id', $this->auth->id)->first();
        if (!$data) return;

        $this->deleteCashflowId = $data->id;
        $this->deleteCashflowLabel = $data->label;
        $this->deleteCashflowConfirmLabel = null;

        $this->dispatch('showModal', id: 'deleteCashflowModal');
    }

    public function deleteCashflow()
    {
        $this->validate([
            'deleteCashflowConfirmLabel' => 'required|same:deleteCashflowLabel',
        ]);

        $target = Cashflow::where('id', $this->deleteCashflowId)
            ->where('user_id', $this->auth->id)
            ->first();

        if (!$target) return;

        if ($target->cover && Storage::disk('public')->exists($target->cover)) {
            Storage::disk('public')->delete($target->cover);
        }

        $target->delete();

        $this->reset(['deleteCashflowId', 'deleteCashflowLabel', 'deleteCashflowConfirmLabel']);
        $this->dispatch('closeModal', id: 'deleteCashflowModal');
        $this->dispatch('swal:success', message: 'Transaksi berhasil dihapus!');
        
        $this->calculateSummary();
        $this->generateChartData();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterType = '';
        $this->filterSource = '';
        $this->startDate = '';
        $this->endDate = '';
        $this->resetPage();
        $this->calculateSummary();
        $this->generateChartData();
    }
}