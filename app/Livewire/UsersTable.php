<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\WithoutUrlPagination;

class UsersTable extends Component
{
    use WithPagination, WithoutUrlPagination;

    protected $listeners = ['refreshUsersTable' => '$refresh', 'deleteUser' => 'delete'];

     // Make sure query parameters persist in the URL
     protected $queryString = [
        'search' => ['except' => 1], // Default empty
        'perPage' => ['except' => 5], // Default 5
    ];

    public $search = ''; // Search input
    public $perPage = 5; // Items per page
    public $perPageOptions = [5, 10, 25, 50, 100]; // Dropdown options
    public $sortColumn = 'username'; // Default sorting column
    public $sortDirection = 'asc'; // Sorting direction

    public function updatedPerPage()
    {
        $this->resetPage(); // Resets pagination when changing perPage
    }

    public function updatedSearch()
    {
        $this->resetPage(); // Resets pagination when searching
    }
 
     // ✅ Livewire calls this when the perPage dropdown is updated
     public function updatePerPage()
     {
         $this->resetPage();
     }

     public function updatingPage($page)
    {
        // Runs before the page is updated for this component...
        
    }
 
    public function updatedPage($page)
    {
        // Runs after the page is updated for this component...
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

    // // Emit an event to trigger Swal
    // public function confirmDelete($userId)
    // {
    //     $this->dispatch('swalConfirm', userId: $userId);
    // }

    // Perform delete action
    public function delete($userId)
    {
        if (!auth()->user()->can('user.delete')) {
            session()->flash('error', 'Unauthorized action.');
            return;
        }

        $user = User::find($userId);

        if ($user) {
            $user->delete();
            session()->flash('status', ['success' => true, 'msg' => 'User deleted successfully!']);
        } else {
            session()->flash('status', ['success' => false, 'msg' => 'User not found!']);
        }

        //$this->dispatch('userDeleted'); // Refresh frontend table
    }

    public function render()
    {
        //sleep(1);

        $business_id = session()->get('user.business_id');
        $user_id = session()->get('user.id');

        $users = User::where('business_id', $business_id)
            ->where('id', '!=', $user_id)
            ->where('id', '!=', 1)
            ->where('is_cmmsn_agnt', 0);

        // Force Livewire to detect changes and re-render
        $this->search = trim($this->search);

        if (!empty($this->search)) {
            $users->where(function ($query) {
                $query->where('username', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhereRaw("CONCAT(COALESCE(surname, ''), ' ', COALESCE(first_name, ''), ' ', COALESCE(last_name, '')) like ?", ["%{$this->search}%"]);
            });
        }

        $users = $users->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.users-table', ['users' => $users]);
    }
}
