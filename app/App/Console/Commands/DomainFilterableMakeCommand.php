<?php

namespace Smartville\App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class DomainFilterableMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:filterable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model filter';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Model Filter';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/filterable.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Domain';
    }

    /**
     * Replace the namespace for the given stub.
     *
     * @param  string $stub
     * @param  string $name
     * @return $this
     */
    protected function replaceNamespace(&$stub, $name)
    {
        $stub = str_replace(
            ['DummyNamespace', 'DummyFilterNamespace', 'NamespacedDummyUserModel'],
            [$this->getNamespace($name), $this->rootNamespace() . 'App', config('auth.providers.users.model')],
            $stub
        );

        return $this;
    }
}
