<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Models\Board;

class Person extends Model
{
    //プリマリキーは入力の必要ない（番号自動割り振り）なので$guardedで保護
    protected $guarded = array('id');

    public static $rules = array(
        'name' => 'required',
        'mail' => 'email',
        'age' => 'integer|min:0|max:150'
    );

    public function getData()
    {
        return $this->id.':'.$this->name.'('.$this->age.')';
    }

    public function board()
    {
        return $this->hasOne('App\Models\Board');
    }
}
