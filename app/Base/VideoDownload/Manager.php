<?php

declare(strict_types=1);

namespace App\Base\VideoDownload;

use App\Base\VideoDownload\Dto\DownloadUrl;
use App\Exceptions\InvalidUrl as InvalidUrlException;
use Illuminate\Support\Arr;

class Manager
{
    /**
     * Скачать видео из источника.
     *
     * @param string $url
     * @return \App\Base\VideoDownload\Dto\DownloadUrl
     *
     * @throws \App\Exceptions\InvalidUrl
     */
    public function getDownloadableUri(string $url): DownloadUrl
    {
        foreach (Arr::pluck(config('app_downloads', []), 'manager') as $name) {
            /** @var \App\Contracts\Downloader $manager */
            $manager = app($name);

            if ($manager->isSatisfy($url)) {
                return $manager->generateDownloadableUrl($url);
            }
        }

        throw new InvalidUrlException("Передананя ссылка невалидна.");
    }
}
