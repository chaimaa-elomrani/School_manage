<?php
require_once __DIR__ . './models/Student.php';

// here we should work with the concept of SRP, OCP and factory method in the design pattern wich are 
// SRP: Single Responsibility Principle c'est a dire que chaque classe doit avoir une seule responsabilité
// OCP: Open-Closed Principle c'est a dire que les classes doivent être ouvertes pour l'extension mais fermées
//  pour la modification on veut dire par l'extension que on peut ajouter de nouvelles fonctionnalités sans modifier le code existant 
// Factory method: c'est une méthode qui permet de créer des objets sans avoir à spécifier la classe exacte à instancier

class StudentController
{

    private $studentService ; 

    public function __construct(StudentService $studentService){ // the params are the dependencies of the class , dependencies are the classes that are used in the class
        $this->studentService = $studentService; 
    }


    public function create(array $data){
        $student = PersonFactory::createPerson($data['role'], $data); 
        $this->studentService->save($student);
        return $student ; 
    }



    public function getAll(){
        $students = $this->studentService->getAll();
        return $students;
    }

    public function getById($id){
        $student = $this->studentService->getById($id);
        return $student;
    }

    public function update(array $data){
        $student = PersonFactory::createPerson($data['role'], $data); // ($data['role'], $data) this means that we are passing the role and the data to the createPerson method
        $this->studentService->update($student);
        return $student;
    }

    public function delete($id){
        $this->studentService->delete($id);
        return true;
    }


}