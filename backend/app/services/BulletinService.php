<?php
namespace App\Services;
use App\Models\Bulletin;
use PDO;

class BulletinService{
    private $pdo;

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

}