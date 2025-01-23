<?php



function compute_total_amount($quantity, $price, $profit = 10): float
{
    if (!is_numeric($quantity) || $quantity < 0) {
        throw new InvalidArgumentException("The quantity must be a non-negative numeric value.");
    }
    if (!is_numeric($price) || $price < 0) {
        throw new InvalidArgumentException("The price must be a non-negative numeric value.");
    }
    if (!is_numeric($profit) || $profit < 0) {
        throw new InvalidArgumentException("The profit must be a non-negative numeric value.");
    }

    $cost = round($quantity * $price, 2);
    $profitAmount = round($cost * ($profit / 100), 2);
    $totalAmount = round($cost + $profitAmount, 2);

    return $totalAmount;
}


function formatMoney($amount)
{
    if (!is_numeric($amount)) {
        throw new InvalidArgumentException("The input must be a numeric value.");
    }

    // Convert to float and format as money
    $amount = (float)$amount;
    return '$' . number_format($amount, 2, '.', ',');
}
