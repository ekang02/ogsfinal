<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScoreSheet extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'score_sheet';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public function year()
    {
        return $this->hasOne('App\Year','id', 'year_id');
    }
    public function section()
    {
        return $this->hasOne('App\Section','id', 'section_id');
    }
    public function subject()
    {
        return $this->hasOne('App\Subject','id', 'subject_id');
    }
    public function criteria()
    {
        return $this->hasOne('App\Criteria','id', 'category_id');
    }
    public function quarter()
    {
        return $this->hasOne('App\Quarter','id', 'quarter_id');
    }
    public function sheetData()
    {
        return $this->hasMany('App\ScoreSheetData','score_sheet_id', 'id');    }
}
