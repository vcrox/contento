<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Municipio extends Model
{
    use HasFactory;
    protected $fillable = [
        'codigo',
        'descripcion',
        'codigo_estado',
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function estado(): BelongsTo{
        return $this->belongsTo(Estado::class,"codigo_estado","codigo");
    }
}
