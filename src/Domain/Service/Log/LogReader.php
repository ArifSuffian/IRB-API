<?php

namespace App\Domain\Service\Log;

use App\Domain\Repository\LogRepository;

final class LogReader
{
    private $logRepo;

    public function __construct(LogRepository $logRepo)
    {
        $this->logRepo = $logRepo;
    }

    public function getAllLogs()
    {
        $logs = $this->logRepo->getAllLogs();
        $response = (object) [
            'success' => true,
            'message' => null,
            'records' => (object) [
                'logs' => $logs
            ]
        ];

        return $response;
    }

    public function getLogById(int $id)
    {
        $logs = $this->logRepo->getLogById($id);
        $response = (object) [
            'success' => true,
            'message' => null,
            'records' => (object) [
                'logs' => $logs
            ]
        ];

        return $response;
    }
}