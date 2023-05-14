<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Log;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Exceptions\HttpResponseException;

class AuthController extends Controller
{
    //
    public function register(Request $request){
        $validator = validator::make($request->all(),[
            'nama'=> 'required',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|min:8',
            'confirmation_password' => 'required|same:password'
        ]);

        if($validator->fails()){
            return messageError($validator->messages()->toArray());
        }

        $user = $validator->validated();

        User::create($user);

        $payload = [
            'nama'=> $user['nama'],
            'role'=> 'user',
            'lat' => now()->timestamp,
            'exp' => now()->timestamp + 7200 
        ];

        $token = JWT::encode($payload,env('JWT_SECRET_KEY'),'HS256');

        return response()->json([
            "data" => [
                'msg' => 'berhasil registrasi',
                'nama' => $user['nama'],
                'email' => $user['email'],
                'role' => 'user',
            ],
            "token" => "Bearer {$token}"
        ],200);
    }
    public function login(Request $request){
        $validator = validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return messageError($validator->messages()->toArray());
        }

        if(Auth::attempt($validator->validate())){
            $payload= [
                'nama'=> Auth::user()->nama,
                'role'=> Auth::user()->role,
                'iat'=> now()->timestamp,
                'exp'=> now()->timestamp + 7200
            ];

            $token = JWT::encode($payload,env('JWT_SECRET_KEY'),'HS256');

            Log::create([
                'module' => 'login',
                'action' => 'login akun',
                'useraccess' => Auth::user()->email
            ]);
            return response()->json([
                "data"=>[
                    'msg'=> "berhasil login",
                    'nama' => Auth::user()->nama,
                    'email' => Auth::user()->email,
                    'role' => Auth::user()->role,
                ],
                "token"=> "Bearer {$token}"
                ],200);
        }
            return response()->json("email atau password salah",422);
    }
}
