<?php

namespace App\Console\Commands;

use App\Base\VideoDownload\Manager;
use Illuminate\Console\Command;

class TestingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(Manager $manager)
    {
         $manager->getDownloadableUri("https://www.instagram.com/reel/DCWQTfsum08/?utm_source=ig_web_copy_link");

    }
}
