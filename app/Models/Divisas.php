<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Divisas extends Model
{
    use HasFactory;
    protected $table = 'divisas';
    protected $primaryKey = 'id';
    public $timestamps = false;

}
