<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Redirect;
use Symfony\Component\HttpFoundation\Request;

class LogController extends Controller
{

    public function index(Request $request)
    {
        $file = fopen(storage_path('logs/laravel.log'), 'r');
        $data = '';
        $n = $request->get('lines') ? $request->get('lines') : 100;
        while ($n-- and $line = fgets($file)) {
            $data .= $line;
        }
        return view('log')->with(['data' => $data]);
    }

    public function truncate()
    {
        $file = fopen(storage_path('logs/laravel.log'), 'w');
        if (ftruncate($file, 0)) {
            return Redirect::action('LogController@index');
        } else {
            throw new \ErrorException('unable to truncate log');
        }
    }

}
