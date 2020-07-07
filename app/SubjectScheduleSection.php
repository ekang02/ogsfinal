<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubjectScheduleSection extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subject_schedule_section';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public function section()
    {
        return $this->hasOne('App\Section','id','section_id');
    }
    public function subject()
    {
        return $this->hasOne('App\Subject','id','subject_id');
    }
}
