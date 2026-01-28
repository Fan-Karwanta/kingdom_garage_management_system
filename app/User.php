<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

/**
 * @property int $id
 * @property string $name
 * @property string $lastname
 * @property string $company_name
 * @property string $email
 * @property string $mobile_no
 * @property string $landline_no
 * @property string $gender
 * @property string $image
 * @property string $address
 * @property int $state_id
 * @property int $city_id
 * @property int $country_id
 * @property string $timezone
 * @property string $language
 * @property string $custom_field
 * @property string $role
 * @property int $role_id
 * @property int $branch_id
 * @property string $display_name
 * @property string $birth_date
 * @property string $join_date
 * @property string $left_date
 * @property string $designation
 * @property int $create_by
 */
class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<string>
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /* Code for New Accessrights */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_users');
    }

    // public function scopeGetByUser($query, $id)
    // {
    //     $role = getUsersRole(Auth::User()->role_id);
    //     if (isAdmin(Auth::User()->role_id)) {
    //         return $query;
    //     } else {
    //         return $query->where('id', Auth::User()->id);
    //     }
    // }

    /* Give permission to access rights */
    public function hasAccess(array $permissions)
    {
        foreach ($this->roles as $role) {
            if ($role->hasAccess($permissions)) {
                return true;
            }
        }

        return false;
    }

    public function notes()
    {
        return $this->morphMany(Notes::class, 'entity', 'entity_type', 'entity_id');
    }
}
