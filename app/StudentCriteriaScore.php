<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentCriteriaScore extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'student_criteria_score';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    
    public function criteria(){
        return $this->hasOne('App\Criteria','id','criteria_id');
    }
}
