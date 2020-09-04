<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ApiTokenController extends Controller
{
    /**
     * ApiTokenController constructor.
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->attemptLogin($request)) {
            $user = $this->guard()->user();
            $user->generateToken();

            return response()->json([
                'data' => $user->toArray(),
            ]);
        }

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Update the authenticated user's API token.
     *
     * @param Request $request
     * @return array
     */
    public function update(Request $request)
    {
        $token = Str::random(60);

        $request->user()->forceFill([
            'api_token' => hash('sha256', $token),
        ])->save();

        return ['token' => $token];
    }
}