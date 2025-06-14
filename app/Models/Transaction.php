<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function purchase_lines()
    {
        return $this->hasMany(PurchaseLine::class);
    }

    public function sell_lines()
    {
        return $this->hasMany(TransactionSellLine::class);
    }

    public function getVatableAttribute()
    {
        return $this->sell_lines()
            ->selectRaw('SUM(IF(tax_id >= 2, 0, unit_price*quantity)) as vatable')
            ->value('vatable');
    }

    public function getVatAttribute()
    {
        return $this->sell_lines()
            ->selectRaw('SUM(IF(tax_id > 1, 0, item_tax*quantity)) as vat')
            ->value('vat');
    }

    public function getVatExemptAttribute()
    {
        return $this->sell_lines()
            ->selectRaw('SUM(IF(tax_id != 2, 0, unit_price*quantity)) as vat_exempt')
            ->value('vat_exempt');
    }

    public function getVatZeroRatedAttribute()
    {
        return $this->sell_lines()
            ->selectRaw('SUM(IF(tax_id != 3, 0, unit_price*quantity)) as vat_zero_rated')
            ->value('vat_zero_rated');
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    public function payment_lines()
    {
        return $this->hasMany(TransactionPayment::class);
    }

    public function location()
    {
        return $this->belongsTo(BusinessLocation::class, 'location_id');
    }

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function tax()
    {
        return $this->belongsTo(TaxRate::class, 'tax_id');
    }

    public function stock_adjustment_lines()
    {
        return $this->hasMany(StockAdjustmentLine::class);
    }

    public function sales_person()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function return_parent()
    {
        return $this->hasOne(Transaction::class, 'return_parent_id');
    }

    public function table()
    {
        return $this->belongsTo(Restaurant\ResTable::class, 'res_table_id');
    }

    public function service_staff()
    {
        return $this->belongsTo(User::class, 'res_waiter_id');
    }

    public function recurring_invoices()
    {
        return $this->hasMany(Transaction::class, 'recur_parent_id');
    }

    public function recurring_parent()
    {
        return $this->hasOne(Transaction::class, 'id', 'recur_parent_id');
    }

    /**
     * Retrieves documents path if exists
     */
    public function getDocumentPathAttribute()
    {
        $path = !empty($this->document) ? asset('/uploads/documents/' . $this->document) : null;

        return $path;
    }

    /**
     * Removes timestamp from document name
     */
    public function getDocumentNameAttribute()
    {
        $document_name = !empty(explode("_", $this->document, 2)[1]) ? explode("_", $this->document, 2)[1] : $this->document;
        return $document_name;
    }

    public function subscription_invoices()
    {
        return $this->hasMany(Transaction::class, 'recur_parent_id');
    }

    /**
     * Shipping address custom method
     */
    public function shipping_address($array = false)
    {
        $addresses = !empty($this->order_addresses) ? json_decode($this->order_addresses, true) : [];

        $shipping_address = [];

        if (!empty($addresses['shipping_address'])) {
            if (!empty($addresses['shipping_address']['shipping_name'])) {
                $shipping_address['name'] = $addresses['shipping_address']['shipping_name'];
            }
            if (!empty($addresses['shipping_address']['company'])) {
                $shipping_address['company'] = $addresses['shipping_address']['company'];
            }
            if (!empty($addresses['shipping_address']['shipping_address_line_1'])) {
                $shipping_address['address_line_1'] = $addresses['shipping_address']['shipping_address_line_1'];
            }
            if (!empty($addresses['shipping_address']['shipping_address_line_2'])) {
                $shipping_address['address_line_2'] = $addresses['shipping_address']['shipping_address_line_2'];
            }
            if (!empty($addresses['shipping_address']['shipping_city'])) {
                $shipping_address['city'] = $addresses['shipping_address']['shipping_city'];
            }
            if (!empty($addresses['shipping_address']['shipping_state'])) {
                $shipping_address['state'] = $addresses['shipping_address']['shipping_state'];
            }
            if (!empty($addresses['shipping_address']['shipping_country'])) {
                $shipping_address['country'] = $addresses['shipping_address']['shipping_country'];
            }
            if (!empty($addresses['shipping_address']['shipping_zip_code'])) {
                $shipping_address['zipcode'] = $addresses['shipping_address']['shipping_zip_code'];
            }
        }

        if ($array) {
            return $shipping_address;
        } else {
            return implode(', ', $shipping_address);
        }
    }

    /**
     * billing address custom method
     */
    public function billing_address($array = false)
    {
        $addresses = !empty($this->order_addresses) ? json_decode($this->order_addresses, true) : [];

        $billing_address = [];

        if (!empty($addresses['billing_address'])) {
            if (!empty($addresses['billing_address']['billing_name'])) {
                $billing_address['name'] = $addresses['billing_address']['billing_name'];
            }
            if (!empty($addresses['billing_address']['company'])) {
                $billing_address['company'] = $addresses['billing_address']['company'];
            }
            if (!empty($addresses['billing_address']['billing_address_line_1'])) {
                $billing_address['address_line_1'] = $addresses['billing_address']['billing_address_line_1'];
            }
            if (!empty($addresses['billing_address']['billing_address_line_2'])) {
                $billing_address['address_line_2'] = $addresses['billing_address']['billing_address_line_2'];
            }
            if (!empty($addresses['billing_address']['billing_city'])) {
                $billing_address['city'] = $addresses['billing_address']['billing_city'];
            }
            if (!empty($addresses['billing_address']['billing_state'])) {
                $billing_address['state'] = $addresses['billing_address']['billing_state'];
            }
            if (!empty($addresses['billing_address']['billing_country'])) {
                $billing_address['country'] = $addresses['billing_address']['billing_country'];
            }
            if (!empty($addresses['billing_address']['billing_zip_code'])) {
                $billing_address['zipcode'] = $addresses['billing_address']['billing_zip_code'];
            }
        }

        if ($array) {
            return $billing_address;
        } else {
            return implode(', ', $billing_address);
        }
    }

    public function cash_register_payments()
    {
        return $this->hasMany(CashRegisterTransaction::class);
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'model');
    }
}
