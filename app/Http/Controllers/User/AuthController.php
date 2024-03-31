<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Http\Controllers\Traits\ValidatesRequest;
use App\Http\Resources\Auth\AuthFailResource;
use App\Http\Resources\Auth\AuthResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ValidatesRequest;

    public function login(Request $request) {
        $request->validate([
            'username' => 'required|min:4|max:60',
            'password' => 'required|min:8|max:65536'
        ]);

        $user = User::withTrashed()->where('username', $request->get('username'))->first();

        if (!$user || !Hash::check($request->get('password'), $user->password)) {
            return response()->json([
                'status' => 'invalid',
                'message' => 'Wrong username or password'
            ], 401);
        }

        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'token' => $token
        ], 200);
    }

    public function register(Request $request) {
        $request->validate([
            'username' => 'required|unique:users|min:4|max:60',
            'password' => 'required|min:8|max:65536'
        ]);

        $user = new User();
        $user->username = $request->get('username');
        $user->password = Hash::make($request->get('password'));
        $user->created_at = now();
        $user->save();

        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'token' => $token
        ], 201);
    }

    public function logout(Request $request) {
        auth('sanctum')->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success'
        ]);
    }
}
