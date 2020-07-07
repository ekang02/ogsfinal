<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quarter extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'quarter';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';
}
