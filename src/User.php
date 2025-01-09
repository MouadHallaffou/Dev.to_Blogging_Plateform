<?php
namespace App\Src;

use PDO;
use PDOException;

class User {
    private PDO $pdo;
    private $id;
    private string $username;
    private string $email;
    private string $passwordHash;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function setUsername(string $username): void {
        $this->username = $username;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function setPasswordHash(string $passwordHash): void {
        $this->passwordHash = $passwordHash;
    }
    public function setId($id){
        $this->id = $id;
    }
    public function save() {
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

    public function findByEmail(string $email) {
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        try {
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            throw new PDOException("Erreur lors de la recuperation de utilisateur : " . $e->getMessage());
        }
    }

    public static function hashPassword(string $password){
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function asignRole(){
        $query = "UPDATE users SET role = 'author' WHERE id_user = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id' , $this->id, PDO::PARAM_STR);
        $stmt->execute();
    }
}
