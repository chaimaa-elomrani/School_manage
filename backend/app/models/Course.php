<?php
namespace App\Models;

use App\Interfaces\ICourse;

class Course implements ICourse{

    private $id;
    private $teacher_id;
    private $subject_id;
    private $room_id;
    private $duration;
    private $level;
    private $start_date;
    private $end_date;

    public function __construct(array $data){
        $this->id = $data['id'] ?? null;
        $this->teacher_id = $data['teacher_id'] ?? null;
        $this->subject_id = $data['subject_id'] ?? null;
        $this->room_id = $data['room_id'] ?? null;
        $this->duration = $data['duration'] ?? null;
        $this->level = $data['level'] ?? null;
        $this->start_date = $data['start_date'] ?? null;
        $this->end_date = $data['end_date'] ?? null;
    }


    public function getId(){
        return $this->id;
    }
    public function getTeacherId(){
        return $this->teacher_id;
    }
    public function getSubjectId(){
        return $this->subject_id;
    }
    public function getRoomId(){
        return $this->room_id;
    }
    public function getDuration(){
        return $this->duration;
    }
    public function getLevel(){
        return $this->level;
    }
    public function getCourseStartDate(){
        return $this->start_date;
    }
    public function getCourseEndDate(){
        return $this->end_date;
    }

    // setters 

    public function setDuration($duration){
        $this->duration = $duration;
    }
    public function setLevel($level){
        $this->level = $level;
    }
    public function setCourseStartDate($start_date){
        $this->start_date = $start_date;
    }
    public function setCourseEndDate($end_date){
        $this->end_date = $end_date;
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'teacher_id' => $this->teacher_id,
            'subject_id' => $this->subject_id,
            'room_id' => $this->room_id,
            'duration' => $this->duration,
            'level' => $this->level,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date
        ];
    }

}