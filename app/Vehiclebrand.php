<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $vehicle_type_id
 * @property string $vehicle_brand
 * @property string|null $custom_field
 */
class Vehiclebrand extends Model
{
    //
    protected $table = 'tbl_vehicle_brands';
}
