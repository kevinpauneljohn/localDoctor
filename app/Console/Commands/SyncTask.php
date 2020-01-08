<?php

namespace App\Console\Commands;

use App\Terminal;
use App\Threshold;
use App\User;
use GuzzleHttp\Client;
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
        $host="outerboxpro.com";

        exec("ping -n 4 " . $host, $output, $result);

//        return $result;
        if($result === 0)
        {
            $thresholds = Threshold::all();
            foreach ($thresholds as $threshold){
                $server = $this->sendToServer(
                    $threshold->causer_id,
                    config('terminal.license'),
                    $threshold->data,
                    $threshold->action,
                    date('Y-m-d h:i:s', strtotime($threshold->created_at)),
                    date('Y-m-d h:i:s', strtotime($threshold->updated_at))
                );

                $success = "0";
                if($server === 1)
                {
                    $thresholdTrash = Threshold::find($threshold->id);
                    $thresholdTrash->delete();
                    $success = "1";
                }
                echo 'Threshold ID: '.$threshold->id.', Status: '.$success;
            }
        }
    }

    public function sendToServer($causer_id, $terminal_id, $data, $action, $created_at, $updated_at)
    {
        //internet connection ok
        //API callback
        $userToken = User::findOrFail($causer_id)->api_token;
        $client = new Client([
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$userToken,
            ],
        ]);

        //$response = $client->request('POST','https://doctorapp.devouterbox.com/api/userClients',[
        $response = $client->request('GET','http://outerboxpro.com/api/threshold',[
            'json' => [
                'causer_id' => $causer_id,
                'terminal_id'   => $terminal_id,
                'data'  => $data,
                'action'    => $action,
                'created_at'    => $created_at,
                'updated_at'    => $updated_at
            ],
        ]);

        return json_decode($response->getBody());
    }
}
