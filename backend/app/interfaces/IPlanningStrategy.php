<?php

namespace App\Interfaces;

interface IPlanningStrategy {

    public function plan(Corse $corse , Salle $salle , DateTime $date): bool; 

    public function cancelPlan(Corse $corse , Salle $salle , DateTime $date): bool;
    
    

}