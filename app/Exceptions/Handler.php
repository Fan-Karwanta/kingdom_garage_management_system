<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Mail\SendFailedException;
use Illuminate\Support\Facades\Log;
use Swift_TransportException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    /*public function report(Exception $exception)
    {
        parent::report($exception);
    }*/

    /**
     * Report or log an exception.
     *
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    /*public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }*/

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof AuthorizationException) {
            // Customize the response for 403 error
            return response()->view('errors.403', [], 403);
        }
        if ($exception instanceof QueryException) {
            if (strpos($exception->getMessage(), 'Access denied') !== false) {
                return response()->view('errors.db_credentials', [], 500);
            }
        }
        // Handle SMTP authentication error (530)
        if ($exception instanceof Swift_TransportException) {
            if (strpos($exception->getMessage(), '530-5.7.0 Authentication Required') !== false) {
                // Log the error for debugging
                Log::error('SMTP Authentication Error: '.$exception->getMessage());

                // Redirect to a custom SMTP error page
                return response()->view('errors.smtp_error', [], 500);
            }
        }

        // Handle other mail exceptions
        if ($exception instanceof SendFailedException) {
            // Log the error for debugging
            Log::error('Mail Sending Failed: '.$exception->getMessage());

            // Redirect to a custom error page
            return response()->view('errors.smtp_error', [], 500);
        }

        return parent::render($request, $exception);
    }
}
