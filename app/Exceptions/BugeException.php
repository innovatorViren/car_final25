<?php
namespace App\Exceptions;
use Mail;
/**
* Description of BugeException
*
*/
class BugeException
{
   public $env = '';
   public $config = array();
   public function __construct($config = array()) {
       $this->config = $config;
   }
   public function setEnvironment($env) {
       $this->env = $env;
   }
   public function notifyException($exception) {
       if (!empty($this->env)) {
           $request = array();
           $request['fullUrl'] = request()->fullUrl();
           $request['input_get'] = $_GET;
           $request['input_post'] = $_POST;
           $request['input_old'] = array();
           $request['session'] = array();
           $request['cookie'] =  array();
           $request['file'] =  array();
           $request['header'] = request()->header();
           $request['server'] = request()->server();
           $request['json'] = request()->json();
           $request['request_format'] = request()->format();
           $request['error'] = $exception->getTraceAsString();
           $request['subject_line'] = $exception->getMessage();
           $request['class_name'] = get_class($exception);
           if (!in_array($request['class_name'], $this->config['prevent_exception'])) {
               Mail::send("{$this->config['email_template']}", $request, function($message) use ($request) {
                   $message->to($this->config['notify_emails'])->subject("{$this->config['project_name']} On Url " . $request['fullUrl']);
               });
           }
       }
       return $exception;
   }
}