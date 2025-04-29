<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;

class CategoryTable extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $perPageOptions = [10, 25, 50, 100, -1];
    public $sortField = 'name';
    public $sortDirection = 'asc';
    
    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 10],
    ];

    public function mount()
    {
        if (!auth()->user()->can('category.view') && !auth()->user()->can('category.create')) {
            abort(403, 'Unauthorized action.');
        }

        $this->search = request()->query('search', $this->search);
        $this->sortField = request()->query('sortField', $this->sortField);
        $this->sortDirection = request()->query('sortDirection', $this->sortDirection);
        $this->perPage = request()->query('perPage', $this->perPage);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function updatePerPage()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
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
