<?php

namespace App\Domain\Service\Account;
use App\Domain\Repository\EmployeeRepository;

final class Authenticator
{
    private $employeeRepo;
    public function __construct(EmployeeRepository $employeeRepo)
    {
        $this->employeeRepo = $employeeRepo;
    }

    public function authenticate(string $workEmail, string $password)
    {
        $employee = $this->employeeRepo->getEmployeeByWorkEmail($workEmail);
        if ($employee != null) {
            if ($employee['EmploymentStatus_Id'] == 4) {
                    if (password_verify($password, $employee['PasswordHash'])) {

                        $token = bin2hex(openssl_random_pseudo_bytes(8));
                        $tokenExpiration = date('Y-m-d H:i:s', strtotime('+1 hour'));
                        $this->employeeRepo->updateEmployee($employee['Id'], null, null, null, null, null, null, null, $token, $tokenExpiration);
                        $employee['AuthToken'] = $token;

                        $response = (object) [
                            'success' => true,
                            'message' => null,
                            'records' => (object) [
                                'user' => (object) [
                                    'Id' => $employee['Id'],
                                    'WorkEmail' => $employee['WorkEmail'],
                                    'FullName' => $employee['FullName'],
                                    'EmploymentStatus_Id' => $employee['EmploymentStatus_Id'],
                                    'EmploymentStatus_Label' => $employee['EmploymentStatus_Label'],
                                    'JobTitle' => $employee['JobTitle'],
                                    'LastLogin' => $employee['LastLogin'],
                                    'AccessLevel' => $employee['AccessLevel'],
                                    'AuthToken' => $employee['AuthToken']
                                ]
                            ]
                        ];

                        return $response;
                    } else {
                        $response = (object) [
                            'success' => false,
                            'message' => 'Incorrect email/password.'
                        ];
                        return $response;
                    }
            } else {
                $response = (object) [
                    'success' => false,
                    'message' => 'Account has been terminated.'
                ];
                return $response;
            }
        } else {
            $response = (object) [
                'success' => false,
                'message' => 'Incorrect email/password.'
            ];
            return $response;
        }
    }
    
    public function validateToken(string $accessToken): bool
    {
        $user = $this->employeeRepo->getEmployeeByAuthToken($accessToken);
        return $user != null;
    }
}