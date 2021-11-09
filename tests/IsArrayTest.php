<?php

use Lontra\Validator\IsArray;
use PHPUnit\Framework\TestCase;

/**
 * Class IsArrayTest
 */
class IsArrayTest extends TestCase
{

    /**
     * Basic use of IsArray
     *
     * @return void
     */
    public function testIsArrayOptions()
    {
        $options = [
            'emptyIsValid' => true,
            'nullIsValid' => true,
        ];
        $validator = new IsArray($options);

        $optionsValidator = [
            'emptyIsValid' => $validator->getEmptyIsValid(),
            'nullIsValid' => $validator->getNullIsValid(),
        ];

        $this->assertSame($options, $optionsValidator);
    }

    /**
     * @return void
     */
    public function testIsArrayBoolParamOptions()
    {
        $validator = new IsArray(true);

        $this->assertTrue($validator->isValid([]));
        $this->assertTrue($validator->isValid(['A', 'B', 'C']));
        $this->assertFalse($validator->isValid('its not an array'));
    }

    /**
     * @return void
     */
    public function testIsArrayParamsOptions()
    {
        $validator = new IsArray([
            'emptyIsValid' => true,
            'nullIsValid' => true,
        ]);

        $this->assertTrue($validator->isValid([]), implode(' | ', $validator->getMessages()));
        $this->assertTrue($validator->isValid(null), implode(' | ', $validator->getMessages()));
        $this->assertTrue($validator->isValid(['A', 'B', 'C']), implode(' | ', $validator->getMessages()));
        $this->assertFalse($validator->isValid('its not an array'), implode(' | ', $validator->getMessages()));
    }

    /**
     * Basic use of IsArray
     *
     * @return void
     */
    public function testIsArrayNoParams()
    {
        $validator = new IsArray();

        $this->assertFalse(
            $validator->isValid(null),
            implode(' | ', $validator->getMessages())
        );

        $this->assertFalse(
            $validator->isValid(''),
            implode(' | ', $validator->getMessages())
        );

        $this->assertFalse(
            $validator->isValid([]),
            implode(' | ', $validator->getMessages())
        );

        $this->assertTrue(
            $validator->isValid(['A', 'B', 'C']),
            implode(' | ', $validator->getMessages())
        );
    }
}
