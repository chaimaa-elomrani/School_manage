<?php

interface IPayable {

    public function getBaseAmount();

    public function applyDiscount($discount);

    public function applyExtraFee($extraFee);

    public function getTotalAmount();

    public function markAsPaid();

    public function getStatus(); 
}