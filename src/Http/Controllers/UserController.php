<?php

namespace CodexShaper\DBM\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Show login form.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function showLoginForm()
    {
        return view('dbm::admin');
    }

    /**
     * Login User for API.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        if ($request->ajax()) {
            try {
                if (($response = $this->validation($request->data)) !== true) {
                    return $response;
                }
                $credentials = [
                    'email' => $request->data['email'],
                    'password' => $request->data['password'],
                ];

                if (! Auth::attempt($credentials)) {
                    return $this->generateError(["Email and password combination doesn't match"]);
                }

                $user = Auth::user();
                $expiry = Config::get('dbm.auth.token.expiry');
                if (count($user->tokens) > 0) {
                    $user->tokens()->delete();
                }

                return response()->json([
                    'success' => true,
                    'user' => $user,
                    'token' => $user->createToken('DBM')->accessToken,
                    'expiry' => $expiry,
                ]);
            } catch (\Exception $e) {
                $this->generateError([$e->getMessage()]);
            }
        }

        return response()->json(['success' => false, 'error' => 'Unauthorised'], 401);
    }

    /**
     * Validate Credentials.
     *
     * @param array $data
     *
     * @return \Illuminate\Http\JsonResponse|true
     */
    public function validation($data)
    {
        $validator = Validator::make($data, [
            'email' => 'required|email',
            'password' => 'required',

        ]);

        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->all() as $error) {
                $errors[] = $error;
            }

            return $this->generateError($errors);
        }

        return true;
    }

    /**
     * Generate errors and return response.
     *
     * @param array $errors
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateError($errors)
    {
        return response()->json([
            'success' => false,
            'errors' => $errors,
        ], 400);
    }
}
