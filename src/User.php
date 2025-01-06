<?php
namespace App\Src;
use PDO;

class User {
    private $pdo;
    private $username;
    private $email;
    private $passwordHash;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPasswordHash($passwordHash)
    {
        $this->passwordHash = $passwordHash;
    }

    public function save()
    {
        $query = "INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)";
        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password_hash', $this->passwordHash);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
