<?php

namespace App\Domain\Service\Account\Employee;
use App\Domain\Repository\EmployeeRepository;

final class EmployeeCreator
{
    private $employeeRepo;

    public function __construct(EmployeeRepository $employeeRepo)
    {
        $this->employeeRepo = $employeeRepo;
    }

    public function create(string $workEmail, string $fullName, int $employmentStatusId, string $jobTitle, int $accessLevel)
    {
        try {
            $employee = $this->employeeRepo->getEmployeeByWorkEmail($workEmail);
            if ($employee == null) {
                    $rawPassword = 'IRB123';
                    $password = password_hash($rawPassword, PASSWORD_BCRYPT);
                    $this->employeeRepo->createEmployee($workEmail, $password, $fullName, $employmentStatusId, $jobTitle, $accessLevel);

                    $response = (object) [
                        'status' => 201,
                        'success' => true,
                        'message' => 'Registration was successful.'
                    ];
                    return $response;
            } else {
                $response = (object) [
                    'status' => 200,
                    'success' => false,
                    'message' => 'Email already exists.'
                ];
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
}
