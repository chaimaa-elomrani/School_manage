<?php

namespace App\Controllers;

use App\Services\ScheduleService;

class ScheduleController
{
    private $scheduleService;

    public function __construct()
    {
        $this->scheduleService = new ScheduleService();
    }

    public function showSchedules()
    {
        try {
            $schedules = $this->scheduleService->getAll();
            
            header('Content-Type: application/json');
            echo json_encode([
                'message' => 'Schedules retrieved successfully',
                'data' => $schedules
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}