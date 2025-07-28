<?php

namespace App\Controllers;
use App\Services\PaymentService;
use Core\Db;

class PaymentController
{
    private $paymentService;

    public function __construct(PaymentService $paymentService = null)
    {
        if ($paymentService) {
            $this->paymentService = $paymentService;
        } else {
            $pdo = Db::connection();
            $this->paymentService = new PaymentService($pdo);
        }
    }

    public function getAll()
    {
        try {
            $payments = $this->paymentService->getAll();
            echo json_encode(['message' => 'Payments retrieved successfully', 'data' => $payments]);
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}