<?php

namespace App\Exceptions;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        });
    }

    public function render($request, Throwable $exception) {
        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'status' => 'not found',
                'message' => 'Not found'
            ], 404);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'status' => 'not found',
                'message' => 'Not found'
            ], 404);
        }

        if ($exception instanceof AuthenticationException) {
            $token = PersonalAccessToken::findToken($request->bearerToken());

            if ($token) {
                if ($token->tokenable_id) {
                    $user = User::withTrashed()->find($token->tokenable_id);
                    if ($user && $user->deleted_at && $user->delete_reason) {
                        throw new UserBlockedException($user->delete_reason);
                    }
                }
            }
            if ($request->bearerToken()) {
                    return response()->json([
                        'status' => 'unauthenticated',
                        'message' => 'Invalid token'
                    ], 401);
            }
            // Token does not exist or is invalid
            return response()->json([
                'status' => 'unauthenticated',
                'message' => 'Missing token'
            ], 401);
        }

        if ($exception instanceof UserBlockedException) {
            return response()->json([
                'status' => 'blocked',
                'message' => $exception->getMessage(),
                'reason' => $exception->getReason()
            ], $exception->getCode());
        }

        if ($exception instanceof ValidationException) {
            $violations = [];

            foreach ($exception->validator->errors()->toArray() as $field => $messages) {
                foreach ($messages as $message) {
                    if ($message == "The $field field is required.") {
                        $message = 'required';
                    }
                    $violations[$field] = ['message' => $message];
                }
            }

            return response()->json([
                'status' => 'invalid',
                'messsage' => 'Request body is not valid',
                'violations' => $violations
            ], 422);
        }

        return parent::render($request, $exception);
    }
}
