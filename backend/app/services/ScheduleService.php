<?php
namespace App\Services;
use App\Models\Schedule;
use PDO;

class ScheduleService{

    private $pdo;
    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

    public function save(Schedule $schedule){
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare('INSERT INTO schedule (course_id, day, start_time, end_time) VALUES (:course_id, :day, :start_time, :end_time)');
            $stmt->execute([
                'course_id' => $schedule->getCourseId(),
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
    public function getAll(){
        $stmt = $this->pdo->prepare('SELECT * FROM schedule');
        $stmt->execute();
        $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $schedules;
    }

    public function getById($id){
        $stmt = $this->pdo->prepare('SELECT * FROM schedule WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row){
            return new Schedule($row);
        }
        return null;
    }

    public function update(Schedule $schedule){
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare('UPDATE schedule SET course_id = :course_id, day = :day, start_time = :start_time, end_time = :end_time WHERE id = :id');
            $stmt->execute([
                'id' => $schedule->getId(),
                'course_id' => $schedule->getCourseId(),
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

    public function delete($id){
        $stmt = $this->pdo->prepare('DELETE FROM schedule WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return true;
    }
}