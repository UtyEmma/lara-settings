<?php

namespace Utyemma\LaraSetting\Commands;

use Illuminate\Console\Command;

class SeedSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:seed {--class=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the database with the specified settings record';

    /**
     * Execute the console command.
     */
    public function handle() {
        $class = $this->option('class'); 
        $className = "App\\Settings\\$class";
        
        if(!class_exists($className)) {
            return $this->error("\n Target setting class $className does not exist. \n");
        }
        
        app($className)->seed();
        
        return $this->info("\n $class data seeded successfully. \n");
    }
}
