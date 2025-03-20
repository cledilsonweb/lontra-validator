<?php

use Lontra\Validator\Exception\ClassNotFoundException;
use Lontra\Validator\StartWith;
use Lontra\Validator\Validator;
use PHPUnit\Framework\TestCase;

/**
 * 
 */
class ValidatorTest extends TestCase
{

    /**
     * @return void
     */
    public function testIsValid()
    {
        $this->assertTrue(Validator::isValid(StartWith::class, 'start with start', 'start'));
        $this->assertFalse(Validator::isValid(StartWith::class, 'doesnt start with start', 'start'));
    }

    /**
     * @return void
     */
    public function testNamedValidation()
    {
        $this->assertTrue(Validator::validateStartWith('start with start', 'start'));
        $this->assertFalse(Validator::validateStartWith('doesnt start with start', 'start'));
    }

    public function testNamedValidationException()
    {
        $this->expectException(ClassNotFoundException::class);
        Validator::validateFakeValidationClass('fake value', 'fake option');
    }

    public function testNamedValidationCallException()
    {
        $this->expectException(BadMethodCallException::class);
        Validator::fakeFunctionCall('fake value', 'fake option');
    }
}