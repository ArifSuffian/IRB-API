<?php

namespace App\Action\Account;

use App\Domain\Service\Account\Authenticator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class Authenticate
{
    private $authenticatorService;

    public function __construct(Authenticator $authenticatorService)
    {
        $this->authenticatorService = $authenticatorService;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $status = 500;
        // Collect input from the HTTP request
        $data = (array) $request->getParsedBody();

        $email = (string) ($data['email'] ?? null);
        $password = (string) ($data['password'] ?? null);

        if (empty($email)) {
            $result = [
                'success' => false,
                'message' => 'Email is required.'
            ];
            return $this->sendResponse($response, $result, $status);
        }
        if (empty($password)) {
            $result = [
                'success' => false,
                'message' => 'Password is required.'
            ];
            return $this->sendResponse($response, $result, $status);
        }

        $data = $this->authenticatorService->authenticate($email, $password);
        if (!$data->success) {
            $status = 401;
            $result = [
                'success' => $data->success,
                'message' => $data->message
            ];
        } else {
            $status = 200;
            $result = [
                'success' => $data->success,
                'message' => $data->message,
                'data' => $data->records
            ];
        }

        return $this->sendResponse($response, $result, $status);
    }

    private function sendResponse($response, $result, $status)
    {
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write((string) json_encode($result));

        return $response->withStatus($status);
    }
}