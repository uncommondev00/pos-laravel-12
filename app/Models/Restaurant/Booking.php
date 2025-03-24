<?php

namespace App\Models\Restaurant;

use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function customer()
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    public function table()
    {
        return $this->belongsTo(ResTable::class, 'table_id');
    }

    public function correspondent()
    {
        return $this->belongsTo(User::class, 'correspondent_id');
    }

    public function waiter()
    {
        return $this->belongsTo(User::class, 'waiter_id');
    }

    public function location()
    {
        return $this->belongsTo(BusinessLocation::class, 'location_id');
    }

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }
}
