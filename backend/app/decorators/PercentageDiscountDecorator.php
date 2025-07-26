<?php

namespace App\Decorators;

class PercentageDiscountDecorator extends BasePaymentDecorator
{
    private $percentage;
    private $description;

    public function __construct($payment, float $percentage, string $description = "Percentage discount")
    {
        parent::__construct($payment);
        $this->percentage = $percentage;
        $this->description = $description;
    }

    public function getTotalAmount(): float
    {
        $baseTotal = $this->payment->getTotalAmount();
        $discount = $baseTotal * ($this->percentage / 100);
        return $baseTotal - $discount;
    }

    public function getDescription(): string
    {
        return $this->payment->getDescription() . " + {$this->description} ({$this->percentage}%)";
    }

    public function getDiscountAmount(): float
    {
        return $this->payment->getTotalAmount() * ($this->percentage / 100);
    }
}