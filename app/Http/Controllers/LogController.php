<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Redirect;
use Symfony\Component\HttpFoundation\Request;

class LogController extends Controller
{

    public function index()
    {
        $logsDir = storage_path('logs');
        $files = scandir($logsDir);
        return view('logs')->with(
            [
                'files' => array_filter($files, function ($file) {
                    return !preg_match('/^\./', $file);
                }),
            ]
        );
    }

    public function file(Request $request)
    {
        $logsDir = storage_path('logs');
        $filename = $logsDir . '/' . $request->get('file');
        $file = fopen($filename, 'r');
        $data = '';
        $n = $request->get('lines') ? $request->get('lines') : 100;
        while ($n-- and $line = fgets($file)) {
            $data .= $line;
        }
        return view('log')->with(['data' => $data, 'file' => $request->get('file')]);
    }


    public function truncate(Request $request)
    {
        $logsDir = storage_path('logs');
        $filename = $logsDir . '/' . $request->get('file');
        $file = fopen($filename, 'w');
        if (ftruncate($file, 0)) {
            return Redirect::action('LogController@index');
        } else {
            throw new \ErrorException('unable to truncate log');
        }
    }

}
