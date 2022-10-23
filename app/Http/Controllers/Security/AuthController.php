<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @Route("/register") register a new user
     */
    public function registerUser(Request $request)
    {
        try {
            $datosUser = $request->validate([
                'name' => 'required|max:100',
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed',
            ]); //TODO: validate data of user
            $datosUser['password'] = Hash::make($request->password);
            $user = User::create($datosUser);
            $accessToken = $user->createToken('authToken')->accessToken;
            return response([
                'data' => $user,
                'access_token' => $accessToken,
                'message' => 'Registered',
            ]);
        } catch (\Throwable $th) {
            return $th;
        }
    }
    /**
     * @Route("/login") user login
     */
    public function loginUser(Request $request)
    {
        try {
            $dataLogin = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
            if (!auth()->attempt($dataLogin)) {
                return  response(['message' => 'Credentials incorrect']);
            }
            $accessToken = auth()->user()->createToken('authToken')->accessToken;;

            return response([
                'data' => auth()->user(),
                'access_token' => $accessToken,
                'message' => 'logged in',
            ]);
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
