<?php

namespace Utyemma\LaraSettings\Commands;

use Illuminate\Console\GeneratorCommand;

class CreateSetting extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:setting {name : The name of the mailable class}';

    protected $description = 'Create a new Setting Class';

    protected $type = 'Settings';

    protected function getStub(){
        return __DIR__.'../../resources/stubs/settings.stub';
    }

    protected function getDefaultNamespace($rootNamespace) {
        return $rootNamespace.'\Settings';
    }

}
