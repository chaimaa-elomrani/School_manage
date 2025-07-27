<?php

namespace App\Controllers;
use App\Services\EnrollmentService;
use Core\Db;

class EnrollmentController
{
    private $enrollmentService;

    public function __construct(EnrollmentService $enrollmentService = null)
    {
        if ($enrollmentService) {
            $this->enrollmentService = $enrollmentService;
        } else {
            $pdo = Db::connection();
            $this->enrollmentService = new EnrollmentService($pdo);
        }
    }

    public function getAll()
    {
        try {
            $enrollments = $this->enrollmentService->getAll();
            echo json_encode(['message' => 'Enrollments retrieved successfully', 'data' => $enrollments]);
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}