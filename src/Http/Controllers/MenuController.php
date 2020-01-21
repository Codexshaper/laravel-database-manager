<?php

namespace CodexShaper\DBM\Http\Controllers;

use CodexShaper\DBM\Models\MenuItem;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
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
    public function get()
    {
        // if ($request->ajax()) {
        try {
            $menus = MenuItem::with('childrens')
                ->where('menu_id', 1)
                ->whereNull('parent_id')
                ->orderBy('order', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'menus' => $menus,
            ]);
        } catch (\Exception $e) {
            $this->generateError([$e->getMessage()]);
        }
        // }
        // return response()->json(["success" => false, "error" => "Unauthorised"], 401);
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
