<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class schedule extends Model
{
    use HasFactory;

    protected $fillable = ['memberID','memberID2','memberID3','memberID4','memberID5','year','month','day','schedule','place','start_time','end_time','categoryID'];
}
