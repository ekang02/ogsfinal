<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubjectCriteriaPercentage extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subject_criteria_percentage';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public function criteria(){
        return $this->hasOne('App\Criteria','id','criteria_id');
    }
    public function academicYear(){
        return $this->belongsTo('App\AcademicYear','academic_year_id', 'id'); 
    }
}
