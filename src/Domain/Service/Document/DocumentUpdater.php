<?php

namespace App\Domain\Service\Document;
use App\Domain\Repository\DocumentRepository;

final class DocumentUpdater
{
    private $documentRepo;

    public function __construct(DocumentRepository $documentRepo)
    {
        $this->documentRepo = $documentRepo;
    }

    public function updateDocument(
        int $id,
        string $name,
        string $updatedBy
    ) {
        try {

            $this->documentRepo->updateDocument($id, $name, $updatedBy, date("Y-m-d H:i:s"));

            $response = (object) [
                'status' => 200,
                'success' => true,
                'message' => 'Update document was successful.'
            ];
            return $response;
        } catch (\Exception $e) {
            $response = (object) [
                'status' => 500,
                'success' => false,
                'message' => $e->getMessage()
            ];
            return $response;
        }
    }
}