<?php

namespace App\Domain\Service\Document;
use App\Domain\Repository\DocumentRepository;

final class DocumentReader
{
    private $documentRepo;

    public function __construct(DocumentRepository $documentRepo)
    {
        $this->documentRepo = $documentRepo;
    }

    public function getDocumentById(int $id)
    {
        $documents = $this->documentRepo->getDocumentById($id);
        $response = (object) [
            'success' => true,
            'message' => null,
            'records' => (object) [
                'documents' => $documents
            ]
        ];

        return $response;
    }

    public function getDocumentsByPath(string $path)
    {
        $documents = $this->documentRepo->getDocumentsByPath($path);
        $response = (object) [
            'success' => true,
            'message' => null,
            'records' => (object) [
                'documents' => $documents
            ]
        ];

        return $response;
    }
}