<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Base\VideoDownload\Dto\DownloadUrl;

interface Downloader
{
    /**
     * Скачать видео из источника.
     *
     * @param string $url
     * @return \App\Base\VideoDownload\Dto\DownloadUrl
     *
     * @throws \App\Exceptions\InvalidUrl
     */
    public function generateDownloadableUrl(string $url): DownloadUrl;

    /**
     * Проверка удовлетворяет ли контекст вызова условию.
     *
     * @param string $url
     * @return bool
     */
    public function isSatisfy(string $url): bool;
}
