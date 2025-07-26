<?php
namespace App\Services;
use App\Models\Grades;
use App\Interfaces\INoteService;
use App\Interfaces\ISubject;
use App\Interfaces\IObserver;
use PDO;

class GradeService implements INoteService, ISubject
{
    private $pdo;
    private $observers = [];

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function attach(IObserver $observer): void
    {
        $this->observers[] = $observer;
    }

    public function detach(IObserver $observer): void
    {
        $key = array_search($observer, $this->observers);
        if ($key !== false) {
            unset($this->observers[$key]);
        }
    }

    public function notify(string $event, array $data): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($event, $data);
        }
    }

    public function save(Grades $grade): Grades
    {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare("INSERT INTO grades (evaluation_id, student_id, score) VALUES (:evaluation_id, :student_id, :score)");
            $stmt->execute([
                'evaluation_id' => $grade->getEvaluationId(),
                'student_id' => $grade->getStudentId(),
                'score' => $grade->getScore()
            ]);
            
            $gradeId = $this->pdo->lastInsertId();
            $this->pdo->commit();
            
            $savedGrade = new Grades([
                'id' => $gradeId,
                'evaluation_id' => $grade->getEvaluationId(),
                'student_id' => $grade->getStudentId(),
                'score' => $grade->getScore()
            ]);

            // Notify observers
            $this->notify('grade_created', $savedGrade->toArray());
            
            return $savedGrade;
        } catch (\Exception $e) {
            $this->pdo->rollback();
            throw $e;
        }
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->prepare(
        'SELECT g.id, g.evaluation_id , g.student_id , g.score ,  s.person_id ,
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
    public function getById($id): Grades|null
    {
        $stmt = $this->pdo->prepare(
        'SELECT g.id, g.evaluation_id , g.student_id , g.score , s.person_id , 
        p.first_name , p.last_name  FROM grades g
        JOIN students s ON g.student_id = s.id
        JOIN person p ON s.person_id = p.id 
        WHERE g.id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        return new Grades($row);
    }
    


    public function update(Grades $grade): Grades
    {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare('UPDATE grades SET evaluation_id = :evaluation_id, student_id = :student_id, score = :score WHERE id = :id');
            $stmt->execute([
                'id' => $grade->getId(),
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
    public function delete($id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM grades WHERE id = :id');
        $result = $stmt->execute(['id' => $id]);
        return $result;
    }

    public function getGradesByStudent($studentId): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM grades WHERE student_id = :student_id');
        $stmt->execute(['student_id' => $studentId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $grades = [];
        foreach ($rows as $row) {
            $grades[] = new Grades($row);
        }
        return $grades;
    }

    public function getGradesByEvaluation($evaluationId): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM grades WHERE evaluation_id = :evaluation_id');
        $stmt->execute(['evaluation_id' => $evaluationId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $grades = [];
        foreach ($rows as $row) {
            $grades[] = new Grades($row);
        }
        return $grades;
    }
}
