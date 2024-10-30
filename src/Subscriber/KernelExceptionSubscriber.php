<?php

namespace App\Subscriber;

use App\Exception\ApiException;
use App\Response\ApiResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\KernelInterface;
use Throwable;

readonly class KernelExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private KernelInterface $kernel,
        private LoggerInterface $logger,
        private ParameterBagInterface $parameterBag
    ) {
    }
    public function onKernelException(ExceptionEvent $event): void
    {
        $isDevEnvironment = $this->kernel->getEnvironment() === "dev";
        $isDevJsonErrorsEnabled = $this->parameterBag->get("dev_json_errors");
        $exception = $event->getThrowable();
        $this->logException($exception);
        // If we're in dev environment and json errors are disabled we just use the default exception handler
        if ($isDevEnvironment && !$isDevJsonErrorsEnabled) {
            return;
        }
        // Else we construct the correct error format and return the JSON error
        // Each Exception which should be returned on the API must extend the ApiException class
        if ($exception instanceof ApiException) {
            //throw new \RuntimeException("test exception");
            $jsonErrors = json_encode(["errors" => $exception->getErrorMessages()]);
            if ($jsonErrors === false) {
                $error = json_last_error_msg();
                throw new \RuntimeException("JSON encoding failed: $error");
            }
            $response = new ApiResponse(
                $jsonErrors,
                $exception->getStatusCode(),
            );

            $event->setResponse($response);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => "onKernelException"
        ];
    }

    private function logException(Throwable $exception): void
    {
        // Log the exception with relevant information
        $this->logger->error('Exception occurred', [
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}
