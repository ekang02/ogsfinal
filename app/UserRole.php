<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_roles';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';
}
