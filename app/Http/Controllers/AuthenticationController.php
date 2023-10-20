<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class AuthenticationController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => 'required|email',
            'username' => 'required|min:4',
            'password' => 'required|min:6',
            'confirmPassword' => 'required|same:password'
        ]);

        if (User::where('email', $data['email'])->count() === 1) {
            throw new HttpResponseException(response([
                "errors" => [
                    "email" => [
                        "email already registered"
                    ]
                ]
            ], 400));
        }

        if (User::where(Str::lower('username'), Str::lower($data['username']))->count() === 1) {
            throw new HttpResponseException(response([
                "errors" => [
                    "username" => [
                        "username already registered"
                    ]
                ]
            ], 400));
        }

        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        return (new UserResource($user))->response()->setStatusCode(201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['email or password incorrect.'],
            ]);
        }

        $oldToken = PersonalAccessToken::where('tokenable_id', $user->id);
        if ($oldToken) {
            $oldToken->delete();
        }

        $token = $user->createToken('user_token', ['*'])->plainTextToken;

        $user->tokens->each(function ($token) {
            $token->forceFill([
                'expires_at' => now()->addHours(1),
            ])->save();
        });

        return response()->json(['data' => [
            "message" => "Login successfully",
            "user" => [
                "id" => $user->id,
                "username" => $user->username,
                "email" => $user->email
            ],
            "token" => $token
        ]], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            "data" => [
                "message" => "Logout success"
            ]
        ], 200);
    }
}
