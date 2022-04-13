<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\StoreUserRequest;
use App\Http\Requests\Auth\UpdateUserRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function signUp(StoreUserRequest $request)
    {
        if ($request->validated())
            User::create([
                "name" => $request->name,
                "surname" => $request->surname,
                "email" => $request->email,
                "password" => bcrypt($request->password)
            ]);

        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }

    public function login(LoginUserRequest $request)
    {
        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized: Wrong email or password'
            ], 401);

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');

        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString()
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function userUpdate(UpdateUserRequest $request, User $user)
    {
        if ($request->validated())
            $user->update([
                "name" => $request->name,
                "surname" => $request->surname,
                "email" => $request->email,
                "password" => bcrypt($request->password)
            ]);
        return response()->json($user, 202);
    }
}
