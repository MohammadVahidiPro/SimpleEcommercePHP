<?php
require_once __DIR__ . '/../utils/util.php';

use PHPUnit\Framework\TestCase;

class FormatMoneyTest extends TestCase
{
    /**
     * Test formatting positive integers
     */
    public function testPositiveInteger()
    {
        $this->assertEquals('$1,000.00', formatMoney(1000));
    }

    /**
     * Test formatting positive floats
     */
    public function testPositiveFloat()
    {
        $this->assertEquals('$1,234.56', formatMoney(1234.56));
    }

    /**
     * Test formatting negative integers
     */
    public function testNegativeInteger()
    {
        $this->assertEquals('$-1,000.00', formatMoney(-1000));
    }

    /**
     * Test formatting negative floats
     */
    public function testNegativeFloat()
    {
        $this->assertEquals('$-1,234.56', formatMoney(-1234.56));
    }

    /**
     * Test formatting zero
     */
    public function testZero()
    {
        $this->assertEquals('$0.00', formatMoney(0));
    }

    /**
     * Test formatting a number string
     */
    public function testNumberString()
    {
        $this->assertEquals('$1,000.00', formatMoney('1000'));
    }

    /**
     * Test formatting a float string
     */
    public function testFloatString()
    {
        $this->assertEquals('$1,234.56', formatMoney('1234.56'));
    }

    /**
     * Test formatting with large numbers
     */
    public function testLargeNumber()
    {
        $this->assertEquals('$1,000,000,000.00', formatMoney(1000000000));
    }

    /**
     * Test formatting with small decimals
     */
    public function testSmallDecimal()
    {
        $this->assertEquals('$0.01', formatMoney(0.01));
    }

    /**
     * Test invalid input throws exception
     */
    public function testInvalidInput()
    {
        $this->expectException(InvalidArgumentException::class);
        formatMoney('invalid');
    }
}
