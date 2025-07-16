<?php

class StudentService{

    private $pdo; 

    public function __construct(PDO $pdo){
        $this->pdo = $pdo; 
    }

    public function save(Student $student){
        $stmt = $this->pdo->prepare('INSERT INTO students (name, email , role , studentNumber , class , abscencen ) VALUES (:name, :email , :role , :studentNumber , :abscence'); 
        $stmt->execute([
            $student->getName(),
            $student->getEmail(),
            $student->getRole(),
            $student->getStudentNumber(),
            $student->getClass(),
            $student->getAbsence()
        ]); 
        return $student ; 
    }
    
    // explication profonde de la méthode save():
    // 1. on crée une instance de la classe PDO pour se connecter à la base de données, PDO est une classe qui permet de se connecter à la base de données
    // 2. on crée une requête préparée pour insérer les données dans la base de données, la requête préparée permet de sécuriser les données
    // 3. on exécute la requête préparée avec les données de l'étudiant
    // 4. on retourne l'étudiant
    // les getname etc se sont les getters de la classe Student il se trouve  dejat dans le model Student  , on les ai utiliser ici pour recuperer les données de l'étudiant


    public function getAll(){
        $stmt = $this->pdo->query('SELECT * FROM students'); 
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC); 
        $students = []; 
        foreach($rows as $row){
            $students[] = new Student ($row );
        }

        return $students ; 
    }

    // explication profonde de la méthode getAll():
    // 1. on crée une instance de la classe PDO pour se connecter à la base de données, PDO est une classe qui permet de se connecter à la base de données
    // 2. on crée une requête pour sélectionner toutes les données de la table students
    // 3. on exécute la requête
    // 4. on récupère les données sous forme de tableau associatif
    // 5. on crée un tableau pour stocker les étudiants
    // 6. on parcourt le tableau des données et on crée un nouvel étudiant pour chaque ligne
    // 7. on retourne le tableau des étudiants



    public function getById($id){
        $stmt = $this->pdo->prepare('SELECT *FROM students  WHERE id = :id'); 
        $stmt->execute(['id' => $id]); 
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return new Student($row);
    }


    // explication profonde de la méthode getById():
    // 2. on crée une requête préparée pour sélectionner les données de l'étudiant avec l'id passé en paramètre
    // 3. on exécute la requête préparée avec l'id passé en paramètre
    // 4. on récupère les données sous forme de tableau associatif
    // 5. on crée un nouvel étudiant avec les données récupérées
    // 6. on retourne l'étudiant

    // the :id is a placeholder for the id passed in the URL

    public function update(Student $student){
        $stmt =  $this->pdo->prepare('UPDATE students SET name = :name , email = :email , role = :role , studentNumber = :studentNumber , :class = :class , abscence = :abscense WHERE id = :id'); 
        $stmt->execute([
            'name' => $student->getName(),// on utilise les getters de la classe Student pour récupérer les données de l'étudiant
            'email' => $student->getEmail(),
            'role' => $student->getRole(),
            'studentNumber' => $student->getStudentNumber(),
            'class' => $student->getClass(),
            'abscence' => $student->getAbsence(),
            'id' => $student->getId()
        ]);
        return $student ;
    }


    // explication profonde de la méthode update():
    // 2. on crée une requête préparée pour mettre à jour les données de l'étudiant avec l'id passé en paramètre
    // 3. on exécute la requête préparée avec les données de l'étudiant
    // 4. on retourne l'étudiant mis à jour
    // 5. on utilise les getters de la classe Student pour récupérer les données de l'étudiant
    // 6. on utilise les setters de la classe Student pour mettre à jour les données de l'étudiant



    public function delete($id){
        $stmt =$this->pdo->prepare('DELETE FROM students WHERE id = :id'); 
        $stmt->execute(['id' => $id]);
        return true;
    }
}