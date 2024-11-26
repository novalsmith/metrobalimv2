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
        try {
            $placeholders = !empty($params) ? implode(', ', array_fill(0, count($params), '?')) : '';
            $query = "CALL {$procedureName}" . ($placeholders ? "({$placeholders})" : "");

            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchObject($modelClass);
        } catch (\PDOException $e) {
            // Log the database error
            error_log("Database error in executeQueryFetchObject: " . $e->getMessage());
            // Optionally, rethrow a custom exception to Service
            throw new \RuntimeException("Failed to execute stored procedure: {$procedureName}");
        }
    }

    protected function executeQueryFetchAll(string $procedureName, array $params = [], ?string $modelClass = null, bool $singleResult = false)
    {
        try {
            $placeholders = !empty($params) ? implode(', ', array_fill(0, count($params), '?')) : '';
            $query = "CALL {$procedureName}" . ($placeholders ? "({$placeholders})" : "");

            $stmt = $this->db->prepare($query);
            $stmt->execute($params);

            if ($modelClass) {
                return $singleResult
                ? $stmt->fetchObject($modelClass)
                : $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $modelClass);
            }

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Log the database error
            error_log("Database error in executeQueryFetchAll: " . $e->getMessage());
            // Optionally, rethrow a custom exception to Service
            throw new \RuntimeException("Failed to execute stored procedure: {$procedureName}");
        }
    }

}
