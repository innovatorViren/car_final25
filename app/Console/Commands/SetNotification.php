<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\CommonController;
use App\Models\{Setting};
use DB;
use Carbon\Carbon;
use Mail;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Firebase;

class SetNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:SetNotification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command Set Notification for employee based on time.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->common = new CommonController();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $date = Carbon::now()->format('Y-m-d');

        $washData = DB::table('washes')->where('scheduled_date',$date)->whereNotNull('employee_id')->where('status','pending')->whereNull('deleted_at')->get();
        if(isset($washData)){
            foreach($washData as $item)
            {
                $formattedTime = date('h:i A', strtotime($item->start_time));
                $orderData = DB::table('orders')->where('id',$item->order_id)->first();
                $orderCode = $orderData->code;
                $customerName = DB::table('customers')->where('id',$orderData->customer_id)->first()->first_name;
                $dataArray = [
                    'come_from'=>'emp-order',
                    'order_id'=>(string)$orderData->id,
                    'order_name'=>$orderData->code
                ];
                $userData = DB::table('users')->where('emp_id',$item->employee_id)->first();
                $body = "Today's Order\nAt {$formattedTime} - {$customerName}'s car service is scheduled.";

                $user_token = $this->sendFcmNotificationApplication(
                                $userData->id,
                                'New Order Scheduled',
                                $body,
                                $dataArray
                            );

            }
        }else{
        }


        info('SetNotification Command Run successfully!');

        return Command::SUCCESS;
    }

    public function sendFcmNotificationApplication($user,$title,$body,$dataArray)
    {

        $user = \App\Models\User::find($user);
        $fcm = $user->fcm_token;

        if (!$fcm) {
            return response()->json(['message' => 'User does not have a device token'], 400);
        }

        $title = $title;
        $description = $body;
        $projectId = 'clearmycar-e1d9d'; 

        $credentialsFilePath = Storage::path('json/clearmycar-e1d9d-firebase-adminsdk-fbsvc-f02e0b791b.json');
        $client = new GoogleClient();
        $client->setAuthConfig($credentialsFilePath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->refreshTokenWithAssertion();
        $token = $client->getAccessToken();

        $access_token = $token['access_token'];

        $headers = [
            "Authorization: Bearer $access_token",
            'Content-Type: application/json'
        ];

        $data = [
            "message" => [
                "token" => $fcm,
                "notification" => [
                    "title" => $title,
                    "body" => $description,
                    // "test" => 'sddsds',
                ],
                "data" =>$dataArray,
            ]
        ];

        $payload = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_VERBOSE, true); // Enable verbose output for debugging
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return response()->json([
                'message' => 'Curl Error: ' . $err
            ], 500);
        } else {
            return response()->json([
                'message' => 'Notification has been sent',
                'response' => json_decode($response, true)
            ]);
        }
    }
    
}
