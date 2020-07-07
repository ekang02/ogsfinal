<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subject';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    
    public function year(){
        return $this->hasOne('App\Year','id', 'year_id');
    }
    public function academicYear(){
        return $this->belongsTo('App\AcademicYear','academic_year_id', 'id'); 
    }
    public function criteria_percentage(){
        return $this->hasMany('App\SubjectCriteriaPercentage','subject_id', 'id');
    }
}
