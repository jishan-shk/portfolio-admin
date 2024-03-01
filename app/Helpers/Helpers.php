<?php


namespace App\Helpers;


class Helpers
{
    public static function save_error_log($message, $line = Null, $file = Null, $dont_exit = false)
    {
        ErrorLogModel::create([
            "message" => $message,
            "line" => $line,
            "file" => $file,
        ]);
    }
}
