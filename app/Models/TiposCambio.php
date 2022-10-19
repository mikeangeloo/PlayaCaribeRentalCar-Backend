<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiposCambio extends Model
{
    use HasFactory;
    protected $table = 'tipos_cambio';
    protected $primaryKey = 'id';
}
