<?php
namespace mahalaxmi\Services;

use Mail;

/**
 * This class is onzup service for sending emails.
 */
class Email
{
    public function send($view, $data, $to, $from, $subject, $files)
    {
        if (env('MAIL_PRETEND', true)) {
            return true;
        }
        Mail::queue($view, $data, function ($message) use ($to, $from, $subject) {
            $message->from($from);
            $message->subject($subject);
            $message->to($to);
        });
    }
}
