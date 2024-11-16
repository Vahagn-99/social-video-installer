import sys
import yt_dlp
import os

def download_instagram_video(url, save_path, file_extension, file_name):
    # Настройки для скачивания видео
    ydl_opts = {
        'outtmpl': os.path.join(save_path, file_name + '.%(ext)s'),  # Путь для сохранения
        'quiet': False,  # Выводить ли лог
        'format': 'bestvideo+bestaudio/best',  # Лучшее качество видео и аудио
    }

    try:
        # Используем yt-dlp для получения информации о видео
        with yt_dlp.YoutubeDL(ydl_opts) as ydl:
            info_dict = ydl.extract_info(url, download=False)

            # Получаем расширение файла из информации о видео
            video_extension = info_dict.get('ext', 'mp4')  # По умолчанию mp4
            print(f"Расширение видео: {video_extension}")

            # Обновляем путь с полученным расширением
            ydl_opts['outtmpl'] = os.path.join(save_path, file_name + '.' + video_extension)

            # Скачиваем видео
            ydl_opts['quiet'] = True  # Отключаем вывод логов
            with yt_dlp.YoutubeDL(ydl_opts) as ydl:
                ydl.download([url])

            print(f"Видео успешно скачано в {os.path.join(save_path, file_name + '.' + video_extension)}")
            print(os.path.join(save_path, file_name + '.' + video_extension))  # Выводим путь к файлу

    except Exception as e:
        print(f"Ошибка при скачивании видео: {e}")
        sys.exit(1)

if __name__ == "__main__":
    # Чтение аргументов из командной строки
    url = sys.argv[1]
    save_path = sys.argv[2]
    file_extension = sys.argv[3]
    file_name = sys.argv[4]

    download_instagram_video(url, save_path, file_extension, file_name)
