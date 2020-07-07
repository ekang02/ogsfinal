<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubjectTeacher extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subject_teacher';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public function userInfo(){
        return $this->belongsTo('App\UserInfo','user_id', 'user_id'); 
    }

    public function academicYear(){
        return $this->belongsTo('App\AcademicYear','academic_year_id', 'id'); 
    }
    public function subject(){
        return $this->belongsTo('App\Subject','subject_id', 'id'); 
    }
    public function year(){
        return $this->hasOne('App\Year','id', 'year_id'); 
    }
    public function scheduleSection(){
        return $this->hasMany('App\SubjectScheduleSection','subject_teacher_id', 'id'); 
    }
}
