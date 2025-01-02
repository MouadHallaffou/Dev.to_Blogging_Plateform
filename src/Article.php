<?php

namespace App\Src;

use PDO;
use App\Config\Database;

class Article
{
    private PDO $pdo;

    public function __construct(Database $db)
    {
        $this->pdo = $db->connect();
    }
    
}
