<?php


namespace App\Helpers;


use App\Models\ErrorLogModel;

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

    public static function firebase_img_url($imgPath)
    {
        $storage = app('firebase.storage');
        $defaultBucket = $storage->getBucket();

        $expiryTimestamp = strtotime('+1 week');
        $signedUrl = $defaultBucket->object($imgPath)->signedUrl($expiryTimestamp);
        return $signedUrl;
    }

    public static function save_img_firebase($file,$file_name)
    {
        ini_set('max_execution_time', 0);

        $storage = app('firebase.storage');
        $defaultBucket = $storage->getBucket();

        $imagePathInBucket = 'Logo/' .strtolower($file_name).'_'. uniqid() . '.' . $file->getClientOriginalExtension();

        $defaultBucket->upload(
            $file->get(),
            [
                'name' => $imagePathInBucket
            ]
        );

        return $imagePathInBucket;
    }
}
