<?php

namespace App\Decorators;

class LateFeeDecorator extends BasePaymentDecorator
{
    private $lateFee;
    private $description;

    public function __construct($payment, float $lateFee, string $description = "Late payment fee")
    {
        parent::__construct($payment);
        $this->lateFee = $lateFee;
        $this->description = $description;
    }

    public function getTotalAmount(): float
    {
        return $this->payment->getTotalAmount() + $this->lateFee;
    }

    public function getDescription(): string
    {
        return $this->payment->getDescription() . " + {$this->description} (+{$this->lateFee})";
    }

    public function getLateFee(): float
    {
        return $this->lateFee;
    }
}