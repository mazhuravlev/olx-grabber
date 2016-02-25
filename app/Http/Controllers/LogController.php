<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Redirect;
use Symfony\Component\HttpFoundation\Request;

class LogController extends Controller
{

    public function index()
    {
        return view('logs')->with(
            [
                'files' => self::getDirFileList(storage_path('logs')),
            ]
        );
    }

    public function file(Request $request)
    {
        $filename = storage_path('logs') . '/' . $request->get('file');
        $file = fopen($filename, 'r');
        $data = '';
        $n = $request->get('lines') ? $request->get('lines') : 100;
        while ($n-- and $line = fgets($file)) {
            $data .= $line;
        }
        return view('log')->with(
            [
                'data' => $data,
                'file' => $request->get('file'),
                'files' => self::getDirFileList(storage_path('logs')),
            ]
        );
    }

    private static function getDirFileList($dir)
    {
        $files = array_filter(scandir($dir), function ($file) {
            return !preg_match('/^\./', $file);
        });
        $result = [];
        foreach ($files as $file) {
            array_push(
                $result,
                [
                    'file' => $file,
                    'size' => filesize($dir . DIRECTORY_SEPARATOR . $file),
                ]
            );
        }
        return $result;
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
