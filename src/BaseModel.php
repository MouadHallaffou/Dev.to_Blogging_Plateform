<?php 
namespace App\Src;

use App\Config\Database;
use PDO;

abstract class BaseModel {
    protected $pdo;

    public function insertEntry($table, $data) {
        $pdo=Database::connect();

        $columns = implode(',', array_keys($data));
        $placeholders = implode(',', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array_values($data));
        return $pdo->lastInsertId();
    }

    public function updateEntry($table, $data, $idColumn, $idValue) {
        $pdo=Database::connect();

        $setClause = implode(', ', array_map(fn($col) => "$col = ?", array_keys($data)));
        $sql = "UPDATE $table SET $setClause WHERE $idColumn = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([...array_values($data), $idValue]);
        return $stmt->rowCount();
    }

    public function deleteEntry($table, $idColumn, $idValue) {
        $pdo=Database::connect();

        $sql = "DELETE FROM $table WHERE $idColumn = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idValue]);
        return $stmt->rowCount();
    }

    public function selectEntries($table, $columns = "*", $where = null, $params = []) {
        $pdo=Database::connect();

        $sql = "SELECT $columns FROM $table";
        if ($where) {
            $sql .= " WHERE $where";
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
