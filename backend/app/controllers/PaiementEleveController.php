<?php

namespace App\Controllers;

use App\Models\PaiementEleve;
use App\Services\PaiementEleveService;
use Core\Db;

class PaiementEleveController
{
    private $paymentService;

    public function __construct()
    {
        $pdo = Db::connection();
        $this->paymentService = new PaiementEleveService($pdo);
    }

    public function create()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            echo json_encode(['error' => 'Invalid JSON data']);
            return;
        }

        try {
            $payment = new PaiementEleve($input);
            
            // Apply discount and extra fees if provided
            if (isset($input['discount'])) {
                $payment->applyDiscount($input['discount']);
            }
            if (isset($input['extra_fee'])) {
                $payment->applyExtraFee($input['extra_fee']);
            }

            $result = $this->paymentService->save($payment);
            echo json_encode(['message' => 'Payment created successfully', 'data' => $result->toArray()]);
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function markAsPaid($id)
    {
        try {
            $payment = $this->paymentService->getById($id);
            if (!$payment) {
                echo json_encode(['error' => 'Payment not found']);
                return;
            }

            $payment->markAsPaid();
            $result = $this->paymentService->save($payment);
            echo json_encode(['message' => 'Payment marked as paid', 'data' => $result->toArray()]);
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getAll()
    {
        try {
            $payments = $this->paymentService->getAll();
            $paymentsArray = [];
            foreach ($payments as $payment) {
                $paymentsArray[] = $payment->toArray();
            }
            echo json_encode(['message' => 'Payments retrieved successfully', 'data' => $paymentsArray]);
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getById($id)
    {
        try {
            $payment = $this->paymentService->getById($id);
            if ($payment) {
                echo json_encode(['message' => 'Payment found', 'data' => $payment->toArray()]);
            } else {
                echo json_encode(['error' => 'Payment not found']);
            }
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}