<?php

use Lontra\Validator\StartWith;
use PHPUnit\Framework\TestCase;

/**
 * Class StartWithTest
 */
class StartWithTest extends TestCase
{

    /**
     * Basic use of StartWith
     *
     * @return void
     */
    public function testStartWithOptions()
    {
        $options = [
            'start' => 'start',
            'caseSensitive' => true,
        ];
        $validator = new StartWith($options);

        $optionsValidator = [
            'start' => $validator->getStart(),
            'caseSensitive' => $validator->getCaseSensitive(),
        ];

        $this->assertSame($options, $optionsValidator);
    }

    /**
     * @return void
     */
    public function testStartWithStringParamOptions()
    {
        $validator = new StartWith('start');

        $this->assertTrue($validator->isValid('start with start'));
        $this->assertFalse($validator->isValid('doesnt start with start'));
    }

    /**
     * Basic use of StartWith
     * 
     * @dataProvider getData
     * @param        mixed $start
     * @param        mixed $case
     * @param        mixed $value
     * @param        mixed $expected
     *
     * @return void
     */
    public function testStartWith($start, $case, $value, $expected)
    {
        $validator = new StartWith(
            [
                'start' => $start,
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
            'isStartWith_true' => [
                'start' => 'lorem',
                'caseSensitive' => false,
                'value' => 'Lorem ipsum dolor sit amet.',
                'expected' => true,
            ],
            'isStartWith_CaseTrue_true' => [
                'start' => 'Lorem',
                'caseSensitive' => true,
                'value' => 'Lorem ipsum dolor sit amet.',
                'expected' => true,
            ],
            'isStartWith_Special_true' => [
                'start' => '#lorem',
                'caseSensitive' => false,
                'value' => '#Lorem ipsum dolor sit amet.',
                'expected' => true,
            ],
            'isStartWith_Special_2_true' => [
                'start' => 'lôrem',
                'caseSensitive' => false,
                'value' => 'Lôrem ipsum dolor sit amet.',
                'expected' => true,
            ],
            'isStartWith_Special_CaseTrue_true' => [
                'start' => 'Lôrem',
                'caseSensitive' => true,
                'value' => 'Lôrem ipsum dolor sit amet.',
                'expected' => true,
            ],
        ];
    }
}
