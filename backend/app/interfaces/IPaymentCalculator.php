<?php

namespace App\Interfaces;

interface IPaymentCalculator
{
    public function getBaseAmount(): float;
    public function getTotalAmount(): float;
}