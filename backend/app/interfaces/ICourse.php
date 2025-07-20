<?php
namespace App\Interfaces; 

interface ICourse{

    // getters
    public function getId();
    public function getTeacherId(); 
    public function getSubjectId();
    public function getRoomId();
    public function getDuration();
    public function getLevel();
    public function getCourseStartDate();
    public function getCourseEndDate();

    // setters 
    public function setDuration($duration);
    public function setLevel($level);
    public function setCourseStartDate($courseStartDate);
    public function setCourseEndDate($courseEndDate);
}