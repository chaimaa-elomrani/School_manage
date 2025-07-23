<?php
namespace App\Services;
use App\Models\Grades;
use PDO;

class GradeService
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Grades $grade)
    {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare("INSERT INTO grades (evaluation_id, student_id , score) VALUES (?, ?, ?)");
            $stmt->execute([
                'evaluation_id' => $grade->getEvaluationId(),
                'student_id' => $grade->getStudentId(),
                'score' => $grade->getScore()
            ]);
            $this->pdo->commit();
            return $grade;
        } catch (\Exception $e) {
            $this->pdo->rollback();
            throw $e;
        }
    }

    public function getAll(){
        $stmt = $this->pdo->prepare(
        'SELECT g.evaluation_id , g.student_id , g.score ,  s.person_id ,
         p.first_name , p.last_name FROM grades g
         JOIN students s ON g.student_id = s.id
         JOIN person p ON s.person_id = p.id');
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $grades = [];
        foreach ($rows as $row) {
            $grades[] = new Grades($row);
        }
        return $grades;
    }

    public function getById($id){
        $stmt = $this->pdo->prepare(
        'SELECT g.evaluation_id , g.student_id , g.score , s.person_id , 
        p.first_name , p.last_name  FROM grades g
        JOIN students s ON g.student_id = s.id
        JOIN person p ON s.person_id = p.id 
        WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        return new Grades($row);
    }


    public function update(Grades $grade)
    {
        try {
            $stmt = $this->pdo->prepare('UPDATE grades SET evaluation_id = :evaluation_id, student_id = :student_id, score = :score WHERE id = :id');
            $stmt->execute([
                'id' => $grade->getId(),
                'evaluation_id' => $grade->getEvaluationId(),
                'student_id' => $grade->getStudentId(),
                'score' => $grade->getScore()
            ]);
            return $grade;
        } catch (\Exception $e) {
            throw $e;
        }
    }


    public function delete($id){
        $stmt = $this->pdo->prepare('DELETE FROM grades WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return true;
    }
}