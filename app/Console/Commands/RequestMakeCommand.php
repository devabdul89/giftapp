<?php
namespace App\Console\Commands;

use Illuminate\Foundation\Console\RequestMakeCommand as ParentRequestMakeCommand;

class RequestMakeCommand extends ParentRequestMakeCommand
{
    protected function getStub()
    {
        return app_path('Stubs/request.stub');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Requests';
    }
}
