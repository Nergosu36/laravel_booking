<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * User logs in.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function unauthorized(): JsonResponse
    {
        return response()->json([
            'message' => __('apiMessages.auth.unauthorized'),
        ], 403);
    }

    /**
     * User logs in.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        $password = $credentials['password'];
        $email = $credentials['email'];

        if (!$user = User::where('email', $email)->get()->first()) {
            return response()->json([
                'message' => __('apiMessages.auth.invalid_email'),
            ], 401);
        }
        if (Hash::check($password, $user->password)) {
            $token = Str::random(80);
 
            $user->api_token = hash('sha256', $token);
            $user->save();
     
            return response()->json([
                'message' => __('apiMessages.auth.logged_in'),
                'token' => $token,
            ], 200);
        }

        return response()->json([
            'message' => __('apiMessages.auth.wrong_credentials'),
        ], 401);
    }

    /**
     * User creates an account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        $password = $credentials['password'];
        $email = $credentials['email'];

        if (!User::where('email', $email)->get()->isEmpty()) {
            return response()->json([
                'message' => __('apiMessages.auth.user_already_exists'),
            ], 200);
        }

        $user = new User(['email' => $email, 'password' => Hash::make($password), 'name' => $credentials['name']]);
        $user->save();

        return response()->json([
            'message' => __('apiMessages.auth.account_created'),
            'user' => $user,
        ], 200);
    }
}
