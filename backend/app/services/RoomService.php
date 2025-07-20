<?php
namespace App\Services;
use App\Models\Room;
use PDO;

class RoomService
{
    private $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Room $room)
    {
        $this->pdo->beginTransaction();

        try {
            $stmt = $this->pdo->prepare('INSERT INTO rooms (number, level) VALUES (:number, :level)');
            $stmt->execute([
                'number' => $room->getNumber(),
                'level' => $room->getLevel()
            ]);
            $this->pdo->commit();
            return $room;
        } catch (\Exception $e) {
            $this->pdo->rollback();
            throw $e;
        }
    }

    public function getAll()
    {
        $stmt = $this->pdo->prepare('SELECT * FROM rooms');
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rooms = [];

        foreach ($rows as $row) {
            $rooms[] = new Room($row);
        }

        return $rooms;
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM rooms WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Room($row);
        }

        return null;
    }

    public function update(Room $room)
    {
        $this->pdo->beginTransaction();

        try {
            $stmt = $this->pdo->prepare('UPDATE rooms SET number = :number , level = :level WHERE id = :id');
            $stmt->execute([
                'id' => $room->getId(),
                'number' => $room->getNumber(),
                'level' => $room->getLevel()
            ]);

            $this->pdo->commit();
            return $room;
        } catch (\Exception $e) {
            $this->pdo->rollback();
            throw $e;
        }
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM rooms WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return true;
    }   
}
