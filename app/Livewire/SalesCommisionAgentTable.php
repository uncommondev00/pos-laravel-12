<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SalesCommisionAgentTable extends Component
{
    use WithPagination;

    public $search = ''; // Search input
    public $perPage = 5; // Items per page
    public $perPageOptions = [5, 10, 25, 50, 100]; // Dropdown options
    public $sortColumn = 'full_name'; // Default sort column
    public $sortDirection = 'asc'; // Default sort direction
    public $showModalCreate = false;
    public $showModalEdit = false;
    public $userId = null;

    protected $listeners = ['closeModal'];

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'search',
        'perPage'
    ];

    public function updatingSearch(){
        $this->resetPage();
    }

    public function updatingPerPage(){
        $this->resetPage();
    }

    public function updatePerPage()
     {
         $this->resetPage();
     }

     public function sortBy($column)
    {
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
    }


    public function openModalCreate($userId = null)
    {
        $this->userId = $userId;
        $this->showModalCreate = true;
        $this->dispatch('show-modal'); // 👈 Ensure modal is forced to be shown
    }

    public function openModalEdit($userId = null)
    {
        $this->userId = $userId;
        $this->showModalEdit = true;
        $this->dispatch('show-modal'); // 👈 Ensure modal is forced to be shown
    }

    public function closeModal()
    {
        $this->showModalCreate = false;
        $this->showModalEdit = false;
        $this->reset('userId');
    }

    public function render()
    {
        if (!auth()->user()->can('user.view') && !auth()->user()->can('user.create')) {
            abort(403, 'Unauthorized action.');
        }

            $business_id = request()->session()->get('user.business_id');

            $users = User::where('business_id', $business_id)
                        ->where('is_cmmsn_agnt', 1)
                        ->select(['id',
                            DB::raw("CONCAT(COALESCE(surname, ''), ' ', COALESCE(first_name, ''), ' ', COALESCE(last_name, '')) as full_name"),
                            'email', 'contact_no', 'address', 'cmmsn_percent']);

            $this->search = trim($this->search);

            if (!empty($this->search)) {
                $users->where(function ($query) {
                    $query->where('contact_no', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhereRaw("CONCAT(COALESCE(surname, ''), ' ', COALESCE(first_name, ''), ' ', COALESCE(last_name, '')) like ?", ["%{$this->search}%"]);
                });
            }
    
            $users = $users->orderBy($this->sortColumn, $this->sortDirection)
                ->paginate($this->perPage);

        return view('livewire.sales-commision-agent-table', ['users' => $users]);
    }
}
