<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $sales_service_id
 * @property string $invoice_number
 * @property int $payment_status
 */
class Invoice extends Model
{
    // For
    protected $table = 'tbl_invoices';

    public function notes()
    {
        return $this->morphMany(Notes::class, 'entity', 'entity_type', 'entity_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
