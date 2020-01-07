<?php

namespace CodexShaper\DBM\Http\Controllers;

use CodexShaper\DBM\Facades\Manager as DBM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

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
    /**
     * Get access token for API
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPersonalAccessToken(Request $request)
    {
        try {

            $tokenName = $request->name != "" ? $request->name : 'DBM';

            $status   = 401;
            $response = ['error' => 'Unauthorised'];

            if (Auth::attempt($request->only(['email', 'password']))) {

                $status   = 200;
                $response = [
                    'success' => true,
                    'token'   => Auth::user()->createToken($tokenName)->accessToken,
                ];
            }

            return response()->json($response, $status);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors'  => [$e->getMessage()],
            ], 400);
        }
    }

    /*

    public function logout(Request $request)
    {
    if ($request->ajax()) {
    // return response()->json(['success' => false]);
    try {
    $token = $request->user()->token();
    if ($token->revoke()) {
    return response()->json(['success' => true, 'token' => $token]);
    }

    } catch (\Exception $e) {
    return response()->json([
    'success' => false,
    'errors'  => [$e->getMessage()],
    ], 400);
    }

    }

    return response()->json(['success' => false, 'error' => 'Unauthorized']);
    }
     */
    /**
     * Load API component
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function api()
    {
        if (Auth::guest()) {
            return Route::has('login') ? redirect(route('login')) : Response::view('dbm::errors.404', [], 404);
        }

        return view('dbm::api');
    }

}
