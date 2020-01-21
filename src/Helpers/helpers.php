<?php

if (! function_exists('dbm_asset')) {
    function dbm_asset($path, $secure = null)
    {
        return route('dbm.asset').'?path='.urlencode($path);
    }
}

if (! function_exists('dbm_driver')) {
    function dbm_driver()
    {
        return (config('database.default') != '') ? config('database.default') : 'mysql';
    }
}

if (! function_exists('dbm_prefix')) {
    function dbm_prefix()
    {
        return (config('dbm.prefix') != '') ? config('dbm.prefix') : '';
    }
}

if (! function_exists('is_json')) {
    function is_json($string)
    {
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }
}

if (! function_exists('save_json')) {
    function save_json(array $arr)
    {
        $jsonArr = [];
        $i = 1;
        foreach ($arr as $key => $value) {
            $jsonArr['key_'.$i++] = $value;
        }

        return json_encode($jsonArr);
    }
}

if (! function_exists('retreive_json')) {
    function retreive_json(string $str)
    {
        $jsonData = json_decode($str, true);
        $jsonResults = [];

        foreach ($jsonData as $data) {
            $jsonResults[] = $data;
        }

        return $jsonResults;
    }
}
