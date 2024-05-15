<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Estado extends Model
{
    use HasFactory;
    protected $fillable = [
        'codigo',
        'descripcion',
        'codigo_pais',
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function pais(): BelongsTo{
        return $this->belongsTo(Pais::class,"codigo_pais","codigo");
    }
}
