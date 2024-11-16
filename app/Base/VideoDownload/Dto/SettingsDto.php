<?php

declare(strict_types=1);

namespace App\Base\VideoDownload\Dto;

readonly class SettingsDto
{
    public function __construct(
        public string $manager,
        public string $storage,
        public string $path,
        public string $extension
    )
    {
    }

    public static function from(array $settings): self
    {
        return new self($settings['manager'], $settings['storage'], $settings['path'], $settings['extension']);
    }
}
