<?php
namespace App\Services;
use App\Models\Evaluation;
use PDO;

class EvaluationService
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Evaluation $evaluation)
    {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare("INSERT INTO evaluations (teacher_id, subject_id , title , type, date_evaluation) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                'teacher_id' => $evaluation->getTeacherId(),
                'subject_id' => $evaluation->getSubjectId(),
                'title' => $evaluation->getTitle(),
                'type' => $evaluation->getType(),
                'date_evaluation' => $evaluation->getDate()
            ]);
            $this->pdo->commit();
            return $evaluation;
        } catch (\Exception $e) {
            $this->pdo->rollback();
            throw $e;
        }
    }


    public function getAll()
    {
        $stmt = $this->pdo->prepare(
            'SELECT e.subject_id , e.teacher_id , e.title , e.type , e.date_evaluation,
        s.name , t.first_name , t.last_name 
        FROM evaluations e join subjects s ON e.subject_id = s.id join teachers t ON e.teacher_id = t.id'
        );
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $evaluations = [];
        foreach ($rows as $row) {
            $evaluations[] = new Evaluation($row);
        }
        return $evaluations;
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare(
            'SELECT e.subject_id , e.teacher_id , e.title , e.type , e.date_evaluation,
        s.name , t.first_name , t.last_name FROM evaluations e 
        join subjects s ON e.subject_id = s.id join teachers t ON e.teacher_id = t.id WHERE e.id = :id'
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Evaluation($row);
        }
        return null;
    }


    public function update(Evaluation $evaluation)
    {
        try {
            $stmt = $this->pdo->prepare('UPDATE evaluations SET teacher_id = :teacher_id, subject_id = :subject_id, title = :title, type = :type, date_evaluation = :date_evaluation WHERE id = :id');
            $stmt->execute([
                'teacher_id' => $evaluation->getTeacherId(),
                'subject_id' => $evaluation->getSubjectId(),
                'title' => $evaluation->getTitle(),
                'type' => $evaluation->getType(),
                'date_evaluation' => $evaluation->getDate()
            ]);
            $this->pdo->commit();
            return $evaluation;
        } catch (\Exception $e) {
            $this->pdo->rollback();
            throw $e;
        }
    }

    public function delete($id){
        $stmt = $this->pdo->prepare('DELETE FROM evaluations WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return true;
    }

}
