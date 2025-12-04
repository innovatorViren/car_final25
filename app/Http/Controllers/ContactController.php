<?php

namespace App\Http\Controllers;

use App\Models\MailTemplate;
use App\Models\SmtpConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\{ContactUs};
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function __construct(){
        parent::__construct();
    }
    public function index(Request $request){
        $data=$request->all();
        return view('contactus.index');
    }
    public function store(Request $request)
    {
        try{
            $requestData = Validator::make($request->all(), [
                'mobile' => 'required',
                'email' => 'required',
            ]);


            $data = [
                'customer_id' => $request->customer_id ?? null,
                'mobile' => $request->mobile ?? null,
                'email' => $request->email ?? null,
                'description' => $request->description ?? null,
            ];
            
            $employee = ContactUs::create($data);

            return back()->with('success', 'Thanks for contacting us!');
        } catch (Exception $e) {
            return back()->with('danger', 'Something want wrong!');
        }
        
    }
    public function sendMailContact(Request $request){
                
        $data=array(
            'name'=>$request->name,
            'email'=>$request->email,
            'contact'=>$request->contact,
            'message'=>$request->message
        );
        
        $sendMail=MailTemplate::with('smtpDetail')->where('module_name', 'contact_us')->latest()->first();//SmtpConfiguration::where('is_active','=','Yes')->first();        
        try {
            if($sendMail){
                $from=$sendMail->smtpDetail->from_name;
                $host=$sendMail->smtpDetail->host_name;
                $username=$sendMail->smtpDetail->username;
                $port=$sendMail->smtpDetail->port;
                $password=$sendMail->smtpDetail->password;
                $driver=$sendMail->smtpDetail->driver;
                $encryption=$sendMail->smtpDetail->encryption;
    
                $sentData="<br/><b>Person Name: </b>".$data['name'].'<br/><b>Email: </b>'.$data['email'].'<br/><b>Contact No. </b>'.$data['contact'].'<br/><b>Message:</b><br/>'.$data['message'];
                //dd($sentData);
                $transport = (new \Swift_SmtpTransport($host, $port))
                            ->setUsername($username)
                            ->setPassword($password)
                            ->setEncryption($encryption);
    
                $mailer    = new \Swift_Mailer($transport);
                $messageSend   = (new \Swift_Message('Contact Message'))
                                ->setFrom($username)
                                ->setTo('hello@gopalprintpack.com')
                                ->setBody($sentData, 'text/html');
                
                $mailer->send($messageSend);
                return back()->with('success', 'Thanks for contacting us!');
            }else{
                return back()->with('danger', 'Something want wrong!');
            }
        } catch (\Throwable $th) {
            return abort(404);
        }
        
        
    }
}