<?php

namespace App\Domain\Repository;

include_once(dirname(__FILE__) . '/../../../config/constants.php');

use Doctrine\DBAL\Connection;

class EmployeeRepository
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function createEmployee(
        string $workEmail,
        string $passwordHash,
        string $fullName,
        int $employmentStatusId,
        string $jobTitle,
        string $accessLevel
    ) {
        $query = $this->connection->createQueryBuilder();

        $query
            ->insert('employee')
            ->values(
                array(
                    'WorkEmail' => '?',
                    'PasswordHash' => '?',
                    'FullName' => '?',
                    'EmploymentStatus_Id' => '?',
                    'JobTitle' => '?',
                    'AccessLevel' => '?'
                )
            )
            ->setParameter(0, $workEmail)
            ->setParameter(1, $passwordHash)
            ->setParameter(2, $fullName)
            ->setParameter(3, $employmentStatusId)
            ->setParameter(4, $jobTitle)
            ->setParameter(5, $accessLevel);

        $rows = $query->executeStatement();

        return $this->connection->lastInsertId();
    }

    public function getAllEmployees()
    {
        $query = $this->connection->createQueryBuilder();

        $rows = $query
            ->select(
                'e.Id',
                'e.WorkEmail',
                'e.PasswordHash',
                'e.FullName',
                'e.EmploymentStatus_Id',
                'es.Label AS EmploymentStatus_Label',
                'e.JobTitle',
                'e.LastLogin',
                'e.AccessLevel',
                'e.AuthToken',
                'e.TokenExpiry'
            )
            ->from('employee', 'e')
            ->leftJoin('e', 'employment_status', 'es', 'e.EmploymentStatus_Id = es.Id')
            ->orderBy('FullName');

        return $rows->fetchAllAssociative();
    }

    public function getEmployeeById(int $id)
    {
        $query = $this->connection->createQueryBuilder();

        $rows = $query
            ->select(
                'e.Id',
                'e.WorkEmail',
                'e.PasswordHash',
                'e.FullName',
                'e.EmploymentStatus_Id',
                'es.Label AS EmploymentStatus_Label',
                'e.JobTitle',
                'e.LastLogin',
                'e.AccessLevel',
                'e.AuthToken',
                'e.TokenExpiry'
            )
            ->from('employee', 'e')
            ->leftJoin('e', 'employment_status', 'es', 'e.EmploymentStatus_Id = es.Id')
            ->where('e.Id = :id')
            ->setParameter('id', $id);

        return $rows->fetchAssociative();
    }

    public function getEmployeeByWorkEmail(string $workEmail)
    {
        $query = $this->connection->createQueryBuilder();

        $rows = $query
            ->select(
                'e.Id',
                'e.WorkEmail',
                'e.PasswordHash',
                'e.FullName',
                'e.EmploymentStatus_Id',
                'es.Label AS EmploymentStatus_Label',
                'e.JobTitle',
                'e.LastLogin',
                'e.AccessLevel',
                'e.AuthToken',
                'e.TokenExpiry'
            )
            ->from('employee', 'e')
            ->leftJoin('e', 'employment_status', 'es', 'e.EmploymentStatus_Id = es.Id')
            ->where('e.WorkEmail = :workEmail')
            ->setParameter('workEmail', $workEmail);

        return $rows->fetchAssociative();
    }

    public function getEmployeeByAuthToken(string $token)
    {
        $query = $this->connection->createQueryBuilder();

        $rows = $query
            ->select(
                'e.Id',
                'e.WorkEmail',
                'e.PasswordHash',
                'e.FullName',
                'e.EmploymentStatus_Id',
                'es.Label AS EmploymentStatus_Label',
                'e.JobTitle',
                'e.LastLogin',
                'e.AccessLevel',
                'e.AuthToken',
                'e.TokenExpiry'
            )
            ->from('employee', 'e')
            ->leftJoin('e', 'employment_status', 'es', 'e.EmploymentStatus_Id = es.Id')
            ->where('e.AuthToken = :token')
            ->setParameter('token', $token);

        return $rows->fetchAssociative();
    }

    public function updateEmployee(
        int $id,
        string $workEmail = null,
        string $passwordHash = null,
        string $fullName = null,
        int $employmentStatusId = null,
        string $jobTitle = null,
        string $lastLogin = null,
        int $accessLevel = null,
        string $authToken = null,
        string $tokenExpiry = null
    ) {
        $query = $this->connection->createQueryBuilder();

        $query
            ->update('employee')
            ->where('Id = :id')
            ->setParameter('id', $id);

        if ($workEmail != null) {
            $query->set('WorkEmail', ':workEmail')->setParameter('workEmail', $workEmail);
        }
        if ($passwordHash != null) {
            $query->set('PasswordHash', ':passwordHash')->setParameter('passwordHash', $passwordHash);
        }
        if ($fullName != null) {
            $query->set('FullName', ':fullName')->setParameter('fullName', $fullName);
        }
        if ($employmentStatusId != null) {
            $query->set('EmploymentStatus_Id', ':employmentStatusId')->setParameter('employmentStatusId', $employmentStatusId);
        }
        if ($employmentStatusId != null) {
            $query->set('JobTitle', ':jobTitle')->setParameter('jobTitle', $jobTitle);
        }
        if ($employmentStatusId != null) {
            $query->set('LastLogin', ':lastLogin')->setParameter('lastLogin', $lastLogin);
        }
        if ($employmentStatusId != null) {
            $query->set('AccessLevel', ':accessLevel')->setParameter('accessLevel', $accessLevel);
        }
        if ($authToken != null) {
            $query->set('AuthToken', ':authToken')->setParameter('authToken', $authToken);
        }
        if ($tokenExpiry != null) {
            $query->set('TokenExpiry', ':tokenExpiry')->setParameter('tokenExpiry', $tokenExpiry);
        }

        $rows = $query->executeStatement();

        return $rows > 0;
    }

    public function deleteEmployee(
        int $id
    ) {
        $query = $this->connection->createQueryBuilder();

        $query
            ->update('employee')
            ->where('Id = :id')
            ->setParameter('id', $id);

        $query->set('EmploymentStatus_Id', ':employmentStatusId')->setParameter('employmentStatusId', EMPLOYMENT_STATUS_TERMINATE);

        $rows = $query->executeStatement();

        return $rows > 0;
    }
}