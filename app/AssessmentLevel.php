<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssessmentLevel extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'assessment_level';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    
    public function academicYear(){
        return $this->belongsTo('App\AcademicYear','academic_year_id', 'id'); 
    }
}
