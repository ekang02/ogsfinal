<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'year';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    public function section()
    {
        return $this->hasMany('App\Section','year_id', 'id');
    }
}
