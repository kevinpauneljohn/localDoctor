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
        /*this will check if the connection is good*/
        $host="doctorapp.devouterbox.com";

        exec("ping -n 2 " . $host, $output, $result);

//        echo $output;
//        return $result;
        /*will return 0 if there is response from the server*/
        if($output[0] === "")
        {
            /*this will retrieve all rows from thresholds table*/
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

//                $success = "0";
//                /*will return 1 if the transfer was success*/
//                if($server === 1)
//                {
//                    /*will delete the rows if the data was transferred successfully*/
//                    $thresholdTrash = Threshold::find($threshold->id);
//                    $thresholdTrash->delete();
//                    $success = "1";
//                }
//                echo 'Threshold ID: '.$threshold->id.', Status: '.$success;
            }
        }
    }

    /**
     * Jan. 08, 2020
     * @author john kevin paunel
     * api endpoint for transfering rows from threshold table origin local to server destination
     * @param  string $causer_id
     * @param string $terminal_id
     * @param string $data
     * @param string $action
     * @param string $created_at
     * @param string $updated_at
     * @return mixed
     * */
    public function sendToServer($causer_id, $terminal_id, $data, $action, $created_at, $updated_at)
    {
        //internet connection ok
        //API callback
        $userToken = User::findOrFail($causer_id)->api_token;
        $client = new Client([
            'headers' => [
                'content-type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$userToken,
            ],
        ]);

        $response = $client->request('POST','https://doctorapp.devouterbox.com/api/threshold',[
            'json' => [
                'causer_id'     => $causer_id,
                'terminal_id'   => $terminal_id,
                'data'          => $data,
                'action'        => $action,
                'created_at'    => $created_at,
                'updated_at'    => $updated_at
            ],
        ]);

        return json_decode($response->getBody());
    }
}
