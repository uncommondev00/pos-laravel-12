<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brands;
use App\Models\Unit;
use App\Models\TaxRate;
use App\Models\SellingPriceGroup;
use Illuminate\Support\Facades\DB;

class ProductsTable extends Component
{
    use WithPagination;

    public $search = '';
    public $type;
    public $category_id;
    public $brand_id;
    public $unit_id;
    public $tax_id;
    public $perPage = 10;
    
    protected $queryString = [
        'search' => ['except' => ''],
        'type' => ['except' => ''],
        'category_id' => ['except' => ''],
        'brand_id' => ['except' => ''],
        'unit_id' => ['except' => ''],
        'tax_id' => ['except' => '']
    ];

    public function render()
    {
        $business_id = request()->session()->get('user.business_id');
        $selling_price_group_count = SellingPriceGroup::countSellingPriceGroups($business_id);

        $products = Product::query()
            ->with(['brand', 'unit', 'category', 'sub_category', 'product_tax'])
            ->select(
                'products.id',
                'products.name as product',
                'products.type',
                'products.category_id',
                'products.sub_category_id',
                'products.unit_id',
                'products.brand_id',
                'products.tax',
                'products.sku',
                'products.image',
                'products.enable_stock',
                'products.is_inactive'
            )
            ->where('products.business_id', $business_id)
            ->where('products.type', '!=', 'modifier')
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('products.name', 'like', '%' . $this->search . '%')
                      ->orWhere('products.sku', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->type, fn($q) => $q->where('products.type', $this->type))
            ->when($this->category_id, fn($q) => $q->where('products.category_id', $this->category_id))
            ->when($this->brand_id, fn($q) => $q->where('products.brand_id', $this->brand_id))
            ->when($this->unit_id, fn($q) => $q->where('products.unit_id', $this->unit_id))
            ->when($this->tax_id, fn($q) => $q->where('products.tax', $this->tax_id));

        // Get stock totals in a single query
        $stocks = DB::table('variation_location_details')
            ->select('product_id', DB::raw('SUM(qty_available) as total_stock'))
            ->groupBy('product_id');

        $products = $products
            ->leftJoinSub($stocks, 'stocks', function($join) {
                $join->on('products.id', '=', 'stocks.product_id');
            })
            ->select('products.*', 'stocks.total_stock')
            ->orderBy('products.name')
            ->paginate($this->perPage);

        return view('livewire.products-table', [
            'products' => $products,
            'selling_price_group_count' => $selling_price_group_count,
            'categories' => Category::forDropdown($business_id),
            'brands' => Brands::forDropdown($business_id),
            'units' => Unit::forDropdown($business_id),
            'taxes' => TaxRate::forBusinessDropdown($business_id, false)['tax_rates'],
            'rack_enabled' => (request()->session()->get('business.enable_racks') || 
                             request()->session()->get('business.enable_row') || 
                             request()->session()->get('business.enable_position'))
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}