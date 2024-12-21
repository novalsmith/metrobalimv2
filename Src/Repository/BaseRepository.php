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

            // Cek apakah modelClass diberikan dan buat objek dengan data yang diambil
            if ($modelClass) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC); // Ambil data sebagai array
                if ($data) {
                    return new $modelClass($data); // Pemetaan data ke konstruktor model
                }
            } else {
                return $stmt->fetchObject($modelClass);
            }

            return null;
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
                // Jika modelClass diberikan, maka kita buat objek dari hasil query
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC); // Ambil data sebagai array

                // Pemetaan data ke objek model
                return array_map(function ($row) use ($modelClass) {
                    return new $modelClass($row); // Mengirimkan data ke konstruktor model
                }, $data);
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
