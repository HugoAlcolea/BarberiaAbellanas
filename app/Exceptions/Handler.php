<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
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

    // public function render($request, Throwable $exception)
    // {
    //     if ($exception instanceof ValidationException) {
    //         return parent::render($request, $exception);
    //     }
    
    //     $status = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500;
    
    //     if (view()->exists("errors.{$status}")) {
    //         return response()->view("errors.{$status}", ['code' => $status, 'message' => $exception->getMessage()], $status);
    //     }
    
    //     return parent::render($request, $exception);
    // }
}
