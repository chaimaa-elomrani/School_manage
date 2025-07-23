<?php

namespace App\Models;
use App\Interfaces\IRoom;

class Room implements IRoom{

    private $id;
    private $number;
    private $type;
    private $disponibility;

    public function __construct(array $data){
        $this->id = $data['id'] ?? null ; 
        $this->number = $data['number'] ?? '';
        $this->type = $data['type'] ?? 'classroom';
        $this->disponibility = $data['disponibility'] ?? 'available';
    }

    public function getId() { 
        return $this->id;
    }
    public function getNumber() {
        return $this->number;
    }
    public function getType() {
        return $this->type;
    }
    public function getDisponibility() {
        return $this->disponibility;
    }
    public function getLevel() { 
        // Map type to level for strategy pattern
        switch($this->type) {
            case 'classroom': return 'beginner';
            case 'lab': return 'intermediate';
            case 'auditorium': return 'advanced';
            default: return 'beginner';
        }
    }
    public function toArray() {
        return [
            'id' => $this->id,
            'number' => $this->number,
            'type' => $this->type,
            'disponibility' => $this->disponibility
        ];
    }

  
}