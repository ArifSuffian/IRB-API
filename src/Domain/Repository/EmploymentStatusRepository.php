<?php

namespace App\Domain\Repository;
use Doctrine\DBAL\Connection;

class EmploymentStatusRepository
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function createEmploymentStatus(
        string $label,
    ) {
        $query = $this->connection->createQueryBuilder();

        $query
            ->insert('employment_status')
            ->values(
                array(
                    'Label' => '?'
                )
            )
            ->setParameter(0, $label);

        $rows = $query->executeStatement();

        return $this->connection->lastInsertId();
    }

    public function getAllEmploymentStatuss()
    {
        $query = $this->connection->createQueryBuilder();

        $rows = $query
            ->select(
                'es.Id',
                'es.Label'
            )
            ->from('employment_status', 'e');

        return $rows->fetchAllAssociative();
    }

    public function getEmploymentStatusById(int $id)
    {
        $query = $this->connection->createQueryBuilder();

        $rows = $query
            ->select(
                'es.Id',
                'es.Label'
            )
            ->from('employment_status', 'e')
            ->where('es.Id = :id')
            ->setParameter('id', $id);

        return $rows->fetchAssociative();
    }

    public function updateEmploymentStatus(
        int $id,
        string $label = null
    ) {
        $query = $this->connection->createQueryBuilder();

        $query
            ->update('employment_status')
            ->where('Id = :id')
            ->setParameter('id', $id);

        if ($label != null) {
            $query->set('Label', ':label')->setParameter('label', $label);
        }

        $rows = $query->executeStatement();

        return $rows > 0;
    }

    public function deleteEmploymentStatus(
        int $id
    ) {
        $query = $this->connection->createQueryBuilder();

        $query
            ->delete('employment_status')
            ->where('Id = :id')
            ->setParameter('id', $id);

        $rows = $query->executeStatement();

        return $rows > 0;
    }
}