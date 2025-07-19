<?php

namespace App\Services;

use App\Models\Teacher;
use PDO;

class TeacherService{

    private $pdo; 

    public function __construct(PDO $pdo){
        $this->pdo = $pdo ; 
    }

    public function save(Teacher $teacher){
        $stmt = $this->pdo->prepare('INSERT INTO teachers (name, email , role , absence , salary , subject ) VALUES (:name, :email , :role , :absence , :salary , :subject)'); 
        $stmt->execute([
            'name' => $teacher->getName(),
            'email' => $teacher->getEmail(),
            'role' => $teacher->getRole(),
            'absence' => $teacher->getAbsence(),
            'salary' => $teacher->getSalary(),
            'subject' => $teacher->getSubject()
        ]);

        return $teacher; 
    }


    public function getAll(){
        $stmt = $this->pdo->prepare('SELECT * FROM teachers'); 
        $stmt->execute();
       $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC); 
       return $teachers;
    }


    public function getById($id){
        $stmt = $this->pdo->prepare('SELECT * FROM teachers WHERE id = :id'); 
        $stmt->execute(['id' => $id]); 
        $teacher = $stmt->fetch(PDO::FETCH_ASSOC);
        return $teacher;
    }


    public function update(Teacher $teacher){
        $stmt = $this->pdo->prepare('UPDATE teachers SET name = :name , email = :email , role = :role , absence = :absence , salary = :salary , subject = :subject WHERE id = :id');
        $stmt->execute([
            'name' => $teacher->getName(),
            'email' => $teacher->getEmail(),
            'role' => $teacher->getRole(),
            'absence' => $teacher->getAbsence(),
            'salary' => $teacher->getSalary(),
            'subject' => $teacher->getSubject(),
        ]); 

       return $teacher;
    }


    public function delete($id){
        $stmt = $this->pdo->prepare('DELETE FROM teachers WHERE id = :id'); 
        $stmt->execute(['id' => $id]); 
        return true ;
    }
}
