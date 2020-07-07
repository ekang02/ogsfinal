<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransmutationTable extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transmutation_table';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';
}
