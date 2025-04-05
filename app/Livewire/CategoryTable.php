<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;

class CategoryTable extends Component
{
    use WithPagination;

    public $search = '';
    protected $paginationTheme = 'bootstrap';
    
    protected $listeners = ['refreshTable' => '$refresh'];

    public function mount()
    {
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
            ->paginate(10);

        return view('livewire.category-table', [
            'categories' => $categories
        ]);
    }

    public function getFormattedName($category)
    {
        return $category->parent_id != 0 ? '--' . $category->name : $category->name;
    }
}
