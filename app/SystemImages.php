<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemImages extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_images';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';
}
