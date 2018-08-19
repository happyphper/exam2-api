<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Transformers\UserTransformer;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $this->validate(request(), [
            'username' => 'required',
            'password' => 'required|min:6'
        ]);

        $credentials['password'] = request('password');

        $username = request('username');

        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $username;
        } elseif (preg_match('/1[3-9]\d{9}/', $username,$matches) && count($matches)) {
            $credentials['phone'] = $username;
        } else {
            $credentials['name'] = $username;
        }

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => '账号或密码错误！'], 400);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return
     */
    public function me()
    {
        return $this->response->item(auth()->user(), new UserTransformer());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return $this->response->array(['message' => '成功登出！']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return $this->response->array([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
