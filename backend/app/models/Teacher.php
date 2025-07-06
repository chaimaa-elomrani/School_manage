<?php

class Teacher implements IPerson, ITeacher{

	private $id;
	private $name;
	private $email;
	private $role;
	private $absence;
	private $salary;
	private $subject;

    public function __construct(array $data){
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->role = $data['role'];
        $this->absence = $data['absence'];
        $this->salary = $data['salary'];
        $this->subject = $data['subject'];
    }

	public function getId() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getEmail() {
		return $this->email;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	public function getRole() {
		return $this->role;
	}

	public function getAbsence() {
		return $this->absence;
	}

	public function setAbsence($absence) {
		$this->absence = $absence;
	}

	public function getSalary() {
		return $this->salary;
	}

	public function setSalary($salaire) {
		$this->salary = $salaire;
	}

	public function getSubject() {
		return $this->subject;
	}

	public function setSubject($subject) {
		$this->subject = $subject;
	}

	public function toArray() {
		return [
			'id' => $this->id,
			'name' => $this->name,
			'email' => $this->email,
			'role' => $this->role,
			'absence' => $this->absence,
			'salary' => $this->salary,
			'subject' => $this->subject
		];
	}
}