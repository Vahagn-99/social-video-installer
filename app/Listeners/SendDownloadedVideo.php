<?php

namespace App\Listeners;

use App\Base\VideoDownload\Manager as VideoDownloadManager;
use App\Events\NewUrlReceived;
use App\Exceptions\InvalidUrl;
use DefStudio\Telegraph\Facades\Telegraph;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendDownloadedVideo implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct(private readonly VideoDownloadManager $video_download_manager)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewUrlReceived $event): void
    {
        $sender = Telegraph::chat($event->telegram_chat);

        try {
            $download = $this->video_download_manager->getDownloadableUri($event->url);

            $sender->message("Готово! лови")->video($download->path)->send();

        } catch (InvalidUrl $e) {
            $sender->message($e->getMessage())->send();
        }
    }
}
