<?php

class ScheduleController {
    private $scheduleService;

    public function __construct($scheduleService) {
        $this->scheduleService = $scheduleService;
    }

    public function getAll() {
        try {
            $schedules = $this->scheduleService->getAll();
            echo json_encode(['message' => 'Schedules retrieved successfully', 'data' => $schedules]);
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
