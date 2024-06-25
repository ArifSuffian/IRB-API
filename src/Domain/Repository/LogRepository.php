<?php

namespace App\Domain\Repository;
use Doctrine\DBAL\Connection;

class LogRepository
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function createLog(
        int $employeeId,
        int $documentId,
        int $logActionId,
        string $description,
        string $dateCreated
    ) {
        $query = $this->connection->createQueryBuilder();

        $query
            ->insert('log')
            ->values(
                array(
                    'Employee_Id' => '?',
                    'Document_Id' => '?',
                    'LogAction_Id' => '?',
                    'Description' => '?',
                    'DateCreated' => '?'
                )
            )
            ->setParameter(0, $employeeId)
            ->setParameter(1, $documentId)
            ->setParameter(2, $logActionId)
            ->setParameter(3, $description)
            ->setParameter(4, $dateCreated);

        $rows = $query->executeStatement();

        return $this->connection->lastInsertId();
    }

    public function getAllLogs()
    {
        $query = $this->connection->createQueryBuilder();

        $rows = $query
            ->select(
                'l.Id',
                'l.Employee_Id',
                'e.FullName AS Employee_FullName',
                'l.Document_Id',
                'd.Name AS Document_Name',
                'd.Type AS Document_Type',
                'l.LogAction_Id',
                'la.Label AS LogAction_Label',
                'l.Description',
                'l.DateCreated'
            )
            ->from('log', 'l')
            ->leftJoin('l', 'employee', 'e', 'l.Employee_Id = e.Id')
            ->leftJoin('l', 'document', 'd', 'l.Document_Id = d.Id')
            ->leftJoin('l', 'log_action', 'la', 'l.LogAction_Id = la.Id')
            ->add('orderBy', 'l.DateCreated DESC');

        return $rows->fetchAllAssociative();
    }

    public function getLogById(int $id)
    {
        $query = $this->connection->createQueryBuilder();

        $rows = $query
            ->select(
                'l.Id',
                'l.Employee_Id',
                'e.FullName AS Employee_FullName',
                'l.Document_Id',
                'd.Name AS Document_Name',
                'd.Type AS Document_Type',
                'l.LogAction_Id',
                'la.Label AS LogAction_Label',
                'l.Description',
                'l.DateCreated'
            )
            ->from('log', 'l')
            ->leftJoin('l', 'employee', 'e', 'l.Employee_Id = e.Id')
            ->leftJoin('l', 'document', 'd', 'l.Document_Id = d.Id')
            ->where('e.Id = :id')
            ->setParameter('id', $id);

        return $rows->fetchAssociative();
    }

    public function deleteLog(
        int $id
    ) {
        $query = $this->connection->createQueryBuilder();

        $query
            ->delete('log')
            ->where('Id = :id')
            ->setParameter('id', $id);

        $rows = $query->executeStatement();

        return $rows > 0;
    }
}