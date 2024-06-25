<?php

namespace App\Domain\Service\Account\Employee;
use App\Domain\Repository\EmployeeRepository;

final class EmployeeUpdater
{
    private $employeeRepo;

    public function __construct(EmployeeRepository $employeeRepo)
    {
        $this->employeeRepo = $employeeRepo;
    }

    public function updateEmployee(
        int $id,
        string $workEmail,
        string $fullName = null,
        int $employmentStatusId = null
    ) {
        try {
            $employee = $this->employeeRepo->getEmployeeById($id);
            if ($workEmail != null) {
                if ($workEmail != $employee['WorkEmail']) {
                    $employeeEmail = $this->employeeRepo->getEmployeeByWorkEmail($workEmail);
                    if ($employeeEmail != null) {
                        $response = (object) [
                            'status' => 200,
                            'success' => false,
                            'message' => 'Email already exists.'
                        ];
                        return $response;
                    }
                }
            }

            $this->employeeRepo->updateEmployee($id, $workEmail, null, $fullName, $employmentStatusId, null, null);

            $response = (object) [
                'status' => 200,
                'success' => true,
                'message' => 'Update employee was successful.'
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

    public function updatePassword(
        int $id,
        string $currentPassword = null,
        string $newPassword = null
    ) {
        try {
            if ($newPassword != null && $currentPassword != null) {
                $employee = $this->employeeRepo->getEmployeeById($id);
                if (password_verify($currentPassword, $employee['Password'])) {
                    $newPassword = password_hash($newPassword, PASSWORD_BCRYPT);
                } else {
                    $response = (object) [
                        'status' => 200,
                        'success' => false,
                        'message' => 'Current password is wrong.'
                    ];
                    return $response;
                }
            } else {
                $response = (object) [
                    'status' => 200,
                    'success' => false,
                    'message' => 'Current password and new password are required to update the password.'
                ];
                return $response;
            }

            $this->employeeRepo->updateEmployee($id, null, $newPassword, null, null, null, null);

            $response = (object) [
                'status' => 200,
                'success' => true,
                'message' => 'Update password was successful.'
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

    public function updateToken(
        int $id,
        string $token
    ) {
        try {
            $this->employeeRepo->updateEmployee($id, null, null, null, null, $token, null);

            $response = (object) [
                'status' => 200,
                'success' => true,
                'message' => 'Update token was successful.'
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
