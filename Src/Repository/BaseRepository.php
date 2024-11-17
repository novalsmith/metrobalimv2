<?php

namespace App\Src\Repository;

use PDO;

class BaseRepository
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    protected function executeQueryFetchObject(string $procedureName, array $params = [], ?string $modelClass = null)
    {
        $placeholders = !empty($params) ? implode(', ', array_fill(0, count($params), '?')) : '';
        $query = "CALL {$procedureName}" . ($placeholders ? "({$placeholders})" : "");

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchObject($modelClass);
    }

    protected function executeQueryFetchAll(string $procedureName, array $params = [], ?string $modelClass = null, bool $singleResult = false)
    {
        $placeholders = !empty($params) ? implode(', ', array_fill(0, count($params), '?')) : '';
        $query = "CALL {$procedureName}" . ($placeholders ? "({$placeholders})" : "");

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        if ($modelClass) {
            // Return a single model instance if $singleResult is true
            if ($singleResult) {
                return $stmt->fetchObject($modelClass);
            }
            // Return an array of model instances
            return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $modelClass);
        }

        // Default to returning an associative array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
