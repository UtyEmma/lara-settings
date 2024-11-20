<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SeedSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:seed {class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed Default Settings';

    /**
     * Execute the console command.
     */
    public function handle() {
        $class = $this->option('class');
        app("App\Settings\{$class}")->seed();
    }
}
