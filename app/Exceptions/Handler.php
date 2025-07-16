<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function report(Throwable $exception)
   {
        $bugnotified = new BugeException(config('bugonemail'));
        if (in_array(app()->environment(), $bugnotified->config['notify_environment'])) {
            $bugnotified->setEnvironment(app()->environment());
            $bugnotified->notifyException($exception);
        }
        parent::register($exception);
    }
}
