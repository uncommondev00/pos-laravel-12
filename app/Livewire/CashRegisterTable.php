<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CashRegister;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CashRegisterTable extends Component
{
    use WithPagination;

    public $search = '';
    public $userId = null;
    public $status = null;
    public $businessId;

    public function mount($businessId = null)
    {
        $this->businessId = $businessId ?? auth()->user()->business_id;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingUserId()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function render()
    {
        $registers = CashRegister::join('users as u', 'u.id', '=', 'cash_registers.user_id')
            ->where('cash_registers.business_id', $this->businessId)
            ->select(
                'cash_registers.*',
                DB::raw("CONCAT(COALESCE(surname, ''), ' ', COALESCE(first_name, ''), ' ', COALESCE(last_name, ''), '<br>', COALESCE(email, '')) as user_name")
            );

        if (!empty($this->userId)) {
            $registers->where('cash_registers.user_id', $this->userId);
        }

        if (!empty($this->status)) {
            $registers->where('cash_registers.status', $this->status);
        }

        if (!empty($this->search)) {
            $registers->whereRaw(
                "CONCAT(COALESCE(surname, ''), ' ', COALESCE(first_name, ''), ' ', COALESCE(last_name, ''), '<br>', COALESCE(email, '')) like ?",
                ["%{$this->search}%"]
            );
        }

        $users = User::forDropdown($this->businessId, false);

        return view('livewire.cash-register-table', [
            'registers' => $registers->paginate(10),
            'users' => $users,
        ]);
    }
}
