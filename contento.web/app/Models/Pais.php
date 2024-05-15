<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    use HasFactory;
    protected $table="paises";
    protected $fillable = [
        'codigo',
        'descripcion',
    ];

    protected $dates = ['created_at', 'updated_at'];
}
