<?php

namespace App\Http\Controllers;
use DB;
use App\Models\Emails;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Mail;

class EmailsController extends Controller
{
    public function lote_emails(Request $request){
        try {

          $validator = Validator::make($request->all(),
               [
                   "json"=>'required',
                   "group"=>'required',
               ]
          );

          if($validator->fails()) {
              return response()->json(["status" => "failed", "message" => "Validation error", "errors" => $validator->errors()]);
          }

        } catch (\Exception $e) {
          return response()->error($e->getMessage(), $e->getCode());
        }

    }

    public function solicitud_lote_emails(Request $request){
        try {

          $validator = Validator::make($request->all(),
               [
                   "group"=>'required',
               ]
          );

          if($validator->fails()) {
              return response()->json(["status" => "failed", "message" => "Validation error", "errors" => $validator->errors()]);
          }

          //$apiURL = 'https://dummyjson.com/posts?skip=5&limit=10';
          $apiURL = 'https://backend.google.pgslocales.com/ServerToServer/lista_emails';
          $postInput = [
              'first_name' => 'Hardik',
              'last_name' => 'Savani',
              'email' => 'example@gmail.com'
          ];


          $headers = [
              'X-header' => 'value'
          ];

          $response     =   Http::withHeaders($headers)->get($apiURL, $postInput);
          $statusCode   =   $response->status();
          $responseBody =   json_decode($response->getBody(), true);

          if (!empty($responseBody)) {
            foreach ($responseBody as $key => $value) {
              $fields                     =   [
                                                "email"=>$value["email"],
                                                "nombres"=>$value["nombres"],
                                                "json"=>$value["json"],
                                                "group"=>$value["token"],
                                                "status"=>1
                                              ];
              $register                   =   Emails::create($fields);
              $register->save();
              //p($value,false);
            }
          }

        } catch (\Exception $e) {
          return response()->error($e->getMessage(), $e->getCode());
        }

    }


    public function email(Request $request){
        try {
          $validator = Validator::make($request->all(),
               [
                   "email"=>'required|email',
                   "nombres"=>'required',
                   "json"=>'required',
                   "group"=>'required',
               ]
          );

          if($validator->fails()) {
              return response()->json(["status" => "failed", "message" => "Validation error", "errors" => $validator->errors()]);
          }

        } catch (\Exception $e) {
          return response()->error($e->getMessage(), $e->getCode());
        }

    }
}
