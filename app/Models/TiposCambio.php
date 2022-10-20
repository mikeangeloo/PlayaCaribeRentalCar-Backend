<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiposCambio extends Model
{
    use HasFactory;
    protected $table = 'tipos_cambio';
    protected $primaryKey = 'id';

    public function divisa_base() {
        return $this->belongsTo(Divisas::class, 'divisa_base_id', 'id');
    }

    public function divisa_conversion() {
        return $this->belongsTo(Divisas::class, 'divisa_conversion_id', 'id');
    }
}
