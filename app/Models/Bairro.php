<?php

namespace App\Models;



use Eloquent as Model;

class Bairro extends Model
{
    public $table = 'bairros';
    


    public $fillable = [
        'nome',        
        'cidade_id',
        'active'        
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'nome' => 'string',        
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    /*public static $rules = [
        'cidade' => 'required|max:255',
        'estado' => 'required|size:2'        
    ];*/
}
