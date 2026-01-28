<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $job_no
 * @property string $service_category
 * @property int $customer_id
 * @property int $vehicle_id
 * @property int|null $is_appove
 * @property int $done_status
 * @property string $service_date
 */
class Service extends Model
{
    // For
    protected $table = 'tbl_services';

    public function notes()
    {
        return $this->morphMany(Notes::class, 'entity', 'entity_type', 'entity_id');
    }
}
