<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TarifasApollo extends Model
{
    use HasFactory;
    protected $table = 'tarifas_apollo';
    protected $primaryKey = 'id';
}
