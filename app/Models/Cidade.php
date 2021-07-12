<?php

namespace App\Models;



use Eloquent as Model;

class Cidade extends Model
{
    public $table = 'cidades';
    


    public $fillable = [
        'cidade',
        'uf',
        'active'        
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'cidade' => 'string',
        'uf' => 'string',        
        'active' => 'boolean'        
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
