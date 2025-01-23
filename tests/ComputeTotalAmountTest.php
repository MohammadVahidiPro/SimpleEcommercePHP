<?php

require_once __DIR__ . '/../utils/util.php';

use PHPUnit\Framework\TestCase;


class ComputeTotalAmountTest extends TestCase
{
    /**
     * Test calculation with valid inputs
     */
    public function testValidInputs()
    {
        $this->assertEquals(110.00, compute_total_amount(10, 10));
        $this->assertEquals(121.00, compute_total_amount(10, 10, 21));
    }

    /**
     * Test calculation with zero quantity
     */
    public function testZeroQuantity()
    {
        $this->assertEquals(0.00, compute_total_amount(0, 10));
    }

    /**
     * Test calculation with zero price
     */
    public function testZeroPrice()
    {
        $this->assertEquals(0.00, compute_total_amount(10, 0));
    }

    /**
     * Test calculation with zero profit
     */
    public function testZeroProfit()
    {
        $this->assertEquals(100.00, compute_total_amount(10, 10, 0));
    }

    /**
     * Test calculation with fractional quantity and price
     */
    public function testFractionalInputs()
    {
        $this->assertEquals(55.14, compute_total_amount(2.5, 20.05, 10));
    }

    /**
     * Test calculation with large numbers
     */
    public function testLargeNumbers()
    {
        $this->assertEquals(11000000.00, compute_total_amount(1000000, 10));
    }

    /**
     * Test negative quantity throws exception
     */
    public function testNegativeQuantity()
    {
        $this->expectException(InvalidArgumentException::class);
        compute_total_amount(-1, 10);
    }

    /**
     * Test negative price throws exception
     */
    public function testNegativePrice()
    {
        $this->expectException(InvalidArgumentException::class);
        compute_total_amount(10, -10);
    }

    /**
     * Test negative profit throws exception
     */
    public function testNegativeProfit()
    {
        $this->expectException(InvalidArgumentException::class);
        compute_total_amount(10, 10, -10);
    }

    /**
     * Test invalid inputs throw exceptions
     */
    public function testInvalidInputs()
    {
        $this->expectException(InvalidArgumentException::class);
        compute_total_amount('invalid', 10);

        $this->expectException(InvalidArgumentException::class);
        compute_total_amount(10, 'invalid');

        $this->expectException(InvalidArgumentException::class);
        compute_total_amount(10, 10, 'invalid');
    }
}
