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

    public function save(Room $room): array
    {
        $stmt = $this->pdo->prepare("INSERT INTO rooms (name, capacity, type) VALUES (?, ?, ?)");
        $stmt->execute([$room->getName(), $room->getCapacity(), $room->getType()]);
        
        return ['id' => $this->pdo->lastInsertId(), 'message' => 'Room saved successfully'];
    }

    public function getById(int $id): ?Room
    {
        $stmt = $this->pdo->prepare("SELECT * FROM rooms WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch();
        
        return $data ? new Room($data) : null;
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM rooms");
        $rooms = [];
        
        while ($row = $stmt->fetch()) {
            $rooms[] = new Room($row);
        }
        
        return $rooms;
    }
}
