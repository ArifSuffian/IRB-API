<?php

namespace App\Domain\Repository;

include_once(dirname(__FILE__) . '/../../../config/constants.php');

use Doctrine\DBAL\Connection;

class DocumentRepository
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function createDocument(
        int $type,
        string $name,
        string $path,
        int $createdBy,
        string $DateCreated
    ) {
        $query = $this->connection->createQueryBuilder();

        $query
            ->insert('document')
            ->values(
                array(
                    'Type' => '?',
                    'Name' => '?',
                    'Path' => '?',
                    'CreatedBy' => '?',
                    'DateCreated' => '?'
                )
            )
            ->setParameter(0, $type)
            ->setParameter(1, $name)
            ->setParameter(2, $path)
            ->setParameter(3, $createdBy)
            ->setParameter(4, $DateCreated);

        $rows = $query->executeStatement();

        return $this->connection->lastInsertId();
    }

    public function getAllDocuments()
    {
        $query = $this->connection->createQueryBuilder();

        $rows = $query
            ->select(
                'd.Id',
                'd.Type',
                'd.Name',
                'd.Path',
                'd.CreatedBy',
                'e1.FullName AS CreatedBy_FullName',
                'd.DateCreated',
                'd.UpdatedBy',
                'e2.FullName AS UpdatedBy_FullName',
                'd.DateUpdated',
                'd.DataStatus'
            )
            ->from('document', 'd')
            ->leftJoin('d', 'employee', 'e1', 'd.CreatedBy = e1.Id')
            ->leftJoin('d', 'employee', 'e2', 'd.UpdatedBy = e2.Id')
            ->where('d.DataStatus = :dataStatus')
            ->setParameter('dataStatus', DATA_STATUS_EXIST);

        return $rows->fetchAllAssociative();
    }

    public function getDocumentById(int $id)
    {
        $query = $this->connection->createQueryBuilder();

        $rows = $query
            ->select(
                'd.Id',
                'd.Type',
                'd.Name',
                'd.Path',
                'd.CreatedBy',
                'e1.FullName AS CreatedBy_FullName',
                'd.DateCreated',
                'd.UpdatedBy',
                'e2.FullName AS UpdatedBy_FullName',
                'd.DateUpdated',
                'd.DataStatus'
            )
            ->from('document', 'd')
            ->leftJoin('d', 'employee', 'e1', 'd.CreatedBy = e1.Id')
            ->leftJoin('d', 'employee', 'e2', 'd.UpdatedBy = e2.Id')
            ->where('d.Id = :id AND d.DataStatus = :dataStatus')
            ->setParameter('id', $id)
            ->setParameter('dataStatus', DATA_STATUS_EXIST);

        return $rows->fetchAssociative();
    }

    public function getDocumentsByPath(string $path)
    {
        $query = $this->connection->createQueryBuilder();

        $rows = $query
            ->select(
                'd.Id',
                'd.Type',
                'd.Name',
                'd.Path',
                'd.CreatedBy',
                'e1.FullName AS CreatedBy_FullName',
                'd.DateCreated',
                'd.UpdatedBy',
                'e2.FullName AS UpdatedBy_FullName',
                'd.DateUpdated',
                'd.DataStatus'
            )
            ->from('document', 'd')
            ->leftJoin('d', 'employee', 'e1', 'd.CreatedBy = e1.Id')
            ->leftJoin('d', 'employee', 'e2', 'd.UpdatedBy = e2.Id')
            ->where('d.Path = :path AND d.DataStatus = :dataStatus')
            ->setParameter('path', $path)
            ->setParameter('dataStatus', DATA_STATUS_EXIST);

        return $rows->fetchAllAssociative();
    }

    public function getDocumentByName(string $name)
    {
        $query = $this->connection->createQueryBuilder();

        $rows = $query
            ->select(
                'd.Id',
                'd.Type',
                'd.Name',
                'd.Path',
                'd.CreatedBy',
                'e1.FullName AS CreatedBy_FullName',
                'd.DateCreated',
                'd.UpdatedBy',
                'e2.FullName AS UpdatedBy_FullName',
                'd.DateUpdated',
                'd.DataStatus'
            )
            ->from('document', 'd')
            ->leftJoin('d', 'employee', 'e1', 'd.CreatedBy = e1.Id')
            ->leftJoin('d', 'employee', 'e2', 'd.UpdatedBy = e2.Id')
            ->where('d.Name = :name AND d.DataStatus = :dataStatus')
            ->setParameter('name', $name)
            ->setParameter('dataStatus', DATA_STATUS_EXIST);

        return $rows->fetchAssociative();
    }

    public function updateDocument(
        int $id,
        string $name = null,
        string $updatedBy = null,
        string $dateUpdated = null
    ) {
        $query = $this->connection->createQueryBuilder();

        $query
            ->update('document')
            ->where('Id = :id AND d.DataStatus = :dataStatus')
            ->setParameter('id', $id)
            ->setParameter('dataStatus', DATA_STATUS_EXIST);

        if ($name != null) {
            $query->set('Name', ':name')->setParameter('name', $name);
        }
        if ($updatedBy != null) {
            $query->set('UpdatedBy', ':updatedBy')->setParameter('updatedBy', $updatedBy);
        }
        if ($dateUpdated != null) {
            $query->set('DateUpdated', ':dateUpdated')->setParameter('dateUpdated', $dateUpdated);
        }

        $rows = $query->executeStatement();

        return $rows > 0;
    }

    public function deleteDocument(
        int $id
    ) {
        $query = $this->connection->createQueryBuilder();

        $query
            ->update('document')
            ->where('Id = :id')
            ->setParameter('id', $id);

        $query->set('DataStatus', ':dataStatus')->setParameter('dataStatus', DATA_STATUS_DELETED);
        
        $rows = $query->executeStatement();

        return $rows > 0;
    }

    public function deleteDocumentByPath(
        string $path
    ) {
        $query = $this->connection->createQueryBuilder();

        $query
            ->update('document')
            ->where('Path LIKE :path%')
            ->setParameter('path', $path);

        $query->set('DataStatus', ':dataStatus')->setParameter('dataStatus', DATA_STATUS_DELETED);

        $rows = $query->executeStatement();

        return $rows > 0;
    }
}