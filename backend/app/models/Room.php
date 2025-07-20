<?php

namespace App\Models;
use App\Interfaces\IRoom;

class Room implements IRoom{

    private $id;
    private $name;
    private $level;

    public function __construct(array $data){
        $this->id = $data['id'] ?? null ; 
        $this->name = $data['name'] ?? '';
        $this->level = $data['level'] ?? '';
    }

    public function getId() { 
        return $this->id;
    }
    public function getName() {
        return $this->name;
    }
    public function getLevel() {
        return $this->level;
    }
    public function toArray() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'level' => $this->level
        ];
    }

    public function setName($name) {
        $this->name = $name;
    }
    public function setLevel($level) {
        $this->level = $level;
    }
}