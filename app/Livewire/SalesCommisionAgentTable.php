<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Traits\WithSortingSearchPagination;
use Illuminate\Support\Facades\DB;

class SalesCommisionAgentTable extends Component
{
    use WithPagination, WithSortingSearchPagination;

    public function mount()
    {
        $this->sortField = 'name'; // Different default sort field
        $this->mountWithSortingSearchPagination();

        if (!auth()->user()->can('user.view') && !auth()->user()->can('user.create')) {
            abort(403, 'Unauthorized action.');
        }
    }
    public function render()
    {


        $business_id = request()->session()->get('user.business_id');

        $users = User::where('business_id', $business_id)
            ->where('is_cmmsn_agnt', 1)
            ->select([
                'id',
                DB::raw("CONCAT(COALESCE(surname, ''), ' ', COALESCE(first_name, ''), ' ', COALESCE(last_name, '')) as full_name"),
                'email',
                'contact_no',
                'address',
                'cmmsn_percent'
            ]);

        $this->search = trim($this->search);

        if (!empty($this->search)) {
            $users->where(function ($query) {
                $query->where('contact_no', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhereRaw("CONCAT(COALESCE(surname, ''), ' ', COALESCE(first_name, ''), ' ', COALESCE(last_name, '')) like ?", ["%{$this->search}%"]);
            });
        }

        $users = $users->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage == -1 ? 9999 : $this->perPage);

        return view('livewire.sales-commision-agent-table', ['users' => $users]);
    }
}
