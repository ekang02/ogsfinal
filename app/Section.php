<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'section';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    public function year()
    {
        return $this->belongsTo('App\Year','year_id', 'id');
    }
    public function academicYear(){
        return $this->belongsTo('App\AcademicYear','academic_year_id', 'id'); 
    }
    public function getSectionName(){
      return $this->section . ' - '. $this->label .') ';
    }

    public function students(){
        return $this->hasMany('App\StudentInfo','section_id', 'id');
    }

    public function subjectSections(){
        return $this->hasMany('App\SubjectSection','section_id', 'id'); 
    }
}
