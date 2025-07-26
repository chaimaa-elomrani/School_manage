<?php

namespace App\Services;
use App\Models\Course;
use PDO;

class CourseService{
    private $pdo;

    public function  __construct(PDO $pdo){
        $this->pdo = $pdo; 
    }

    public function save(Course $course){
        $this->pdo->beginTransaction(); //this function of beginTransaction is a funnction in the pdo class , allows us to insert the data in the database in a secure way 

        try {
            $stmt = $this->pdo->prepare('INSERT INTO courses (name ,teacher_id , subject_id , room_id, duration, level , start_date, end_date) VALUES (:name, :teacher_id ,:subject_id , :room_id , :duration , :level , :start_date , :end_date)');
            $stmt->execute([
                'name' => $course->getName(),
                'teacher_id' => $course->getTeacherId(),
                'subject_id' => $course->getSubjectId(),
                'room_id' => $course->getRoomId(),
                'duration' => $course->getDuration(),
                'level' => $course->getLevel(),
                'start_date' => $course->getCourseStartDate(),
                'end_date' => $course->getCourseEndDate()
            ]);

            $this->pdo->commit();
            return $course; 
        }catch(\Exception $e){
            $this->pdo->rollback(); //rollback function means to cancel the transaction if there is an error
            throw $e; //$e means the error
        }
    }

    public function getAll(){
        $stmt = $this->pdo->prepare(
            'SELECT c.id , c.name , c.teacher_id , c.subject_id , 
                   c.room_id , c.duration , c.level , c.start_date , c.end_date , 
                   p.first_name , p.last_name , s.name as subject_name , r.number FROM courses c 
                   JOIN teachers t ON c.teacher_id = t.id 
                   JOIN person p ON t.person_id = p.id
                   JOIN subjects s ON c.subject_id = s.id 
                   JOIN rooms r ON c.room_id = r.id'); 
        
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC); 
        $courses = []; 

        foreach($rows as $row){
            $courses[] = new Course($row); 
        }

        return $courses ;
    }


    public function getById($id){
        $stmt = $this->pdo->prepare('SELECT c.id , c.name , c.teacher_id , c.subject_id , 
                   c.room_id , c.duration , c.level , c.start_date , c.end_date , 
                   p.first_name , p.last_name , s.name as subject_name , r.number FROM courses c 
                   JOIN teachers t ON c.teacher_id = t.id 
                   JOIN person p ON t.person_id = p.id
                   JOIN subjects s ON c.subject_id = s.id 
                   JOIN rooms r ON c.room_id = r.id 
                   WHERE c.id = :id');
        $stmt->execute(['id' => $id]); 
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row){
            return new Course($row);
        }
        return null;
    }

    public function update(Course $course){
        $this->pdo->beginTransaction();
        
        try {
            $stmt = $this->pdo->prepare(
                'UPDATE courses SET name = :name , teacher_id = :teacher_id ,
                subject_id = :subject_id , room_id = :room_id , duration = :duration , 
                level = :level , start_date = :start_date , end_date = :end_date WHERE id = :id');
                $stmt->execute([
                    'name' => $course->getName(),
                    'teacher_id' => $course->getTeacherId(),
                    'subject_id' => $course->getSubjectId(),
                    'room_id' => $course->getRoomId(),
                    'duration' => $course->getDuration(),
                    'level' => $course->getLevel(),
                    'start_date' => $course->getCourseStartDate(),
                    'end_date' => $course->getCourseEndDate(),
                ]); 
                $this->pdo->commit();
                return $course;
        }catch(\Exception $e){
            $this->pdo->rollback();
            throw $e;
        }
    }

    public function delete($id){
        $stmt = $this->pdo->prepare('DELETE FROM courses WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return true;
    }


}
