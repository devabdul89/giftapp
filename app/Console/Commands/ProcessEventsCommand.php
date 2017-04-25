<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use LaraModels\Event;
use Libs\EventProcessor;
use Repositories\EventsRepository;

class ProcessEventsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'process events';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $eventProcessor = new EventProcessor();
        foreach ($this->fetchUnProcessedEvents() as $event){
            $eventProcessor->setEvent($event)->process();
        }

        (new EventsRepository())->expireOutDatedEvents();
    }

    public function fetchUnProcessedEvents(){
        return (new EventsRepository())->getReadyEvents();
    }
}
