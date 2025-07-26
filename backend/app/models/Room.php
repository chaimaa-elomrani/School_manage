<?php

namespace App\Models;

class Room
{
    private $id;
    private $name;
    private $capacity;
    private $type;
    private $equipment;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? '';
        $this->capacity = $data['capacity'] ?? 0;
        $this->type = $data['type'] ?? '';
        $this->equipment = $data['equipment'] ?? '';
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'capacity' => $this->capacity,
            'type' => $this->type,
            'equipment' => $this->equipment
        ];
    }

    // Getters...
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getCapacity() { return $this->capacity; }
}
