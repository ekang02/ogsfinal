<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuarterStudentGrade extends Model
{
    
   	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'quarter_student_grade';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';


    public function subject()
    {
        return $this->hasOne('App\Subject','id','subject_id');
    }

    public function quarter()
    {
        return $this->hasOne('App\Quarter','id','quarter_id');
    }
    public function studentInfo()
    {
        return $this->hasOne('App\StudentInfo','user_id', 'user_id');
    }
    public function academicYear(){
        return $this->belongsTo('App\AcademicYear','academic_year_id', 'id'); 
    }
}
