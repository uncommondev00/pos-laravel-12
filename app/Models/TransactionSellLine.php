<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionSellLine extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variations()
    {
        return $this->belongsTo(Variation::class, 'variation_id');
    }

    public function modifiers()
    {
        return $this->hasMany(TransactionSellLine::class, 'parent_sell_line_id');
    }

    /**
     * Get the quantity column.
     *
     * @param  string  $value
     * @return float $value
     */
    public function getQuantityAttribute($value)
    {
        return (float)$value;
    }

    public function lot_details()
    {
        return $this->belongsTo(PurchaseLine::class, 'lot_no_line_id');
    }

    public function get_discount_amount()
    {
        $discount_amount = 0;
        if (!empty($this->line_discount_type) && !empty($this->line_discount_amount)) {
            if ($this->line_discount_type == 'fixed') {
                $discount_amount = $this->line_discount_amount;
            } elseif ($this->line_discount_type == 'percentage') {
                $discount_amount = ($this->unit_price * $this->line_discount_amount) / 100;
            }
        }
        return $discount_amount;
    }

    /**
     * Get the unit associated with the purchase line.
     */
    public function sub_unit()
    {
        return $this->belongsTo(Unit::class, 'sub_unit_id');
    }

    public function order_statuses()
    {
        $statuses = [
            'received',
            'cooked',
            'served'
        ];
    }
}
