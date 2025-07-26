<?php

namespace App\Models;

class User
{
    private $id;
    private $first_name;
    private $last_name;
    private $email;
    private $phone;
    private $role;
    private $password;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->first_name = $data['first_name'] ?? '';
        $this->last_name = $data['last_name'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->phone = $data['phone'] ?? '';
        $this->role = $data['role'] ?? 'admin';
        $this->password = $data['password'] ?? '';
    }

    // Getters
    public function getId() { return $this->id; }
    public function getFirstName() { return $this->first_name; }
    public function getLastName() { return $this->last_name; }
    public function getEmail() { return $this->email; }
    public function getPhone() { return $this->phone; }
    public function getRole() { return $this->role; }
    public function getPassword() { return $this->password; }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setPassword($password) { $this->password = password_hash($password, PASSWORD_DEFAULT); }

    public function verifyPassword($password): bool
    {
        return password_verify($password, $this->password);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->role
        ];
    }
}
