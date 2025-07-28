<?php

namespace App\Services;

class PaymentService
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->prepare('
            SELECT p.*, 
                   CONCAT(per.first_name, " ", per.last_name) as student_name,
                   sf.name as fee_name
            FROM payments p 
            LEFT JOIN students s ON p.student_id = s.id
            LEFT JOIN person per ON s.person_id = per.id
            LEFT JOIN school_fees sf ON p.fee_id = sf.id
            ORDER BY p.payment_date DESC
        '); 
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}