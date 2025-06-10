<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use App\Traits\WithSortingSearchPagination;

class RolesTable extends Component
{
    use WithPagination, WithSortingSearchPagination;
    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        $this->sortField = 'name'; // Different default sort field
        $this->mountWithSortingSearchPagination();

        if (!auth()->user()->can('roles.view')) {
            abort(403, 'Unauthorized action.');
        }
    }
    public function render()
    {


        $business_id = request()->session()->get('user.business_id');

        $roles = Role::where('business_id', $business_id)
            ->where('id', '!=', 1)
            ->select(['name', 'id', 'is_default', 'business_id']);

        $this->search = trim($this->search);

        if (!empty($this->search)) {
            $roles->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            });
        }

        $roles = $roles->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage == -1 ? 9999 : $this->perPage);

        return view('livewire.roles-table', ['roles' => $roles]);
    }
}
