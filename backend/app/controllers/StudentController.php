<?php
require_once __DIR__ . './models/Student.php';

// here we should work with the concept of SRP, OCP and factory method in the design pattern wich are 
// SRP: Single Responsibility Principle c'est a dire que chaque classe doit avoir une seule responsabilité
// OCP: Open-Closed Principle c'est a dire que les classes doivent être ouvertes pour l'extension mais fermées
//  pour la modification on veut dire par l'extension que on peut ajouter de nouvelles fonctionnalités sans modifier le code existant 
// Factory method: c'est une méthode qui permet de créer des objets sans avoir à spécifier la classe exacte à instancier

// class StudentController{

//     private $student;
//     public function __construct(){
//         $this->student = new Student();
//     }

// //     public function index(){
// //         $sudent = PersonFactory::create('student', $request); 

// // }
//     }