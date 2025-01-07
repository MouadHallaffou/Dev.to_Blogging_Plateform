<?php
namespace App\Src;

use PDO;
use PDOException;

class User {
    private $pdo;
    private $username;
    private $email;
    private $passwordHash;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPasswordHash(string $passwordHash): void
    {
        $this->passwordHash = $passwordHash;
    }

    // Save dans la base
    public function save(): bool
    {
        $query = "INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)";
        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password_hash', $this->passwordHash);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException("Erreur lors de l'enregistrement de l'utilisateur : " . $e->getMessage());
        }
    }

    // find user par email
    public function findByEmail(string $email): ?array
    {
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user ?: null;
        } catch (PDOException $e) {
            throw new PDOException("Erreur lors de la récupération de l'utilisateur : " . $e->getMessage());
        }
    }

}
