<?php
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Teacher.php';
class PersonFactory{

    public static function createPerson(string $role, array $data): ?IPerson
    {
        if ($role === 'student') {
            $newStudent = new Student($data);
            return $newStudent;
        }else if ($role === 'teacher'){
            $newTeacher = new Teacher($data);
            return $newTeacher;
        }else {
            return null ; 
        }
    }
}