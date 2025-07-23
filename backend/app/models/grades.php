<?php
namespace App\Models;

class Grades {
    private $id;
    private $student_id;
    private $evaluation_id;
    private $score ; 


    public function __construct(){
        $this->id = $data['id'] ?? null;
        $this->student_id = $data['student_id'] ?? null;
        $this->evaluation_id = $data['evaluation_id'] ?? null;
        $this->score = $data['score'] ?? null;
    }

    public function getId(){
        return $this->id;
    }

    public function getStudentId(){
        return $this->student_id;
    }

    public function getEvaluationId(){
        return $this->evaluation_id;
    }

    public function getScore(){
        return $this->score;
    }


}