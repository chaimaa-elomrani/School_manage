<?php
namespace App\Controllers;
use App\Services\ScheduleService;
use App\Models\Schedule;
use Core\Db;


class ScheduleController
{
    private $scheduleService;
    public function __construct(ScheduleService $scheduleService = null)
    {
        if ($scheduleService) {
            $this->scheduleService = $scheduleService;
        } else {
            $pdo = Db::connection();
            $this->scheduleService = new ScheduleService($pdo);
        }
    }

    public function create()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input) {
            echo json_encode(['error' => 'Invalid JSON data']);
            return;
        }
        try {
            $schedule = new Schedule($input);
            $result = $this->scheduleService->save($schedule);
            echo json_encode(['message' => 'Schedule created successfully', 'data' => $result]);
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getAll()
    {
        try {
            $schedules = $this->scheduleService->getAll();
            $schedulesArray = [];
            foreach ($schedules as $schedule) {
                $schedulesArray[] = $schedule->toArray();
            }
            echo json_encode(['message' => 'Schedules retrieved successfully', 'data' => $schedulesArray]);
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getById($id)
    {
        try {
            $schedule = $this->scheduleService->getById($id);
            if ($schedule) {
                echo json_encode(['message' => 'Schedule found', 'data' => $schedule->toArray()]);
            } else {
                echo json_encode(['error' => 'Schedule not found']);
            }
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }


    }

    public function update($id)
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input) {
            echo json_encode(['error' => 'Invalid JSON data']);
            return;
        }

        try {
            $schedule = new Schedule($input);
            $result = $this->scheduleService->update($schedule);
            echo json_encode(['message' => 'Schedule updated successfully', 'data' => $result]);
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        try {
            $this->scheduleService->delete($id);
            echo json_encode(['message' => 'Schedule deleted successfully']);
        } catch (\Exception $e) {

            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}