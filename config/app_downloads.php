<?php

use App\Base\VideoDownload\Downloads\Instagram;

return [

    /*
    |--------------------------------------------------------------------------
    | Настройки загрузчиков видео.
    |--------------------------------------------------------------------------
    |
    */

    'instagram' => [
        'manager' => Instagram::class,
        'storage' => "downloads",
        'path' => "instagram",
        'extension' => "mp4"
    ],
];
