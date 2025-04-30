<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BusinessLocation;

class LocationTable extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $business_id = session()->get('user.business_id');

        $query = BusinessLocation::where('business_locations.business_id', $business_id)
            ->leftJoin('invoice_schemes as ic', 'business_locations.invoice_scheme_id', '=', 'ic.id')
            ->leftJoin('invoice_layouts as il', 'business_locations.invoice_layout_id', '=', 'il.id')
            ->select([
                'business_locations.name',
                'location_id',
                'landmark',
                'city',
                'zip_code',
                'state',
                'country',
                'business_locations.id',
                'ic.name as invoice_scheme',
                'il.name as invoice_layout'
            ]);

        $permitted_locations = auth()->user()->permitted_locations();
        if ($permitted_locations != 'all') {
            $query->whereIn('business_locations.id', $permitted_locations);
        }

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('business_locations.name', 'like', '%' . $this->search . '%')
                    ->orWhere('location_id', 'like', '%' . $this->search . '%')
                    ->orWhere('city', 'like', '%' . $this->search . '%')
                    ->orWhere('state', 'like', '%' . $this->search . '%');
            });
        }

        $locations = $query->paginate(10);

        return view('livewire.location-table', [
            'locations' => $locations
        ]);
    }
}
