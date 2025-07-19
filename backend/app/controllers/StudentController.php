<?php

namespace App\Controllers;

use App\Models\Student;
use App\Services\StudentService;
use App\Factories\PersonFactory;
use Core\Db;

class StudentController
{

    private $studentService ; 

    public function __construct(StudentService $studentService = null)
    {
        if ($studentService) {
            $this->studentService = $studentService;
        } else {
            $pdo = Db::connection();
            $this->studentService = new StudentService($pdo);
        }
    }


    public function create(){
        // Get data from request body for POST requests
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            echo json_encode(['error' => 'Invalid JSON data']);
            return;
        }

        try {
            $student = PersonFactory::createPerson($input['role'] ?? 'student', $input); 
            $result = $this->studentService->save($student);
            echo json_encode(['message' => 'Student created successfully', 'data' => $result]);
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }



    public function getAll(){
        try {
            $students = $this->studentService->getAll();
            $studentsArray = [];
            foreach($students as $student) {
                $studentsArray[] = $student->toArray();
            }
            echo json_encode(['message' => 'Students retrieved successfully', 'data' => $studentsArray]);
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getById($id){
        try {
            $student = $this->studentService->getById($id);
            if ($student) {
                echo json_encode(['message' => 'Student found', 'data' => $student->toArray()]);
            } else {
                echo json_encode(['error' => 'Student not found']);
            }
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function update(){
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            echo json_encode(['error' => 'Invalid JSON data']);
            return;
        }

        try {
            $student = PersonFactory::createPerson($input['role'] ?? 'student', $input); // ($data['role'], $data) this means that we are passing the role and the data to the createPerson method
            $result = $this->studentService->update($student); 
            echo json_encode(['message' => 'Student updated successfully', 'data' => $result]);
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function delete(){
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['id'])) {
            echo json_encode(['error' => 'Student ID is required']);
            return;
        }

        try {
            $result = $this->studentService->delete($input['id']);
            echo json_encode(['message' => 'Student deleted successfully']);
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }


}
