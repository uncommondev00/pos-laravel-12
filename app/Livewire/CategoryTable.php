<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;
use App\Traits\WithSortingSearchPagination;

class CategoryTable extends Component
{
    use WithPagination,WithSortingSearchPagination;
    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        $this->mountWithSortingSearchPagination();

        if (!auth()->user()->can('category.view') && !auth()->user()->can('category.create')) {
            abort(403, 'Unauthorized action.');
        }

    }

    public function render()
    {
        $business_id = request()->session()->get('user.business_id');

        $categories = Category::where('business_id', $business_id)
            ->select(['name', 'short_code', 'id', 'parent_id'])
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('short_code', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage == -1 ? 9999 : $this->perPage);


        return view('livewire.category-table', [
            'categories' => $categories
        ]);
    }

    public function getFormattedName($category)
    {
        return $category->parent_id != 0 ? '--' . $category->name : $category->name;
    }
}
