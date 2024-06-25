<?php

namespace App\Action\Account\Employee;
use App\Domain\Repository\EmployeeRepository;

final class EmployeeDeleter
{
    private $employeeRepo;

    public function __construct(EmployeeRepository $employeeRepo)
    {
        $this->employeeRepo = $employeeRepo;
    }

    public function deleteEmployee(int $id)
    {
        if ($this->employeeRepo->deleteEmployee($id) > 0) {
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
}