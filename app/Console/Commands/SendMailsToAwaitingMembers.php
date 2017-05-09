<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use LaraModels\AwaitingMember;

class SendMailsToAwaitingMembers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:awaiting_members';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
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
        $members = AwaitingMember::where('email_sent','0')->get();
        foreach ($members->all() as $member){
            Mail::send('invite_awaiting_member_mail', ['data'=>$member->event], function($m)use($member) {
                $m->from(env('MAIL_USERNAME'), 'Group Gift');
                $m->to($member->email)->subject('Invited On GroupGift');
            });
        }
        (new AwaitingMember())->update(['email_sent'=>1]);
    }
}
