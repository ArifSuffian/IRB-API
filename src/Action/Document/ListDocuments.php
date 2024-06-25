<?php

namespace App\Action\Document;
use App\Domain\Service\Document\DocumentReader;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ListDocuments
{
    private $documentReaderService;

    public function __construct(DocumentReader $documentReaderService)
    {
        $this->documentReaderService = $documentReaderService;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $request->getQueryParams();
        $path = (string) ($params['Path'] ?? null);

        $data = $this->documentReaderService->getDocumentsByPath($path);

        $result = [
            'success' => $data->success,
            'message' => $data->message,
            'data' => $data->records
        ];

        $response->getBody()->write((string) json_encode($result));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}