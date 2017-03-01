<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeRepositoryCommand extends Command
{

    protected $files = null;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name} {model?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository';

    /**
     * Create a new command instance.
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $stub = $this->files->get($this->getStub());
        $stub = str_replace('DummyClass', $this->argument('name'), $stub);
        if($this->argument('model') != null){
            $stub = str_replace('ModelNamespace', 'use App\Models\\'.$this->argument('model').';', $stub);
        }else{
            $stub = str_replace('ModelNamespace', '', $stub);
        }
        if($this->argument('model') != null){
            $stub = str_replace('dummyModelObject', 'new '.$this->argument('model').'()', $stub);
        }else{
            $stub = str_replace('dummyModelObject', 'null', $stub);
        }
        $stub = str_replace('dummyModelObject', lcfirst($this->argument('model')), $stub);
        $this->files->put(app_path('Repositories/'.$this->argument('name').'.php'), $stub);
        echo "Repository Created successfully";
    }

    public function getStub()
    {
        return app_path('Stubs/repository.stub');
    }
}
