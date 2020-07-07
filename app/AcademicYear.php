<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'academic_year';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';


    public function fullSchooleYear(){
      return $this->from_year . ' - '. $this->to_year;
    }
}
