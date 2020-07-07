<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentInfo extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'student_info';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    
    public function user(){
        return $this->belongsTo('App\User','user_id', 'id'); 
    }
    public function section(){
        return $this->hasOne('App\Section','id', 'section_id');
    }
    public function year(){
        return $this->hasOne('App\Year','id', 'year_id');
    }

    public function academicYear(){
        return $this->belongsTo('App\AcademicYear','academic_year_id', 'id'); 
    }
    public function grades(){
        return $this->hasMany('App\QuarterStudentGrade','user_id', 'user_id');
    }
    public function getFullName(){
      return $this->first_name . ' '. $this->last_name;
    }
}
