<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CriteriaTotalScore extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'criteria_total_score';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';
}
