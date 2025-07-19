<?php

namespace App\Controllers;

use App\Models\Teacher;
use App\Services\TeacherService;
use App\Factories\PersonFactory;
use Core\Db;

class TeacherController{
    private $teacherService;

    public function __construct(TeacherService $teacherService){
        $this->teacherService = $teacherService;
    }

    public function create(array $data){
        $teacher = PersonFactory::createPerson($data['role'], $data);
        $this->teacherService->save($teacher);
        return $teacher;
    }

    public function getAll(){
        $teachers = $this->teacherService->getAll();
        return $teachers;
    }

    public function getById($id){
        $teacher = $this->teacherService->getById($id);
        return $teacher;
    }

    public function update(array $data){
        $teacher = PersonFactory::createPerson($data['role'], $data);
        $this->teacherService->update($teacher);
        return $teacher;
    }


    public function delete($id){
        $this->teacherService->delete($id);
        return true;
    }

    
}

