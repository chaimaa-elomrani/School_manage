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
        $stmt = $this->pdo->prepare('INSERT INTO rooms (number, type, disponibility) VALUES (:number, :type, :disponibility)');
        $stmt->execute([
            'number' => $room->getNumber(),
            'type' => $room->getType(),
            'disponibility' => $room->getDisponibility()
        ]);
        
        $roomId = $this->pdo->lastInsertId();
        $roomData = [
            'id' => $roomId,
            'number' => $room->getNumber(),
            'type' => $room->getType(),
            'disponibility' => $room->getDisponibility()
        ];
        
        return new Room($roomData);
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
        $stmt = $this->pdo->prepare('UPDATE rooms SET number = :number, type = :type, disponibility = :disponibility WHERE id = :id');
        $stmt->execute([
            'id' => $room->getId(),
            'number' => $room->getNumber(),
            'type' => $room->getType(),
            'disponibility' => $room->getDisponibility()
        ]);
        return $room;
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM rooms WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }   
}
