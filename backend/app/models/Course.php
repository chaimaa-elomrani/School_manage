<?php

namespace App\Models;

class Course
{
    private $id;
    private $name;
    private $code;
    private $credits;
    private $description;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? '';
        $this->code = $data['code'] ?? '';
        $this->credits = $data['credits'] ?? 0;
        $this->description = $data['description'] ?? '';
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'credits' => $this->credits,
            'description' => $this->description
        ];
    }

    // Getters and setters...
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getCode() { return $this->code; }

     public function getCredits()
    {
        return $this->credits;
    }

    public function getDescription()
    {
        return $this->description;
    }
}
