<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
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

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $e)
    {
        // Sempre retornar JSON para requisições de API
        if ($request->is('api/*') || $request->expectsJson() || $request->wantsJson()) {
            return $this->handleApiException($request, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * Handle API exceptions and return JSON responses.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Illuminate\Http\JsonResponse
     */
    private function handleApiException(Request $request, Throwable $e): JsonResponse
    {
        // Definir código HTTP padrão
        $status = 500;
        $response = [
            'success' => false,
            'message' => 'Erro interno do servidor.'
        ];

        // Tratar exceções específicas
        if ($e instanceof AuthenticationException) {
            $status = 401;
            $response['message'] = 'Não autenticado.';
        } elseif ($e instanceof ValidationException) {
            $status = 422;
            $response['message'] = 'Os dados fornecidos são inválidos.';
            $response['errors'] = $e->errors();
        } elseif ($e instanceof ModelNotFoundException) {
            $status = 404;
            $model = strtolower(class_basename($e->getModel()));
            $response['message'] = "Não foi possível encontrar {$model} com o ID especificado.";
        } elseif ($e instanceof NotFoundHttpException) {
            $status = 404;
            $response['message'] = 'A URL solicitada não foi encontrada.';
        } elseif ($e instanceof MethodNotAllowedHttpException) {
            $status = 405;
            $response['message'] = 'O método HTTP especificado não é válido.';
        } elseif ($e instanceof HttpException) {
            $status = $e->getStatusCode();
            $response['message'] = $e->getMessage() ?: 'Erro HTTP.';
        } elseif ($e instanceof \InvalidArgumentException) {
            $status = 422;
            $response['message'] = $e->getMessage();
        }

        // Adicionar detalhes do erro em ambiente de desenvolvimento
        if (config('app.debug')) {
            $response['debug'] = [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTrace()
            ];
        }

        return response()->json($response, $status);
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        // Sempre retornar JSON para requisições de API
        if ($request->is('api/*') || $request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Não autenticado.'
            ], 401);
        }

        return redirect()->guest(route('login'));
    }
}
