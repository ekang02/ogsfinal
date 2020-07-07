<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScoreSheetData extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'score_sheet_data';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';


    public function studentInfo(){
        return $this->belongsTo('App\StudentInfo','user_id', 'user_id'); 
    }
    public function sheetData(){
        return $this->belongsTo('App\ScoreSheet','score_sheet_id', 'id'); 
    }
}
