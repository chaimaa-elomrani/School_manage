<?php

use App\Models\PaiementEleve;
use App\Decorators\PercentageDiscountDecorator;
use App\Decorators\LateFeeDecorator;
use App\Decorators\TaxDecorator;

// Base payment
$payment = new PaiementEleve([
    'student_id' => 1,
    'fee_id' => 1,
    'amount' => 1000
]);

// Apply 10% student discount
$discountedPayment = new PercentageDiscountDecorator($payment, 10, "Student discount");

// Add late fee
$withLateFee = new LateFeeDecorator($discountedPayment, 50, "Late payment penalty");

// Add tax
$finalPayment = new TaxDecorator($withLateFee, 5, "VAT");

echo "Base amount: " . $payment->getBaseAmount() . "\n";
echo "Final amount: " . $finalPayment->getTotalAmount() . "\n";
echo "Description: " . $finalPayment->getDescription() . "\n";