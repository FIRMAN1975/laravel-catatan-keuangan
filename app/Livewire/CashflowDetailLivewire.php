<?php

namespace App\Livewire;

use App\Models\Cashflow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class CashflowDetailLivewire extends Component
{
    use WithFileUploads;

    public $auth;
    public $cashflow;
    public $editCoverCashflowFile;

    public $layout = 'layouts.app';

    public function mount($cashflow_id)
    {
        $this->auth = Auth::user();
        
        $this->cashflow = Cashflow::where('id', $cashflow_id)
            ->where('user_id', $this->auth->id)
            ->first();

        if (!$this->cashflow) {
            return redirect()->route('app.home');
        }
    }

    public function render()
    {
        return view('livewire.cashflow-detail-livewire');
    }

    public function editCoverCashflow()
    {
        $this->validate([
            'editCoverCashflowFile' => 'required|image|max:2048',
        ]);

        if ($this->cashflow->cover && Storage::disk('public')->exists($this->cashflow->cover)) {
            Storage::disk('public')->delete($this->cashflow->cover);
        }

        $filename = $this->auth->id . "_" . now()->format('YmdHis') . "." . $this->editCoverCashflowFile->getClientOriginalExtension();
        $path = $this->editCoverCashflowFile->storeAs('cashflow', $filename, 'public');

        $this->cashflow->update(['cover' => $path]);

        $this->reset(['editCoverCashflowFile']);
        $this->dispatch('closeModal', id: 'editCoverCashflowModal');
        $this->dispatch('toast', type: 'success', message: 'Bukti transaksi berhasil diperbarui!');
        
        $this->cashflow->refresh();
    }
}