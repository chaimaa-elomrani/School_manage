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
        $stmt = $this->pdo->prepare("
            INSERT INTO courses (subject_id, teacher_id, room_id, duration, level, start_date, end_date, title) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $course->getSubjectId(),
            $course->getTeacherId(), 
            $course->getRoomId(),
            $course->getDuration(),
            $course->getLevel(),
            $course->getCourseStartDate(),
            $course->getCourseEndDate(),
            $course->getName() // This will be stored as title
        ]);
        
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
        $stmt = $this->pdo->query("
            SELECT c.id, c.subject_id, c.teacher_id, c.room_id, 
                   c.duration, c.level, c.start_date, c.end_date, c.title as name,
                   s.name as subject_name, 
                   CONCAT(p.first_name, ' ', p.last_name) as teacher_name, 
                   r.number as room_number
            FROM courses c 
            JOIN subjects s ON c.subject_id = s.id
            JOIN teachers t ON c.teacher_id = t.id
            JOIN rooms r ON c.room_id = r.id
            JOIN person p ON t.person_id = p.id
        ");
        $courses = [];
        

        while ($row = $stmt->fetch()) {
            // Debug: log the raw data
            error_log('Course row data: ' . json_encode($row));
            $courses[] = new Course($row);
        }
        return $courses;
    }

    public function update(Course $course): Course
    {
        $stmt = $this->pdo->prepare("
            UPDATE courses 
            SET subject_id = ?, teacher_id = ?, room_id = ?, duration = ?, 
                level = ?, start_date = ?, end_date = ?, title = ? 
            WHERE id = ?
        ");
        $stmt->execute([
            $course->getSubjectId(),
            $course->getTeacherId(),
            $course->getRoomId(),
            $course->getDuration(),
            $course->getLevel(),
            $course->getCourseStartDate(),
            $course->getCourseEndDate(),
            $course->getName(),
            $course->getId()
        ]);
        
        return $course;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM courses WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
