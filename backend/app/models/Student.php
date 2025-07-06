<?php

class Student implements IPerson, IStudent{

	private $id;
	private $name;
	private $email;
	private $role;
	private $studentNumber;
	private $class;
	private $absence;


    public function __construct(array $data){
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->role = $data['role'];
        $this->studentNumber = $data['studentNumber'];
        $this->class = $data['class'];
        $this->absence = $data['absence'];
    }


	public function getAbsence() {
		return $this->absence;
	}

	public function setAbsence($absence) {
		$this->absence = $absence;
	}

	public function getClass() {
		return $this->class;
	}

	public function setClass($class) {
		$this->class = $class;
	}

	public function getStudentNumber() {
		return $this->studentNumber;
	}

	public function setStudentNumber($studentNumber) {
		$this->studentNumber = $studentNumber;
	}

	public function getEmail() {
		return $this->email;
	}

	public function setEmail($email) {
		$this->email = $email;
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

	public function getRole() {
		return $this->role;
	}

	public function toArray() {
		return [
			'id' => $this->id,
			'name' => $this->name,
			'email' => $this->email,
			'role' => $this->role,
			'studentNumber' => $this->studentNumber,
			'class' => $this->class,
			'absence' => $this->absence,
		];
	}
}