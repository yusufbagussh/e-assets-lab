<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index()
    {
        return view('login.index');
    }

    public function login(Request $request)
    {
        $user = User::where('user_email', $request->user_email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Password is incorrect'
            ], 401);
        }

        $token = $user->createToken('token-name')->plainTextToken;

        return response()->json([
            'message' => 'success',
            'user' => $user,
            'token' => $token,
        ], 200);
    }
}
