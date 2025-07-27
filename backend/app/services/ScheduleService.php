<?php

namespace App\Services;

use App\Models\Schedule;
use PDO;

class ScheduleService
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Schedule $schedule)
    {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare('INSERT INTO schedules (course_id, room_id, teacher_id, date, start_time, end_time) VALUES (:course_id, :room_id, :teacher_id, :date, :start_time, :end_time)');
            $stmt->execute([
                'course_id' => $schedule->getCourseId(),
                'room_id' => $schedule->getRoomId(),
                'teacher_id' => $schedule->getTeacherId(),
                'date' => $schedule->getDate(),
                'start_time' => $schedule->getStartTime(),
                'end_time' => $schedule->getEndTime()
            ]);
            
            $scheduleId = $this->pdo->lastInsertId();
            $this->pdo->commit();
            
            // Return the schedule with the new ID
            $scheduleData = [
                'id' => $scheduleId,
                'course_id' => $schedule->getCourseId(),
                'room_id' => $schedule->getRoomId(),
                'teacher_id' => $schedule->getTeacherId(),
                'date' => $schedule->getDate(),
                'start_time' => $schedule->getStartTime(),
                'end_time' => $schedule->getEndTime()
            ];
            
            return new Schedule($scheduleData);
        } catch (\Exception $e) {
            $this->pdo->rollback();
            throw $e;
        }
    }

    public function getAll()
    {
        $stmt = $this->pdo->prepare('
            SELECT s.*, c.name as course_name, r.number as room_number
            FROM schedules s 
            LEFT JOIN courses c ON s.course_id = c.id
            LEFT JOIN rooms r ON s.room_id = r.id
        '); 
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM schedules WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            return new Schedule($row);
        }
        return null;
    }

    // New methods for strategy pattern support
    public function getByRoomAndDate($roomId, $date)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM schedules WHERE room_id = :room_id AND date = :date');
        $stmt->execute(['room_id' => $roomId, 'date' => $date]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $schedules = [];
        foreach ($rows as $row) {
            $schedules[] = new Schedule($row);
        }
        return $schedules;
    }

    public function getByTeacherAndDate($teacherId, $date)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM schedules WHERE teacher_id = :teacher_id AND date = :date');
        $stmt->execute(['teacher_id' => $teacherId, 'date' => $date]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $schedules = [];
        foreach ($rows as $row) {
            $schedules[] = new Schedule($row);
        }
        return $schedules;
    }

    public function getByDateRange($startDate, $endDate)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM schedules WHERE date BETWEEN :start_date AND :end_date ORDER BY date, start_time');
        $stmt->execute(['start_date' => $startDate, 'end_date' => $endDate]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $schedules = [];
        foreach ($rows as $row) {
            $schedules[] = new Schedule($row);
        }
        return $schedules;
    }

    public function getByCourseId($courseId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM schedules WHERE course_id = :course_id ORDER BY date, start_time');
        $stmt->execute(['course_id' => $courseId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $schedules = [];
        foreach ($rows as $row) {
            $schedules[] = new Schedule($row);
        }
        return $schedules;
    }

    public function update(Schedule $schedule)
    {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare('UPDATE schedules SET course_id = :course_id, room_id = :room_id, teacher_id = :teacher_id, date = :date, start_time = :start_time, end_time = :end_time WHERE id = :id');
            $stmt->execute([
                'id' => $schedule->getId(),
                'course_id' => $schedule->getCourseId(),
                'room_id' => $schedule->getRoomId(),
                'teacher_id' => $schedule->getTeacherId(),
                'date' => $schedule->getDate(),
                'start_time' => $schedule->getStartTime(),
                'end_time' => $schedule->getEndTime()
            ]);
            $this->pdo->commit();
            return $schedule;
        } catch (\Exception $e) {
            $this->pdo->rollback();
            throw $e;
        }
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM schedules WHERE id = :id');
        $result = $stmt->execute(['id' => $id]);
        return $result;
    }
}
