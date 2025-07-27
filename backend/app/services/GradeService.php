<?php

namespace App\Services;

class GradeService
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->prepare('
            SELECT g.*, e.title as evaluation_title, 
                   c.name as course_name,
                   CONCAT(p.first_name, " ", p.last_name) as student_name
            FROM grades g 
            LEFT JOIN evaluations e ON g.evaluation_id = e.id
            LEFT JOIN courses c ON e.subject_id = c.subject_id
            LEFT JOIN students s ON g.student_id = s.id
            LEFT JOIN person p ON s.person_id = p.id
            ORDER BY g.created_at DESC
        '); 
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM grades WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO grades (student_id, evaluation_id, score, created_at) 
            VALUES (?, ?, ?, NOW())
        ');
        $stmt->execute([
            $data['student_id'],
            $data['evaluation_id'],
            $data['score']
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare('
            UPDATE grades 
            SET student_id = ?, evaluation_id = ?, score = ?, updated_at = NOW()
            WHERE id = ?
        ');
        return $stmt->execute([
            $data['student_id'],
            $data['evaluation_id'],
            $data['score'],
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM grades WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
