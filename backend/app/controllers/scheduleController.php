<?php

namespace App\Controllers;

use App\Services\ScheduleService;
use App\Services\CourseService;
use App\Services\RoomService;
use App\Strategies\PlanningStrategy;
use Core\Db;
use DateTime;

class ScheduleController
{
    private $scheduleService;
    private $planningStrategy;
    private $courseService;
    private $roomService;

    public function __construct(PlanningStrategy $planningStrategy, CourseService $courseService, RoomService $roomService, ScheduleService $scheduleService = null) // why scheduleService = null , aanswer: because we want to check if it exists or not
    {
        if ($planningStrategy && $courseService && $roomService && $scheduleService) { //this is a dependency injection wich means that we can use the same instance of the class in different places
            $this->planningStrategy = $planningStrategy;
            $this->courseService = $courseService;
            $this->roomService = $roomService;
            $this->scheduleService = $scheduleService;
        } else {
            $pdo = Db::connection();
            $this->planningStrategy = new PlanningStrategy($pdo);
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
            $course = $this->courseService->getById($input['course_id']);
            $room = $this->roomService->getById($input['room_id']);
            
            if (!$course || !$room) {
                echo json_encode(['error' => 'Course or Room not found']);
                return;
            }

            $date = new DateTime($input['date']);
            $startTime = $input['start_time'];
            $endTime = $input['end_time'];

            $schedule = $this->planningStrategy->plan($course, $room, $date, $startTime, $endTime);
            
            echo json_encode([
                'message' => 'Schedule created successfully',
                'data' => $schedule->toArray()
            ]);
            return $schedule;
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
            // First cancel the existing plan
            $this->planningStrategy->cancelPlan($id);

            // Then create new plan
            $course = $this->courseService->getById($input['course_id']);
            $room = $this->roomService->getById($input['room_id']);
            
            if (!$course || !$room) {
                echo json_encode(['error' => 'Course or Room not found']);
                return;
            }
            
            $date = new DateTime($input['date']);
            $startTime = $input['start_time'];
            $endTime = $input['end_time'];

            $schedule = $this->planningStrategy->plan($course, $room, $date, $startTime, $endTime);
            
            echo json_encode(['message' => 'Schedule updated successfully', 'data' => $schedule->toArray()]);
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        try {
            $result = $this->planningStrategy->cancelPlan($id);
            
            if ($result) {
                echo json_encode(['message' => 'Schedule deleted successfully']);
            } else {
                echo json_encode(['error' => 'Failed to delete schedule']);
            }
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function checkAvailability()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            echo json_encode(['error' => 'Invalid JSON data']);
            return;
        }

        try {
            $course = $this->courseService->getById($input['course_id']);
            $room = $this->roomService->getById($input['room_id']);
            
            if (!$course || !$room) {
                echo json_encode(['error' => 'Course or Room not found']);
                return;
            }

            $date = new DateTime($input['date']);
            $startTime = $input['start_time'];
            $endTime = $input['end_time'];

            $isAvailable = $this->planningStrategy->isAvailable($course, $room, $date, $startTime, $endTime);
            
            if ($isAvailable) {
                echo json_encode(['message' => 'Time slot is available', 'available' => true]);
            } else {
                $conflicts = $this->planningStrategy->getConflicts($course, $room, $date, $startTime, $endTime);
                echo json_encode([
                    'message' => 'Time slot is not available', 
                    'available' => false,
                    'conflicts' => $conflicts
                ]);
            }
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}