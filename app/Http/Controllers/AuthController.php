<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api',['except' => ['login','register']]);
    }

    public function register(Request $request)
    {
        $data = $request->all();
        $this->validate($request, [
            'name' => 'required|string|max:25',
            'email' => 'required|email|string|max:25|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);
        $data['password'] = Hash::make($request->password);
        User::create($data);
        return response()->json('success', 201);
    }

    public function login()
    {
       $credentials = request(['email','password']);
        if (!$token = auth()->claims(['name' => 'demo test'])->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'],401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * @param string $token
     * @return \Illuminate\Http\JsonResponse
     */
    private function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' =>  auth()->factory()->getTTL() * 60
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(\auth()->user());
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     *
     */
    public function refresh()
    {
       return $this->respondWithToken(auth()->refresh());
    }

    /**
     * @return mixed
     */
    public function payload()
    {
        return auth()->payload();
    }


}
