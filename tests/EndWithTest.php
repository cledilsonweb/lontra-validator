<?php

use Lontra\Validator\EndWith;
use PHPUnit\Framework\TestCase;

/**
 * Class EndWithTest
 */
class EndWithTest extends TestCase
{

    /**
     * Basic use of EndWith
     *
     * @return void
     */
    public function testEndWithOptions()
    {
        $options = [
            'end' => 'end',
            'caseSensitive' => true,
        ];
        $validator = new EndWith($options);

        $optionsValidator = [
            'end' => $validator->getEnd(),
            'caseSensitive' => $validator->getCaseSensitive(),
        ];

        $this->assertSame($options, $optionsValidator);
    }

    /**
     * @return void
     */
    public function testEndWithStringParamOptions()
    {
        $validator = new EndWith('end');

        $this->assertTrue($validator->isValid('end with end'));
        $this->assertFalse($validator->isValid('doesnt end with end...'));
    }

    /**
     * Basic use of EndWith
     * 
     * @dataProvider getData
     * @param        mixed $end
     * @param        mixed $case
     * @param        mixed $value
     * @param        mixed $expected
     *
     * @return void
     */
    public function testEndWith($end, $case, $value, $expected)
    {
        $validator = new EndWith(
            [
                'end' => $end,
                'caseSensitive' => $case,
            ]
        );

        $this->assertEquals($expected, $validator->isValid($value), implode(' | ', $validator->getMessages()));
    }

    /**
     * @return void
     */
    public function getData()
    {
        return [
            'isEndWith_true' => [
                'end' => 'amet.',
                'caseSensitive' => false,
                'value' => 'Lorem ipsum dolor sit amet.',
                'expected' => true,
            ],
            'isEndWith_CaseTrue_true' => [
                'end' => 'Amet.',
                'caseSensitive' => true,
                'value' => 'Lorem ipsum dolor sit Amet.',
                'expected' => true,
            ],
            'isEndWith_Special_true' => [
                'end' => '#amet.',
                'caseSensitive' => false,
                'value' => 'Lorem ipsum dolor sit #amet.',
                'expected' => true,
            ],
            'isEndWith_Special_2_true' => [
                'end' => 'âmet.',
                'caseSensitive' => false,
                'value' => 'Lôrem ipsum dolor sit âmet.',
                'expected' => true,
            ],
            'isEndWith_Special_CaseTrue_true' => [
                'end' => 'Âmet.',
                'caseSensitive' => true,
                'value' => 'Lôrem ipsum dolor sit Âmet.',
                'expected' => true,
            ],
        ];
    }
}
