<?php

namespace CodexShaper\DBM\Http\Controllers;

use CodexShaper\DBM\Facades\Manager as DBM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    /**
     * Show login form
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function showLoginForm()
    {
        return view('dbm::admin');
    }
    /**
     * Login User for API
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        if ($request->ajax()) {
            try {
                $credentials = [
                    'email'    => $request->data['email'],
                    'password' => $request->data['password'],
                ];

                if (Auth::attempt($credentials)) {
                    $user   = Auth::user();
                    $expiry = Config::get('dbm.auth.token.expiry');
                    if (count($user->tokens) > 0) {
                        $user->tokens()->delete();
                    }
                    return response()->json([
                        'success' => true,
                        'user'    => $user,
                        'token'   => $user->createToken('DBM')->accessToken,
                        'expiry'  => $expiry,
                    ]);

                }
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'errors'  => [$e->getMessage()],
                ], 400);
            }
        }
        return response()->json(["success" => false, "error" => "Unauthorised"], 401);

    }
}
