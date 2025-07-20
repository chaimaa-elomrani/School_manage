<?php
namespace App\Interfaces;

interface IRoom{

    public function getId();
    public function getName();
    public function getLevel(); 

    public function setName($name);
    public function setLevel($level);
}