<?php

namespace App\Domain\Service\Account\Employment_Status;
use App\Domain\Repository\EmploymentStatusRepository;

final class EmploymentStatusReader
{
    private $employmentStatusRepo;

    public function __construct(EmploymentStatusRepository $employmentStatusRepo)
    {
        $this->employmentStatusRepo = $employmentStatusRepo;
    }

    public function getAllEmploymentStatuss()
    {
        $employmentStatuss = $this->employmentStatusRepo->getAllEmploymentStatuss();
        $response = (object) [
            'success' => true,
            'message' => null,
            'records' => (object) [
                'employmentStatuss' => $employmentStatuss
            ]
        ];

        return $response;
    }

    public function getEmploymentStatusById(int $id)
    {
        $employmentStatus = $this->employmentStatusRepo->getEmploymentStatusById($id);
        $response = (object) [
            'success' => true,
            'message' => null,
            'records' => (object) [
                'employmentStatus' => $employmentStatus
            ]
        ];

        return $response;
    }
}