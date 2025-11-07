<?php

namespace App\Livewire;

use App\Models\Cashflow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class HomeLivewire extends Component
{
    use WithFileUploads;

    public $auth;

    public $layout = 'layouts.app';

    public function mount()
    {
        $this->auth = Auth::user();
    }

    public function render()
    {
        $cashflows = Cashflow::where('user_id', $this->auth->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.home-livewire', [
            'cashflows' => $cashflows,
        ]);
    }

    // ================================
    // TAMBAH CASHFLOW
    // ================================
    public $addType;
    public $addSource;
    public $addLabel;
    public $addAmount;
    public $addDescription;
    public $addCover;

    public function addCashflow()
    {
        $this->validate([
            'addType' => 'required|in:inflow,outflow',
            'addSource' => 'required|in:cash,savings,loans',
            'addLabel' => 'required|string|max:255',
            'addAmount' => 'required|numeric|min:1',
            'addDescription' => 'nullable|string',
            'addCover' => 'nullable|image|max:2048',
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
        ]);

        $this->reset(['addType', 'addSource', 'addLabel', 'addAmount', 'addDescription', 'addCover']);
        $this->dispatch('closeModal', id: 'addCashflowModal');
        $this->dispatch('toast', type: 'success', message: 'Transaksi berhasil ditambahkan!');
    }

    // ================================
    // EDIT COVER CASHFLOW
    // ================================
    public $editCoverCashflowId;
    public $editCoverCashflowFile;

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
            $this->dispatch('toast', type: 'error', message: 'Transaksi tidak ditemukan!');
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
        $this->dispatch('toast', type: 'success', message: 'Bukti transaksi berhasil diperbarui!');
    }

    // ================================
    // EDIT CASHFLOW
    // ================================
    public $editId;
    public $editType;
    public $editSource;
    public $editLabel;
    public $editAmount;
    public $editDescription;

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
        ]);

        $data = Cashflow::find($this->editId);
        if (!$data) return;

        $data->update([
            'type' => $this->editType,
            'source' => $this->editSource,
            'label' => $this->editLabel,
            'amount' => $this->editAmount,
            'description' => $this->editDescription,
        ]);

        $this->reset(['editId', 'editType', 'editSource', 'editLabel', 'editAmount', 'editDescription']);
        $this->dispatch('closeModal', id: 'editCashflowModal');
        $this->dispatch('toast', type: 'success', message: 'Transaksi berhasil diperbarui!');
    }

    // ================================
    // DELETE CASHFLOW (DENGAN KONFIRMASI LABEL)
    // ================================
    public $deleteCashflowId, $deleteCashflowLabel, $deleteCashflowConfirmLabel;

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
        $this->dispatch('toast', type: 'success', message: 'Transaksi berhasil dihapus!');
    }
}