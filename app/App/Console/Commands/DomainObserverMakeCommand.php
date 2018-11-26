<?php

namespace Smartville\App\Console\Commands;

use Illuminate\Foundation\Console\ObserverMakeCommand;
use Symfony\Component\Console\Input\InputOption;

class DomainObserverMakeCommand extends ObserverMakeCommand
{
    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        if ($this->option('global')) {
            return $rootNamespace . '\App\Observers';
        }
        return $rootNamespace . '\Domain';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'The model that the observer applies to.'],
            ['global', 'g', InputOption::VALUE_NONE, 'Make the model observer global.'],
        ];
    }
}
