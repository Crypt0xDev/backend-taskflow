<?php

namespace App\Modules\Access\Permission;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';

    protected $fillable = ['module', 'name', 'description'];
}
