<?php

namespace App\Domain\Service\Document;

use App\Domain\Repository\DocumentRepository;
use App\Domain\Repository\LogRepository;
use Psr\Http\Message\UploadedFileInterface;

final class DocumentCreator
{
    private $documentRepo;
    private $logRepo;

    public function __construct(DocumentRepository $documentRepo, LogRepository $logRepo)
    {
        $this->documentRepo = $documentRepo;
        $this->logRepo = $logRepo;
    }

    public function create(int $type, string $name, string $path, UploadedFileInterface | null $file, int $createdBy)
    {
        try {

            if ($path == null || $path == '') $directory =  __DIR__ . "/../../../../public_html/Repository/";
            else $directory =  __DIR__ . "/../../../../public_html/Repository/" . $path . "/";

            $id = $this->documentRepo->createDocument($type, $name, $path, $createdBy, date("Y-m-d H:i:s"));

            switch ($type) {
                case 1:
                    // if ($this->createDirectory($directory, $name)) {
                        if ($id > 0) {
                        // $id = $this->documentRepo->createDocument($type, $name, $path, $createdBy, date("Y-m-d H:i:s"));
                        $this->logRepo->createLog($createdBy, $id, 1, "Folder Created", date("Y-m-d H:i:s"));

                        $newDirectory = $directory . $id . "/";

                        if (!file_exists($newDirectory)) {
                            mkdir($newDirectory, 0755, true);
                        }

                        $response = (object) [
                            'status' => 201,
                            'success' => true,
                            'message' => 'Create directory was successful.'
                        ];
                    } else {
                        $response = (object) [
                            'status' => 200,
                            'success' => false,
                            'message' => 'Create directory was failed.'
                        ];
                    }
                    return $response;

                case 2:
                    if ($id > 0) {
                        // $id = $this->documentRepo->createDocument($type, $name, $path, $createdBy, date("Y-m-d H:i:s"));
                        $this->logRepo->createLog($createdBy, $id, 2, "File Uploaded", date("Y-m-d H:i:s"));

                        $file->moveTo($directory . DIRECTORY_SEPARATOR . $name);

                        $response = (object) [
                            'status' => 201,
                            'success' => true,
                            'message' => 'Upload file was successful.'
                        ];
                    } else {
                        $response = (object) [
                            'status' => 200,
                            'success' => false,
                            'message' => 'Upload file was failed.'
                        ];
                    }
                    return $response;
            }
        } catch (\Exception $e) {
            $response = (object) [
                'status' => 500,
                'success' => false,
                'message' => $e->getMessage()
            ];
            return $response;
        }
    }

    public function createDirectory(string $path, string $name)
    {
        if (!is_dir($path . $name)) {
            return mkdir($path . $name, 0777);
        }

        return false;
    }

    public function uploadFile(string $path, UploadedFileInterface $uploadedFile, string $name = null)
    {
        if ($name != null) {
            $filename = $name;
        } else {
            $filename = $uploadedFile->getClientFilename();
        }

        if (!file_exists($path . $filename)) {
            $uploadedFile->moveTo($path . $filename);
            return $filename;
        } else {
            unlink($path . $filename);
            $uploadedFile->moveTo($path . $filename);
            return $filename;
        }
    }
}
