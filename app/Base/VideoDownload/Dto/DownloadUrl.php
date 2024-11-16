<?php

declare(strict_types=1);

namespace App\Base\VideoDownload\Dto;

readonly class DownloadUrl
{
    public function __construct(public string $path)
    {
    }
}
