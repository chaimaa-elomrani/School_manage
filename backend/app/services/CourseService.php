<?php

namespace App\Services;

use App\Models\Course;
use PDO;

class CourseService
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Course $course): array
    {
        $stmt = $this->pdo->prepare("INSERT INTO courses (name, code, credits, description) VALUES (?, ?, ?, ?)");
        $stmt->execute([$course->getName(), $course->getCode(), $course->getCredits(), $course->getDescription()]);
        
        return ['id' => $this->pdo->lastInsertId(), 'message' => 'Course saved successfully'];
    }

    public function getById(int $id): ?Course
    {
        $stmt = $this->pdo->prepare("SELECT * FROM courses WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch();
        
        return $data ? new Course($data) : null;
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM courses");
        $courses = [];
        
        while ($row = $stmt->fetch()) {
            $courses[] = new Course($row);
        }
        
        return $courses;
    }
}
