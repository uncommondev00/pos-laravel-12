<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Traits\WithSortingSearchPagination;

class UsersTable extends Component
{
    use WithPagination, WithSortingSearchPagination;
    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        $this->sortField = 'username'; // Different default sort field
        $this->mountWithSortingSearchPagination();
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

        $users = $users->orderBy($this->sortField, $this->sortDirection)
        ->paginate($this->perPage == -1 ? 9999 : $this->perPage);

        return view('livewire.users-table', ['users' => $users]);
    }
}
