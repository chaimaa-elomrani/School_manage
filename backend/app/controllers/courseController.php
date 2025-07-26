<?php

namespace App\Controllers;
use App\Services\CourseService;
use App\Models\Course;
use Core\Db;

class CourseController
{
    private $courseService;

    public function __construct(CourseService $courseService = null)
    {
        if ($courseService) {
            $this->courseService = $courseService;
        } else {
            $pdo = Db::connection();
            $this->courseService = new CourseService($pdo);
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
            $course = new Course($input);
            $result = $this->courseService->save($course);
            echo json_encode(['message' => 'Course created successfully', 'data' => $result]);
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }


    public function getAll()
    {
        try {
            $courses = $this->courseService->getAll();
            $coursesArray = [];
            foreach ($courses as $course) {
                $coursesArray[] = $course->toArray();
            }
            echo json_encode($coursesArray);
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }


    public function getById($id)
    {
        try {
            $course = $this->courseService->getById($id);
            echo json_encode($course->toArray());
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
            $course = new Course($input);
            $results = $this->courseService->update($course);
            echo json_encode(['message' => 'Course updated successfully', 'data' => $results]);
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }


    public function delete($id)
    {
        try {
            $this->courseService->delete($id);
            echo json_encode(['message' => 'Course deleted successfully']);
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
