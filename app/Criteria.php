<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'criteria';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    
    
    public function academicYear(){
        return $this->belongsTo('App\AcademicYear','academic_year_id', 'id'); 
    }
    public function assessment(){
        return $this->belongsTo('App\AssessmentLevel','assessment_id', 'id'); 
    }
    public function percentage(){
        return $this->hasMany('App\SubjectCriteriaPercentage','criteria_id', 'id');
    }
}
