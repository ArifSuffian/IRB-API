<?php

namespace App\Action\Log;

use App\Domain\Service\Log\LogReader;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ListLogs
{
    private $logReaderService;

    public function __construct(LogReader $logReaderService)
    {
        $this->logReaderService = $logReaderService;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $request->getQueryParams();

        $data = $this->logReaderService->getAllLogs();

        $result = [
            'success' => $data->success,
            'message' => $data->message,
            'data' => $data->records
        ];

        $response->getBody()->write((string) json_encode($result));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}