<?php

namespace App\Domain\Service\Account\Employee;
use App\Domain\Repository\EmployeeRepository;

final class EmployeeReader
{
    private $employeeRepo;

    public function __construct(EmployeeRepository $employeeRepo)
    {
        $this->employeeRepo = $employeeRepo;
    }

    public function getAllEmployees()
    {
        $employees = $this->employeeRepo->getAllEmployees();
        $response = (object) [
            'success' => true,
            'message' => null,
            'records' => (object) [
                'employees' => $employees
            ]
        ];

        return $response;
    }

    public function getEmployeeById(int $id)
    {
        $employee = $this->employeeRepo->getEmployeeById($id);
        $response = (object) [
            'success' => true,
            'message' => null,
            'records' => (object) [
                'employee' => $employee
            ]
        ];

        return $response;
    }
}