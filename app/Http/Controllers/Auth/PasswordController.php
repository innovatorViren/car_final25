<?php

namespace App\Http\Controllers\Auth;

use DB;
use Mail;
use Session;
use Reminder;
use Sentinel;
use URL;
use App\Http\Requests;
use Centaur\AuthManager;
use Illuminate\Http\Request;
use Centaur\Mail\CentaurPasswordReset;
use App\Http\Controllers\Controller;
use App\Models\{SmtpConfiguration};
use Illuminate\Support\HtmlString;

class PasswordController extends Controller
{
    /** @var Centaur\AuthManager */
    protected $authManager;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(AuthManager $authManager)
    {
        $this->middleware('sentinel.guest');
        $this->authManager = $authManager;
    }

    /**
     * Show the password reset request form
     * @return View
     */
    public function getRequest()
    {
        return view('Centaur::auth.reset');
    }

    /**
     * Send a password reset link
     * @return Response|Redirect
     */
    public function postRequest(Request $request)
    {
        // Validate the form data
        $result = $this->validate($request, [
            'email' => 'required|email|max:255'
        ]);
        // Fetch the user in question
        $user = Sentinel::findUserByCredentials(['email' => $request->get('email')]);

        // Only send them an email if they have a valid, inactive account
        if ($user) {
            // Generate a new code
            $reminder = Reminder::create($user);

            // Send the email
            $code = $reminder->code;
            $email = $user->email;
            $this->sendPasswordMail($email, $code);
            // Mail::to($email)->queue(new CentaurPasswordReset($code));
        }

        $message = 'Instructions for changing your password will be sent to your email address if it is associated with a valid account.';
        if ($request->expectsJson()) {
            return response()->json(['message' => $message, 'code' => $code], 200);
        }

        Session::flash('success', $message);
        // return redirect()->route('dashboard');
        return redirect()->route('auth.login.form');
    }
    public function sendPasswordMail($customer_email, $code){

        $smtp_details = get_smtp_details('forgot_password');
        if(!empty($smtp_details)){
            
            $html = str_replace('[URL]', route('auth.password.reset.form', urlencode($code)), $smtp_details->message_body);
            $smtp_details->message_body = $html;

            $transport = (new \Swift_SmtpTransport($smtp_details->host_name, $smtp_details->port))
                        ->setUsername($smtp_details->username)
                        ->setPassword($smtp_details->password)
                        ->setEncryption($smtp_details->encryption);

            $mailer    = new \Swift_Mailer($transport);
            $message   = (new \Swift_Message($smtp_details->subject))
                ->setFrom($smtp_details->username, '')
                ->setTo($customer_email)
                ->setBody($smtp_details->message_body, 'text/html');
            $attachment = ($smtp_details->attachment !='') ? public_path($smtp_details->attachment) : '';

            if($attachment !='' && file_exists($attachment)){
                $message->attach(\Swift_Attachment::fromPath(URL::to($smtp_details->attachment)));
            }            
            $mailer->send($message);
        }
    }


    /**
     * Show the password reset form if the reset code is valid
     * @param  Request $request
     * @param  string  $code
     * @return View
     */
    public function getReset(Request $request, $code)
    {
        // Is this a valid code?
        if (!$this->validatePasswordResetCode($code)) {
            // This route will not be accessed via ajax;
            // no need for a json response
            Session::flash('error', 'Invalid or expired password reset code; please request a new link.');
            return redirect()->route('dashboard');
        }

        return view('Centaur::auth.password')
            ->with('code', $code);
    }

    /**
     * Process a password reset form submission
     * @param  Request $request
     * @param  string  $code
     * @return Response|Redirect
     */
    public function postReset(Request $request, $code)
    {
        // Validate the form data
        $result = $this->validate($request, [
            'password' => 'required|confirmed|min:6',
        ]);
        // Attempt the password reset
        $result = $this->authManager->resetPassword($code, $request->get('password'));

        if ($result->isFailure()) {
            return $result->dispatch();
        }
        Session::flash('success', 'Password has been reset successfully.');

        // Return the appropriate response
        return $result->dispatch(route('auth.login.form'));
    }

    /**
     * @param  string $code
     * @return boolean
     */
    protected function validatePasswordResetCode($code)
    {
        return DB::table('reminders')
                ->where('code', $code)
                ->where('completed', false)->count() > 0;
    }
}
