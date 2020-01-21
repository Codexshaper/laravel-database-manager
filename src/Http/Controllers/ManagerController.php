<?php

namespace CodexShaper\DBM\Http\Controllers;

use CodexShaper\DBM\Facades\Manager as DBM;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    /**
     * Load database manager assests.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function assets(Request $request)
    {
        return DBM::assets($request->path);
    }
}
