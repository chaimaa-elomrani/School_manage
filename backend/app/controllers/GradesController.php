<?php

namespace App\Controllers;
use App\Services\GradeService;
use App\Models\Grades;
use Core\Db;

class GradesController
{
    private $gradeService;

    public function __construct(GradeService $gradeService = null)
    {
        if ($gradeService) {
            $this->gradeService = $gradeService;
        } else {
            $pdo = Db::connection();
            $this->gradeService = new GradeService($pdo);
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
            $grade = new Grades($input);
            $result = $this->gradeService->save($grade);
            echo json_encode(['message' => 'Grade created successfully', 'data' => $result]);
            return $result;
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
            $grade = new Grades($input);
            $result = $this->gradeService->update($grade);
            echo json_encode(['message' => 'Grade updated successfully', 'data' => $result]);
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        try {
            $this->gradeService->delete($id);
            echo json_encode(['message' => 'Grade deleted successfully']);
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getAll()
    {
        try {
            $grades = $this->gradeService->getAll();
            $gradesArray = [];
            foreach ($grades as $grade) {
                $gradesArray[] = $grade->toArray();
            }
            echo json_encode(['message' => 'Grades retrieved successfully', 'data' => $gradesArray]);
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getById($id)
    {
        try {
            $grade = $this->gradeService->getById($id);
            if ($grade) {
                echo json_encode(['message' => 'Grade found', 'data' => $grade->toArray()]);
            } else {
                echo json_encode(['error' => 'Grade not found']);
            }
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    
}