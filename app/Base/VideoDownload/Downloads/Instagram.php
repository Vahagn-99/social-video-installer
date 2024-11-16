<?php

declare(strict_types=1);

namespace App\Base\VideoDownload\Downloads;

use App\Base\VideoDownload\Dto\DownloadUrl;
use App\Base\VideoDownload\Dto\SettingsDto;
use App\Contracts\Downloader as DownloaderContract;
use App\Exceptions\InvalidUrl as InvalidUrlException;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Instagram implements DownloaderContract
{
    protected SettingsDto $settings;

    public function __construct()
    {
        $this->settings = SettingsDto::from(config('app_downloads.instagram'));
    }

    /**
     * Скачать видео из источника.
     *
     * @param string $url
     * @return \App\Base\VideoDownload\Dto\DownloadUrl
     *
     * @throws \Exception
     */
    public function generateDownloadableUrl(string $url): DownloadUrl
    {
        $output = $this->extractDownloadUrlUsingScript($url);

        if ($output) {

            $download_path = trim($output);

            return new DownloadUrl($download_path);
        }

        throw new InvalidUrlException("Не удалось получить ссылку");
    }

    /**
     * Проверка удовлетворяет ли контекст вызова условию.
     *
     * @param string $url
     * @return bool
     */
    public function isSatisfy(string $url): bool
    {
        return (bool)preg_match('/instagram\.com/', $url);
    }

    //****************************************************************
    //************************** Support *****************************
    //****************************************************************

    /**
     * Извлекает ссылку на видео из HTML-кода страницы поста в Instagram.
     *
     * @param string $url Ссылка на пост в Instagram.
     * @return string Извлечённая ссылка на видео.
     *
     * @throws \Exception Если ссылка на видео не найдена.
     */
    private function extractDownloadUrlUsingScript(string $url): string
    {
        $script_path = base_path("scripts/python/download_instagram_video.py");

        // Получаем путь для сохранения
        $path_to_save = Storage::disk($this->settings->storage)->path($this->settings->path.'/delete_in_'.now()->addDays(2)->format("Y-m-d"));

        // Формируем имя файла для скачивания
        $file_name = Str::random();

        // Формируем команду для выполнения
        $command = "python3 {$script_path} "
            . escapeshellarg($url)           // URL для видео
            . " " . escapeshellarg($path_to_save)  // Путь для сохранения
            . " " . escapeshellarg($this->settings->extension)     // Расширение для файла
            . " " . escapeshellarg($file_name); // Имя файла для скачивания

        // Выполнение команды и получение вывода
        exec($command, $output, $return_var);

        // Проверяем успешность выполнения команды
        if ($return_var !== 0) {
            throw new Exception("Ошибка при выполнении скрипта. Код возврата: {$return_var}");
        }

        // Проверяем, есть ли вывод, и что это за вывод
        if (empty($output)) {
            throw new InvalidUrlException("Не удалось извлечь ссылку для скачивания из Instagram.");
        }

        // Возвращаем элемент в выводе (URL)
        return sprintf("%s/%s.%s", $path_to_save, $file_name, $this->settings->extension);
    }
}