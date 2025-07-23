<?php
namespace App\Models;

class Evaluation{
    
    private $id;
    private $course_id;
    private $title;
    private $type;
    private $date;
    private $max_score;

    public function __construct(array $data){
        $this->id = $data['id'] ?? null;
        $this->course_id = $data['course_id'] ?? null;
        $this->title = $data['title'] ?? '';
        $this->type = $data['type'] ?? '';
        $this->date = $data['date'] ?? null;
        $this->max_score = $data['max_score'] ?? 20.00;
    }

    public function getId(){
        return $this->id;
    }

    public function getCourseId(){
        return $this->course_id;
    }

    public function getTitle(){
        return $this->title;
    }

    public function getType(){
        return $this->type;
    }

    public function getDate(){
        return $this->date;
    }

    public function getMaxScore(){
        return $this->max_score;
    }

}