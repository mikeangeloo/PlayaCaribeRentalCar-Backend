<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelosDocs extends Model
{
    use HasFactory;
    protected $table = 'modelos_docs';
    protected $primaryKey = 'id';
}
