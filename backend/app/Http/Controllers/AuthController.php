<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use \Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use Mail;


class AuthController extends Controller
{

    // public function register(Request $request)
    // {
    //     try {
    //
    //       $validator = Validator::make($request->all(),
    //           [
    //               "email"=>'required|email',
    //               "password"=>'required',
    //           ]
    //       );
    //
    //       if($validator->fails()) {
    //           return response()->json(["status" => "failed", "message" => "Validation error", "errors" => $validator->errors()]);
    //       }
    //
    //       $child                      =   new User;
    //       $fields                     =   $request->only($child->getFillable());
    //       $fields["remember_token"]   =   md5(date("Y-m-d H:i:s"));
    //       $fields["password"]         =   Hash::make($request->input('password'));
    //       $fields["name"]             =   $request->input('email');
    //
    //       /*check user preview register*/
    //       $user                       =   User::select('id')->where('email', $request->input('email'))->first();
    //       if (empty($user)) {
    //         $user                     =   User::create($fields);
    //         $user->assignRole('Visitante');
    //
    //         /*enviamos correo de registro*/
    //         $subject  =   "Creación de cuenta usuario";
    //         $for      =   $user->email;
    //         $send=[
    //                                           "name"=>$user->name,
    //                                           "message"=>"Hemos registrado una cuenta en nuestra plataforma con tu correo electrónico <b>".$user->email."</b>, Gracias por confiar en nuestra plataforma.",
    //                                         ];
    //         Mail::send('register_user_form',["variables"=>$send], function($msj) use($subject,$for){
    //             $msj->from("fesolafiliacion@gmail.com","Afiliaciones FESOL");
    //             $msj->subject($subject);
    //             $msj->to($for);
    //         });
    //
    //       }else {
    //         return response()->error("Email se encuentra registrado en nuestra base de datos", 500);
    //       }
    //       $user                       =   User::select('token')->where('email', $request->input('email'))->first();
    //       return response()->success($user, 'Usuario creado con éxito');
    //     } catch (\Exception $e) {
    //       return response()->error($e->getMessage(), $e->getCode());
    //     }
    //
    // }

    private function revokeToken($user,$request){
      // Revoke all tokens...
      $user->tokens()->delete();

      // Revoke the token that was used to authenticate the current request...
      //$request->user()->currentAccessToken()->delete();

    }

    public function loginWithToken($id, Request $request){

      try {

        $user = User::where('id', $id)->first();

        if (!empty($user)) {

          //$this->revokeToken($user,$request);
          if (!empty($user->avatar)) {
            /*generate link*/
            $user->avatar   =   URL::asset($user->avatar);
          }else {
            /*img default*/
            $user->avatar   =   URL::asset("uploads/seeder/avatar.jpg");
          }

          $tokenResult      =  $user->createToken('Access Tokens');

          return response()->success([
              'access_token' => $tokenResult->plainTextToken,
              'token_type' => 'Bearer'
          ], 'Welcome!');

        }else {
          return response()->error("Error: Invalid token",404);
        }

      } catch (\Exception $e) {
        return response()->error($e->getMessage(), $e->getCode());
      }

    }

    /**
     * Inicio de sesión y creación de token
     */
    public function login(Request $request)
    {

        $request->validate([
          "email"=>'required|email',
          "password"=>'required',
        ]);

        $user = User::where('email', $request->input('email'))
                    //->where('status', 1)
                    ->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return response()->error('Usuario o contraseña incorrectos', 401);
        }



        if (!empty($user->avatar)) {
          /*generate link*/
          $user->avatar   =   URL::asset($user->avatar);
        }else {
          /*img default*/
          $user->avatar   =   URL::asset("uploads/seeder/avatar.jpg");
        }

        $user->getRoleNames();

        $tokenResult = $user->createToken('Access Tokens');


        return response()->success([
            'dashboard' => "dashboard",
            'user' => $user,
            'access_token' => $tokenResult->plainTextToken,
            'token_type' => 'Bearer'
        ], 'Bienvenido!');

    }

    /**
     * Cierre de sesión (anular el token)
     */
    public function logout(Request $request)
    {
        try {

          if (!empty($request->user()->id)) {
            DB::table('personal_access_tokens')->where('tokenable_id', $request->user()->id)->delete();
          }

          return response()->success([
              'message' => 'Se ha cerrado sesión con éxito'
          ], 'Logout');

            // print_r($request->input("access_token"));
            // exit;
            //
            //
            // $request->user()->token()->revoke();
            // $request->user()->token()->delete();
            //
            // return response()->success([
            //     'message' => 'Se ha cerrado sesión con éxito'
            // ], 'Logout');

        } catch (Exception $e) {
            return response()->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Obtener el objeto User como json
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    /*
      * Obtener estado de la conexión
    */

    public function status(){
      return response()->json([ "status"=>true,
                                "Dev"=>"Programandoweb",
                                "email"=>"lic.jorgemendez@gmail.com"]);
    }

    /*
      * Obtener csrf_token
    */

    public function csrf_token(Request $request){
      $token = $request->session()->token();
      $token = csrf_token();
      return response()->json([ "status"=>true,
                                "csrf_token"=>$token,
                                "Dev"=>"Programandoweb",
                                "email"=>"lic.jorgemendez@gmail.com"]);
    }


    /**
     * Cierre de sesión (anular el token)
     */
    public function exit(Request $request)
    {
        try {

            if (!empty($request->user()->id)) {
              DB::table('personal_access_tokens')->where('tokenable_id', $request->user()->id)->delete();
            }

            return response()->success([
                'message' => 'Se ha cerrado sesión con éxito'
            ], 'Logout');

        } catch (Exception $e) {
            return response()->error($e->getMessage(), $e->getCode());
        }
    }

}
