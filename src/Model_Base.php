<?php 

namespace App\Src;

use PDO;

abstract class BaseModel {
    protected $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function insertEntry($table, $data) {
        $columns = implode(',', array_keys($data));
        $placeholders = implode(',', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_values($data));
        
        return $this->pdo->lastInsertId();
    }

    public function updateEntry($table, $data, $idColumn, $idValue) {
        $setClause = implode(', ', array_map(fn($col) => "$col = ?", array_keys($data)));
        $sql = "UPDATE $table SET $setClause WHERE $idColumn = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([...array_values($data), $idValue]);
        return $stmt->rowCount();
    }

    public function deleteEntry($table, $idColumn, $idValue) {
        $sql = "DELETE FROM $table WHERE $idColumn = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$idValue]);
        return $stmt->rowCount();
    }

    public function selectEntries($table, $columns = "*", $where = null, $params = []) {
        $sql = "SELECT $columns FROM $table";
        if ($where) {
            $sql .= " WHERE $where";
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
