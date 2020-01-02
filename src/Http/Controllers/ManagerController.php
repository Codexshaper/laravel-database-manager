<?php

namespace CodexShaper\DBM\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ManagerController extends Controller
{

    /**
     * Load menu builder assests
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function assets(Request $request)
    {
        return \DBM::assets($request->path);
    }
}
