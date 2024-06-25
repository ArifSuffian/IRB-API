<?php

namespace App\Domain\Service\Document;

use App\Domain\Repository\DocumentRepository;
use App\Domain\Repository\LogRepository;

final class DocumentDeleter
{
    private $documentRepo;
    private $logRepo;

    public function __construct(DocumentRepository $documentRepo, LogRepository $logRepo)
    {
        $this->documentRepo = $documentRepo;
        $this->logRepo = $logRepo;
    }

    public function deleteDocument(int $id, int $userId)
    {
        $document = $this->documentRepo->getDocumentById($id);

        if ($document['Path'] == null || $document['Path'] == '') $directory = '/home/ikramati/public_html/apis/public_html/repository/';
        else $directory = '/home/ikramati/public_html/apis/public_html/repository/' . $document['Path'] . '/';
        
        if ($this->deleteEntry($document, $directory, $userId) > 0) {
            
            $response = (object) [
                'status' => 200,
                'success' => true,
                'message' => null
            ];
        } else {
            $response = (object) [
                'status' => 500,
                'success' => true,
                'message' => 'An error occured.'
            ];
        }

        return $response;
    }
//TODO: modify sikit cara delete kene betulkan lagi
    public function deleteEntry(array $document, string $directory, int $userId)
    {
        if ($document['Type'] == 1) {
            $dir = __DIR__ . $document['Path'] . $document['Id'];
            $it = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);
            $files = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);
            foreach ($files as $file) {
                if ($file->isDir()) {
                    rmdir($file->getRealPath());
                    // $this->documentRepo->deleteDocument($document['id']); delete by path ke
                } else {
                    unlink($file->getRealPath());
                    // $this->documentRepo->deleteDocument($document['id']); delete by path ke
                }
            }
            rmdir($dir);
            $this->documentRepo->deleteDocument($dir);
            $this->logRepo->createLog($userId, $document['Id'], 5, "Folder Deleted", date("Y-m-d H:i:s"));

            return $this->documentRepo->deleteDocument($document['Id']);
        } else if ($document['Type'] == 2) {
            unlink($directory . $document['Id']);
            $this->logRepo->createLog($userId, $document['Id'], 6, "File Deleted", date("Y-m-d H:i:s"));
            
            return $this->documentRepo->deleteDocument($document['Id']);
        }

        // $this->documentRepo->deleteDocument($id)
    }
}
