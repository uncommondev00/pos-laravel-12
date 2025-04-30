<?php

namespace App\Traits;

trait WithSortingSearchPagination
{
    // Base properties
    public $search = '';
    public $page = 1;
    public $perPage = 10;
    public $perPageOptions = [10, 25, 50, 100, -1];
    public $sortField = 'name';
    public $sortDirection = 'asc';
    
    // Will be filled by the component
    protected array $filterConfig = [];
    
    protected $paginationTheme = 'bootstrap';

    // Use the queryString property (not method) for Livewire to detect
    protected $queryString;

    public function initializeWithSortingSearchPagination()
    {
        // Initialize the query string array
        $this->queryString = $this->getQueryString();
        
    }

    protected function getQueryString()
    {
        $base = [
            'search' => ['except' => ''],
            'sortField' => ['except' => $this->sortField],
            'sortDirection' => ['except' => $this->sortDirection],
            'perPage' => ['except' => $this->perPage],
        ];
        
        $filterStrings = [];
        foreach ($this->filterConfig as $filter => $default) {
            // Remove the property_exists check - we'll ensure this in the component
            $filterStrings[$filter] = ['except' => $default];
        }
        
        return array_merge($base, $filterStrings);
    }

    public function mountWithSortingSearchPagination()
    {
        // Initialize from query string
        $this->search = request()->query('search', $this->search);
        $this->sortField = request()->query('sortField', $this->sortField);
        $this->sortDirection = request()->query('sortDirection', $this->sortDirection);
        $this->perPage = request()->query('perPage', $this->perPage);
        
        // Initialize filters from query string
        foreach ($this->filterConfig as $filter => $default) {
        
                $this->$filter = request()->query($filter, $default);
     
        }
    }

    public function updating($property)
    {
        $resetProperties = array_merge(
            ['search', 'perPage'],
            array_keys($this->filterConfig)
        );
        
        if (in_array($property, $resetProperties)) {
            $this->resetPage();
        }
    }

    protected function initializeSortField()
    {
        if (empty($this->sortField)) {
            $this->sortField = $this->defaultSortField();
        }
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
}