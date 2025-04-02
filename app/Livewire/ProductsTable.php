<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\SellingPriceGroup;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class ProductsTable extends Component
{
    use WithPagination;

    public $search = ''; // Search input
    public $perPage = 5; // Items per page
    public $perPageOptions = [5, 10, 25, 50, 100]; // Dropdown options
    public $sortColumn = 'name'; // Default sort column
    public $sortDirection = 'asc'; // Default sort direction

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
    
    public function render()
    {
        if (!auth()->user()->can('product.view') && !auth()->user()->can('product.create')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $selling_price_group_count = SellingPriceGroup::countSellingPriceGroups($business_id);

        $products = Product::leftJoin('brands', 'products.brand_id', '=', 'brands.id')
                ->leftJoin('units', 'products.unit_id', '=', 'units.id')
                ->leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                ->leftJoin('categories as c2', 'products.sub_category_id', '=', 'c2.id')
                ->leftJoin('tax_rates', 'products.tax', '=', 'tax_rates.id')
                ->leftJoin('variation_location_details as vld', 'products.id', '=', 'vld.product_id')
                ->where('products.business_id', $business_id)
                ->where('products.type', '!=', 'modifier')
                ->select(
                    'products.id',
                    'products.name as product',
                    'products.type',
                    'c1.name as category',
                    'c2.name as sub_category',
                    'units.actual_name as unit',
                    'brands.name as brand',
                    'tax_rates.name as tax',
                    'products.sku',
                    'products.image',
                    'products.enable_stock',
                    'products.is_inactive',
                    DB::raw('SUM((vld.qty_available)) as total_stock')
                )->groupBy('products.id');

        return view('livewire.products-table');
    }
}
