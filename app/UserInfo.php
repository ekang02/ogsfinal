<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{    

	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_info';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public function user(){
        return $this->belongsTo('App\User','user_id', 'id'); 
    }
    public function getFullName(){
      return $this->first_name . ' '. $this->middle_name .' '. $this->last_name;
    }
}
