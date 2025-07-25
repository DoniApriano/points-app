<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $user = User::where('email', $validatedData['email'])->first();

            if ($user) {
                if (!Hash::check($validatedData['password'], $user->password)) {
                    return response()->json([
                        'message' => 'Akun tidak cocok',
                    ], 500);
                }
            } else {
                return response()->json([
                'message' => 'Akun tidak cocok',
                ], 500);
            }

            return response()->json([
                'token' =>  $user->createToken('user login')->plainTextToken
            ]);
        } catch (\Throwable $th) {
            info($th);

            return response()->json([
                'message' => 'Kesalahan dalam server',
            ], 500);
        }
    }
}
