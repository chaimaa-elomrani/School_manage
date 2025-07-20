<?php
namespace App\Interfaces; 

interface ICourse{

    public function getTeacherId(); 
    public function getCourseId();
    public function getSubjectId();
    public function getCourseStartDate();
    public function getCourseEndDate();
    public function getClassId();
}