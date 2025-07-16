<?php
namespace mahalaxmi\Services;

/**
 * This class is onzup service for sending SMS.
 */
class Sms
{
    public function send($view, $data, $to)
    {
        if (env('SMS_PRETEND', true)) {
            return true;
        }

        var_dump($view, $data, $to);

        //Send sms via Api
    }
}
