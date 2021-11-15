<?php

namespace App\Http\Controllers;

use App\Enums\AudienceEnum;
use App\Enums\JsonResponse;
use App\Helpers\GeneralValidatorsHelper;
use Illuminate\Http\Request;
use App\Helpers\JWTManager;
use App\Helpers\Odoo;
use App\Mail\UserRecoveyPsw;
use App\Mail\ActivactionCode;
use App\Models\AppUserDevices;
use App\Models\Operadores;
use App\Models\User;
use App\Models\UserTracking;
use App\Models\UserVerificationToken;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PHPUnit\Framework\Constraint\Operator;

class SessionController extends Controller
{
    // Método para autentificar al usuario a traves de las credenciales proporcionadas
    public function login(Request $request)
    {
        $validPrefixes = ['api/dash', 'api/agents', 'api/clients'];


        // Validamos datos recibidos por el request
        $validateData = GeneralValidatorsHelper::validateBeforeLogin($request->all());

        if ($validateData !== true) {
            return response()->json(['ok' => false, 'error' => $validateData], JsonResponse::BAD_REQUEST);
        }

        // Forzamos a utilizar la guardia api
        Auth::shouldUse('api');
        $passCheck = false;

        $jwtExp = null;

        $password = trim($request->password);

        // Verificamos si es usuario admin por el prefijo
        $routePrefix = (object) $request->route()->action;
        if (in_array($routePrefix->prefix, $validPrefixes) === false) {
            return response()->json(['ok' => false, 'error' => ['Endpoint inváido']], JsonResponse::UNAUTHORIZED);
        }

        if ($routePrefix->prefix === 'api/dash') {
            $username = trim($request->username);
            $user = User::where('username', '=', $username)->first();
        } else if ($routePrefix->prefix === 'api/agents') {
            return response()->json(['ok' => false, 'error' => ['Endpoint en desarrollo']], JsonResponse::UNAUTHORIZED);
        } else if ($routePrefix->prefix === 'api/clients') {
            return response()->json(['ok' => false, 'error' => ['Endpoint en desarrollo']], JsonResponse::UNAUTHORIZED);
        }

        if (is_null($user)) {
            //User::agent_log('', 'NO', $request->idusr, $request->psw, 'Unregistered user');
            return response()->json(['ok' => false, 'errors' => ['Usuario no registrado']], JsonResponse::BAD_REQUEST);
        }

        if ($routePrefix->prefix === 'api/dash') {
            $audience = AudienceEnum::DASH;
            $jwtExp = time() + (7 * 24 * 60 * 60);
        } else if ($routePrefix->prefix === 'api/agents') {
            $audience = AudienceEnum::AGENTS;
            $jwtExp = time() + (7 * 24 * 60 * 60);
        } else if ($routePrefix->prefix === 'api/clients') {
            $audience = AudienceEnum::CLIENTS;
            $jwtExp = time() + (7 * 24 * 60 * 60);
        }

        $passCheck = Hash::check($password, $user->password);

        // revisar si el usuario esta activo
        if ($user->active === false) {
            // generamos token de verificación y enviamos correo
            $tokenData = $this->generateRecoveryToken($user->id, 2, $audience);
            if ($tokenData->ok !== true) {
                return response()->json($tokenData, JsonResponse::BAD_REQUEST);
            }
            try {
                Mail::to($user->email)->send(new ActivactionCode($tokenData->token, Carbon::now()));
            } catch(\Exception $e) {
                Log::debug($e);
                return response()->json(['ok' => false, 'errors' => ['No fue posible enviar el correo de activación']], JsonResponse::BAD_REQUEST);
            }
            return response()->json([
                'ok' => true,
                'validateCode' => true
            ], JsonResponse::OK);
        }

        if ($passCheck) {
            $id = $user->id;
            $jwt = JWTManager::createJwt($id, $audience, $jwtExp);
            if ($jwt === false) {
                return response()->json([
                    'ok' => false,
                    'errors' => ['Error durante generación de token']
                ], JsonResponse::BAD_REQUEST);
            }

            $message = 'Bienvenido nuevamente ';
            $message .= ($audience ===  'admin' || $audience ===  'operators') ? $user->login :  $user->name;

            return response()->json(
                [
                    'ok' => true,
                    'token' => $jwt,
                    'message' => $message
                ],
                JsonResponse::OK
            );
        }

        //User::agent_log($user->idusr, 'NO', $request->idusr, $request->psw, 'Invalid credentials');
        return response()->json(
            [
                'ok' => false,
                'errors' => ['Credenciales inválidas']
            ],
            JsonResponse::BAD_REQUEST
        );
    }

    public function activateUserByCode(Request $request) {
        $validateData = Validator::make($request->all(), [
            'verifyEmail' => 'required|email',
            'recoveryToken' => 'required',
            'recoveryToken.token' => 'required'
        ]);

        if ($validateData->fails()) {
            return response()->json([
                'ok' => false,
                'errors' => $validateData->errors()->all()
            ], JsonResponse::BAD_REQUEST);
        }

        $tokenData = $this->checkRecoveryToken($request->recoveryToken['token']);

        if ($tokenData->ok !== true) {
            return response()->json($tokenData, JsonResponse::BAD_REQUEST);
        }

        $userId = $tokenData->data->foreign_id;

        // TODO: verificar lo de audiencias
        if ($tokenData->data->audience === AudienceEnum::DASH) {
            $userData = User::where('id', '=', $userId)
            ->where('email', '=', $request->verifyEmail)
            ->where('active', '=', false)
            ->first();
        } else if ($tokenData->data->audience === AudienceEnum::AGENTS) {
            return response()->json([
                'ok' => false,
                'errors' => ['Audiencia en desarrollo']
            ], JsonResponse::BAD_REQUEST);
        } else if ($tokenData->data->audience === AudienceEnum::CLIENTS) {
            return response()->json([
                'ok' => false,
                'errors' => ['Audiencia en desarrollo']
            ], JsonResponse::BAD_REQUEST);
        }

        if (!$userData) {
            return response()->json([
                'ok' => false,
                'errors' => ['Usuario no encontrado']
            ], JsonResponse::BAD_REQUEST);
        }

        if ($tokenData->data->type !== 2) {
            return response()->json([
                'ok' => false,
                'errors' => ['Este token es inválido']
            ], JsonResponse::BAD_REQUEST);
        }

        $userData->active = true;
        if ($userData->save()) {
            // Invalidamos token si existen
            $this->invalidateTokens($userData->id, $tokenData->data->type, $tokenData->data->audience);
            return response()->json([
                'ok' => true,
                'message' => 'Se ha activado correctamente su cuenta, ya puede iniciar sesión'
            ], JsonResponse::OK);
        } else {
            return response()->json([
                'ok' => false,
                'errors' => ['Hubo un error, no se pudo activar su cuenta, intente nuevamente']
            ], JsonResponse::BAD_REQUEST);
        }
    }

    // Método para modificar username o email
    public function changeUsernameOrEmail(Request $request)
    {
        $user = $request->user;
        $errors = [];
        if ($request->has('email')) {
            $checkEmail = User::where('email', '=', $request->email)
                ->where('id', '!=', $user->id)
                ->first();
            if (is_null($checkEmail)) {
                $user->email = $request->email;
            } else {
                array_push($errors, 'Este correo ya esta registrado en nuestro sistema, intente con uno nuevo o inicie sesión con la cuenta correspondiete');
            }
        }

        if ($request->has('username')) {
            $username = Str::upper($request->username);
            $checkUsername = User::where('username_app', '=', $username)
                ->where('id', '!=', $user->id)
                ->first();
            if (is_null($checkUsername)) {
                $user->username_app = $username;
            } else {
                array_push($errors, 'Este nombre de usuario ya esta registrado en nuestro sistema, intente con uno nuevo o inicie sesión con la cuenta correspondiete');
            }
        }


        if (count($errors) > 0) {
            return response()->json([
                'ok' => false,
                'errors' => $errors
            ], JsonResponse::BAD_REQUEST);
        }

        if ($user->save()) {
            return response()->json([
                'ok' => true,
                'message' => 'Información actualizada correctamente'
            ], JsonResponse::OK);
        } else {
            return response()->json([
                'ok' => false,
                'errors' => ['Algo salio mal, intenta nuevamente']
            ], JsonResponse::BAD_REQUEST);
        }


    }

    // función para cambiar contraseña de sesión
    public function changePwd(Request $request)
    {
        $validateData = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'new_password' => 'required|string'
        ]);

        if ($validateData->fails()) {
            return response()->json([
                'ok' => false,
                'errors' => $validateData->errors()->all()
            ], JsonResponse::BAD_REQUEST);
        }
        if ($request->audience === AudienceEnum::DASH) {
            $userId = $request->user->id;

            $userData = User::where('id', '=', $userId)
            ->where('active', '=', true)
            ->first();
        } else if ($request->audience === AudienceEnum::AGENTS) {
            return response()->json([
                'ok' => false,
                'errors' => ['Audiencia en desarrollo']
            ], JsonResponse::BAD_REQUEST);
        } else if ($request->audience === AudienceEnum::CLIENTS) {
            return response()->json([
                'ok' => false,
                'errors' => ['Audiencia en desarrollo']
            ], JsonResponse::BAD_REQUEST);
        }

        if (!$userData) {
            return response()->json([
                'ok' => false,
                'errors' => ['Usuario no encontrado']
            ], JsonResponse::BAD_REQUEST);
        }

        if (Hash::check($request->old_password, $userData->password)) {
            $userData->password = Hash::make($request->new_password);
            if ($userData->save()) {
                return response()->json([
                    'ok' => true,
                    'message' => 'Su contraseña fue cambiada correctamente'
                ], JsonResponse::OK);
            }
        } else {
            return response()->json([
                'ok' => false,
                'errors' => ['La contraseña anterior no concide con la registrada, intente nuevamente']
            ], JsonResponse::BAD_REQUEST);
        }
    }

    /**
     * //TODO: falta funcionalidad
     * función para obtener listado del perfil
     */
    public function getProfileData(Request $request) {
        return response()->json([
            'ok' => false,
            'errors' => ['Funcion en desarrollo']
        ], JsonResponse::BAD_REQUEST);
    }

    public function generateRecoveryPswToken(Request $request) {
        $validateData = Validator::make($request->all(), [
            'email' => 'required',
            'audience' => 'required|numeric'
        ]);

        if ($validateData->fails()) {
            return response()->json([
                'ok' => false,
                'errors' => $validateData->errors()->all()
            ], JsonResponse::BAD_REQUEST);
        }

        if ($request->audience === AudienceEnum::DASH) {
            $user = User::where('email', '=', $request->email)->first();
            if (!$user) {
                return response()->json([
                    'ok' => false,
                    'errors' =>['No se encontro este correo registrado, intente con otro']
                ], JsonResponse::BAD_REQUEST);
            }
            $foreingId = $user->id;
        } else if ($request->audience === AudienceEnum::AGENTS) {
            return response()->json([
                'ok' => false,
                'errors' => ['Audiencia en desarrollo']
            ], JsonResponse::BAD_REQUEST);
        } else if ($request->audience === AudienceEnum::CLIENTS) {
            return response()->json([
                'ok' => false,
                'errors' => ['Audiencia en desarrollo']
            ], JsonResponse::BAD_REQUEST);
        }

        $tokenData = $this->generateRecoveryToken($foreingId, 1, $request->audience);

        if ($tokenData->ok !== true) {
            return response()->json(
                $tokenData
            ,JsonResponse::BAD_REQUEST);
        }
        $token = $tokenData->token;
        try {
            Mail::to($request->email)->send(new UserRecoveyPsw($token, Carbon::now()));
            return response()->json(['ok' => true, 'message' => 'Se ha enviado su token de recuperación de contraseña'], JsonResponse::OK);
        } catch(\Exception $e) {

            Log::debug($e);
            return response()->json([
                'ok' => false,
                'errors' => ['Hubo un error al generar su token de recuperación, intente nuevamente']
            ], JsonResponse::BAD_REQUEST);
        }
    }

    // función para cambiar contraseña de sesión por generación de token
    public function changePwdByToken(Request $request)
    {
        $validateData = Validator::make($request->all(), [
            'verifyEmail' => 'required|email',
            'new_password' => 'required|string',
            'recoveryToken' => 'required',
            'recoveryToken.token' => 'required'
        ]);

        if ($validateData->fails()) {
            return response()->json([
                'ok' => false,
                'errors' => $validateData->errors()->all()
            ], JsonResponse::BAD_REQUEST);
        }
        $tokenData = $this->checkRecoveryToken($request->recoveryToken['token']);

        if ($tokenData->ok !== true) {
            return response()->json($tokenData, JsonResponse::BAD_REQUEST);
        }

        $userId = $tokenData->data->foreign_id;

        if ($tokenData->data->audience === AudienceEnum::DASH) {
            $userData = User::where('id', '=', $userId)
            ->where('email', '=', $request->verifyEmail)
            ->where('active', '=', true)
            ->first();
        } else if ($tokenData->data->audience === AudienceEnum::AGENTS) {
            return response()->json([
                'ok' => false,
                'errors' => ['Audiencia en desarrollo']
            ], JsonResponse::BAD_REQUEST);
        } else if ($tokenData->data->audience === AudienceEnum::CLIENTS) {
            return response()->json([
                'ok' => false,
                'errors' => ['Audiencia en desarrollo']
            ], JsonResponse::BAD_REQUEST);
        }

        if (!$userData) {
            return response()->json([
                'ok' => false,
                'errors' => ['Usuario no encontrado']
            ], JsonResponse::BAD_REQUEST);
        }

        if ($tokenData->data->type !== 1) {
            return response()->json([
                'ok' => false,
                'errors' => ['Este token es inválido']
            ], JsonResponse::BAD_REQUEST);
        }

        $userData->password = Hash::make(trim($request->new_password));

        if ($userData->save()) {
            // Invalidamos token si existen
            $this->invalidateTokens($userId, $tokenData->data->type, $tokenData->data->audience);

            return response()->json([
                'ok' => true,
                'message' => 'Su contraseña fue cambiada correctamente'
            ], JsonResponse::OK);
        } else {
            return response()->json([
                'ok' => false,
                'errors' => ['Hubo un error al momento de cambiar su contraseña, intente nuevamente']
            ], JsonResponse::BAD_REQUEST);
        }
    }

    public function reviewToken(Request $request) {
        $validateData = Validator::make($request->all(), [
            'token' => 'required'
        ]);

        if ($validateData->fails()) {
            return response()->json([
                'ok' => false,
                'errors' => $validateData->errors()->all()
            ], JsonResponse::BAD_REQUEST);
        }

        $validateToken = $this->checkRecoveryToken($request->token);

        if ($validateToken->ok !== true) {
            return response()->json(
                $validateToken
            , JsonResponse::BAD_REQUEST);
        }

        return response()->json([
            'ok' => true,
            'data' => $validateToken->data
        ], JsonResponse::OK);
    }

    //region PRIVATE FUNCTIONS
    private function checkRecoveryToken($token) {
        $validate = UserVerificationToken::select('token', 'type', 'foreign_id', 'audience')->where('token', '=', $token)->where('active', '=', true)->orderBy('id', 'DESC')->first();

        if (!$validate) {
            return (object) ['ok' => false, 'errors' => ['Token inválido']];
        }

        return (object) ['ok' => true, 'data' => $validate];
    }

    private function generateRecoveryToken($user_id, $type, $audience) {
        $token = Str::upper(Str::random(4));
        //dd($token);

        DB::beginTransaction();
        try {
            // Invalidamos token si existen
           $this->invalidateTokens($user_id, $type, $audience);

            // Creamos nuevo token
            $utoken = new UserVerificationToken();
            $utoken->foreign_id = $user_id;
            $utoken->audience = $audience;
            $utoken->token = $token;
            $utoken->date_reg = Carbon::now();
            $utoken->active = true;
            $utoken->type = $type;
            $utoken->save();

            DB::commit();

            return (object) ['ok' => true, 'token' => $token];
        } catch (\Exception $e) {
            DB::rollBack();
            // Rethrow exception
            Log::debug($e);
            return (object) ['ok' => false, 'errors' => ['No fue posible generar el token de verificación']];
        }
    }

    private function invalidateTokens($user_id, $type, $audience) {
         // Invalidate old tokens if exists.
         UserVerificationToken::query()
         ->where('foreign_id', '=', $user_id)
         ->where('audience', '=', $audience)
         ->where('type', '=', $type)
         ->delete();
    }
    //endregion

}
