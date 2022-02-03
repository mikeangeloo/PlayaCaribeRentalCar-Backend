<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TarifasHoteles extends Model
{
    use HasFactory;
    protected $table = 'tarifas_hoteles';
    protected $primaryKey = 'id';
}
