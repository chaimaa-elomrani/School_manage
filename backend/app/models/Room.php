<?php

namespace App\Models;
use App\Interfaces\IRoom;

class Room implements IRoom{

    private $id;
    private $number;
    private $level;

    public function __construct(array $data){
        $this->id = $data['id'] ?? null ; 
        $this->number = $data['number'] ?? '';
        $this->level = $data['level'] ?? '';
    }

    public function getId() { 
        return $this->id;
    }
    public function getNumber() {
        return $this->number;
    }
    public function getLevel() {
        return $this->level;
    }
    public function toArray() {
        return [
            'id' => $this->id,
            'number' => $this->number,
            'level' => $this->level
        ];
    }

  
}