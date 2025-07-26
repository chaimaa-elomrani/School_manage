<?php

namespace App\Models;

use App\Interfaces\IPayable;

class SalaireEnseignant implements IPayable
{
    private $id;
    private $teacher_id;
    private $month;
    private $year;
    private $amount;
    private $payment_date;
    private $status;
    private $bonus = 0;
    private $deduction = 0;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? null;
        $this->teacher_id = $data['teacher_id'] ?? null;
        $this->month = $data['month'] ?? null;
        $this->year = $data['year'] ?? null;
        $this->amount = $data['amount'] ?? 0;
        $this->payment_date = $data['payment_date'] ?? null;
        $this->status = $data['status'] ?? 'pending';
        $this->bonus = $data['bonus'] ?? 0;
        $this->deduction = $data['deduction'] ?? 0;
    }

    public function getBaseAmount(): float
    {
        return (float) $this->amount;
    }

    public function applyDiscount(float $discount): void
    {
        $this->deduction = $discount;
    }

    public function applyExtraFee(float $extraFee): void
    {
        $this->bonus = $extraFee;
    }

    public function getTotalAmount(): float
    {
        return $this->getBaseAmount() + $this->bonus - $this->deduction;
    }

    public function markAsPaid(): void
    {
        $this->status = 'paid';
        $this->payment_date = date('Y-m-d');
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getTeacherId() { return $this->teacher_id; }
    public function getMonth() { return $this->month; }
    public function getYear() { return $this->year; }
    public function getPaymentDate() { return $this->payment_date; }
    public function getBonus() { return $this->bonus; }
    public function getDeduction() { return $this->deduction; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'teacher_id' => $this->teacher_id,
            'month' => $this->month,
            'year' => $this->year,
            'amount' => $this->amount,
            'bonus' => $this->bonus,
            'deduction' => $this->deduction,
            'total_amount' => $this->getTotalAmount(),
            'payment_date' => $this->payment_date,
            'status' => $this->status
        ];
    }
}