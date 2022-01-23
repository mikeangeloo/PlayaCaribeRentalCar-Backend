<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function rol() {
        return $this->belongsTo(Roles::class, 'role_id', 'id')->select('id', 'rol');
    }

    public function area_trabajo() {
        return $this->belongsTo(AreasTrabajo::class, 'area_trabajo_id', 'id')->select('id', 'nombre as area');
    }

    public function sucursal() {
        return $this->belongsTo(Sucursales::class, 'sucursal_id', 'id')->select('id', 'codigo', 'nombre', 'direccion', 'cp');
    }

    public static function validateBeforeSave($request, $isUpdate = null) {
        $validate = Validator::make($request, [
            'area_trabajo_id' => 'required|exists:areas_trabajo,id',
            'role_id' => 'required|exists:roles,id',
            'nombre' => 'required|string',
            'apellidos' => 'required|string',
            'email' => 'required|email',
            'telefono' => 'required|string',
            'username' => 'nullable',
            'password' => 'nullable|string',
            'sucursal_id' => 'required|exists:sucursales,id',
            //'empresa_id' => 'required|exists:empresas,id'
        ]);

        if (is_null($isUpdate)) {
            $user = User::where('username', $request['username'])->first();
            if ($user) {
                return ['El username debe ser único'];
            }
        }

        if ($validate->fails()) {
            return $validate->errors()->all();
        }

        return true;
    }


}
