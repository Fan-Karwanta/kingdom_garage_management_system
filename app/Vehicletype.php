<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $vehicle_type
 * @property string|null $custom_field
 */
class Vehicletype extends Model
{
    //
    protected $table = 'tbl_vehicle_types';
}
