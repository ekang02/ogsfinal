<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubjectSection extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subject_section';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public function academicYear(){
        return $this->belongsTo('App\AcademicYear','academic_year_id', 'id'); 
    }
    public function subject(){
        return $this->belongsTo('App\Subject','subject_id', 'id'); 
    }
    public function year(){
        return $this->hasOne('App\Year','id', 'year_id'); 
    }
    public function section(){
        return $this->hasOne('App\Section','id', 'section_id'); 
    }
    public function subjectGrades(){
        return $this->hasMany('App\QuarterStudentGrade','subject_id', 'subject_id'); 
    }

}
