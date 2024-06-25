<?php

namespace App\Action\Document;
use App\Domain\Service\Document\DocumentCreator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class AddDocument
{
    private $documentCreatorService;

    public function __construct(DocumentCreator $documentCreatorService)
    {
        $this->documentCreatorService = $documentCreatorService;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $status = null;
        $data = (array) $request->getParsedBody();

        $type = (int) ($data['Type'] ?? -1);
        $name = (string) ($data['Name'] ?? null);
        $path = (string) ($data['Path'] ?? null);
        $createdBy = (int) ($data['CreatedBy'] ?? -1);
        $uploadedFiles = $request->getUploadedFiles();
        $file = $uploadedFiles['DocumentFile'];

        if (empty($type)) {
            $status = 400;
            $result = [
                'success' => false,
                'message' => 'Type is required.'
            ];
            return $this->sendResponse($response, $result, $status);
        }
        if (empty($name)) {
            $status = 400;
            $result = [
                'success' => false,
                'message' => 'Name is required.'
            ];
            return $this->sendResponse($response, $result, $status);
        }
        if (empty($createdBy)) {
            $status = 400;
            $result = [
                'success' => false,
                'message' => 'Created By is required.'
            ];
            return $this->sendResponse($response, $result, $status);
        }
        if ($type == 2 && empty($file)) {
            $status = 400;
            $result = [
                'success' => false,
                'message' => 'File is required.'
            ];
            return $this->sendResponse($response, $result, $status);
        }

        $data = $this->documentCreatorService->create($type, $name, $path, $file, $createdBy);
        if ($data->success) {
            $status = $data->status;
            $result = [
                'success' => $data->success,
                'message' => $data->message
            ];
        } else {
            $status = $data->status;
            $result = [
                'success' => $data->success,
                'message' => $data->message
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