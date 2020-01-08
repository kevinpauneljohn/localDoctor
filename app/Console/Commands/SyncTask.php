<?php

namespace App\Console\Commands;

use App\Threshold;
use App\User;
use Illuminate\Console\Command;

class SyncTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'syncTask';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sync all the offline data to server';

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
        $test ="test";
        $terminal = new Threshold();
        $terminal->causer_id = '42a5dc8a-deeb-4569-ac0e-fc8b45db0782';
        $terminal->data = User::find('42a5dc8a-deeb-4569-ac0e-fc8b45db0782');
        $terminal->action = "test only";
        //$terminal->save();
    }
}
