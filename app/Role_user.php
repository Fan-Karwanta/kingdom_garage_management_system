<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $role_id
 * @property int $user_id
 */
class Role_user extends Model
{
    //
    protected $table = 'role_users';

    protected $fillable = ['user_id', 'role_id'];
}
