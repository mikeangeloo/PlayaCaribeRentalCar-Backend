<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiposTarifas extends Model
{
    use HasFactory;
    protected $table = 'tipos_tarifas';
    protected $primaryKey = 'id';
}
