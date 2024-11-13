<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function register(Request $request){
        $validateData = $request->validate([
            'name'=>['required','string','max:255'],
            'email'=>['required','string','email', 'max:255', 'unique:users'],
            'password'=>['required','string','min:8', 'max:20'],
        ]);

        $user = User::create([
            'name'=>$validateData['name'],
            'email'=>$validateData['email'],
            'password'=> Hash::make($validateData['password']),
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                "success"=>true,
                "erorrs"=>[
                    "code"=>0,
                    "msg"=> "",
                ],
                "data"=>[
                    "access_token"=>$token,
                    "token_type"=> "Bearer",
                ],
                "msg"=>"Usuario creado satisfactoriamente",
                "count"=>1
            ]);
    }

    public function login(Request $request){
        if(!Auth::attempt($request->only("email", "passwird"))){
            return response()->json([
                "success"=>false,
                "erros"=>[
                    "code"=>401,
                    "msg"=>"No se encotraron las credenciales"
                ],
                "data"=>"",
                "count"=> 0
            ],401);
        }
        $user = User::where("email", $request->email)->firstOrFail();
        $toke = $user->createToken("auth_token")->plainTextToken;

        return response()->json([
            "success"=>true,
            "errors"=>[
                "code"=>200,
                "msg"=> ""
            ],
            "data"=>"Ha iniciado sesión correctamente",
            "count"=> 1
        ],200);
    }

    public function me(Request $request){
        return response()->json([
            "success"=>true,
            "erros"=>[
                "code"=>200,
                "msg"=>""
            ],
            "data"=>$request->user(),
            "count"=> 1
        ],200);
    }
}
