<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdvisoryClass extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'advisory_class';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    
    // public function userInfo(){
    //     return $this->belongsTo('App\UserInfo','teacher_id', 'user_id'); 
    // }

    public function section(){
        return $this->hasOne('App\Section','id','section_id');
    }
}
