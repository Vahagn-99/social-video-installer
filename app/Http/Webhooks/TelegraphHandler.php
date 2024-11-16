<?php

declare(strict_types=1);

namespace App\Http\Webhooks;

use App\Events\NewUrlReceived;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Stringable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class TelegraphHandler extends WebhookHandler
{
    public function start(): void
    {
        $this->reply("Привет! Отправь ссылку на видео");
    }

    protected function handleChatMessage(Stringable $text): void
    {
        $validator = Validator::make(['text' => $text], [
            'text' => 'required|starts_with:https',
        ]);

        if ($validator->fails()) {
            $message = "**Ошибка!**\n\n"
                ."Не получилось! Пожалуйста, отправьте ссылку в формате:\n"
                ."[https://my/viedo/url/to/download](https://my/viedo/url/to/download)";

            $this->chat->markdown($message)->send();

            return;
        }

        $this->chat->message("Обработка ...")->send();

        NewUrlReceived::dispatch($this->chat, $text->toString());
    }

    /**
     * @throws \Throwable
     */
    protected function onFailure(Throwable $throwable): void
    {
        if ($throwable instanceof NotFoundHttpException) {
            throw $throwable;
        }

        report($throwable);

        $this->reply('Повторите попытку.');
    }
}
