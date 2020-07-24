<?php

use Lontra\Validator\DateGreaterThan;
use Lontra\Validator\Exception\InvalidDateException;
use PHPUnit\Framework\TestCase;

/**
 * Class DateGreaterThanTest
 */
class DateGreaterThanTest extends TestCase
{

    /**
     * Basic use of DateGreaterThan
     *
     * @return void
     */
    public function testDateGreaterThanOptions()
    {
        $options = [
            'min'       => '2020-06-05',
            'format'    => 'Y-m-d',
            'inclusive' => false,
        ];
        $validator = new DateGreaterThan($options);

        $optionsValidator = [
            'min'       => $validator->getMin(),
            'format'    => $validator->getFormat(),
            'inclusive' => $validator->getInclusive(),
        ];

        $this->assertSame($options, $optionsValidator);
    }

    /**
     * Basic use of DateGreaterThan
     * 
     * @dataProvider getData
     * @param        mixed $min
     * @param        mixed $format
     * @param        mixed $inclusive
     * @param        mixed $date
     * @param        mixed $expected
     *
     * @return void
     */
    public function testDateGreaterThan($min, $format, $inclusive, $date, $expected)
    {
        $validator = new DateGreaterThan(
            [
                'min'       => $min,
                'format'    => $format,
                'inclusive' => $inclusive
            ]
        );

        $this->assertEquals($expected, $validator->isValid($date));
    }

    /**
     * @dataProvider getDataExceptions
     * @param        mixed $min
     * @param        mixed $format
     * @param        mixed $inclusive
     * @param        mixed $date
     *
     * @return void
     */
    public function testDateGreaterThanExceptions($min, $format, $inclusive, $date)
    {
        $this->expectException(InvalidDateException::class);

        $validator = new DateGreaterThan(
            [
                'min'       => $min,
                'format'    => $format,
                'inclusive' => $inclusive
            ]
        );
        $validator->isValid($date);
    }

    /**
     * @return void
     */
    public function getData()
    {
        return [
            'isDate_Valid_true'                     => ['2020-05-05', 'Y-m-d', true, '2020-05-05', true],
            'isDate_Now_Valid_true'                 => ['now', 'Y-m-d', true, '2030-05-18', true],
            'isDate_Valid_false'                    => ['2020-05-05', 'Y-m-d', true, '2020-25-05', false],
            'isDate_Valid_InclusiveFalse_true'      => ['2020-05-05', 'Y-m-d', false, '2020-05-06', true],
            'isDate_Valid_InclusiveFalse_false'     => ['2020-05-05', 'Y-m-d', false, '2020-05-05', false],
            'isDate_Valid_DateFormat_false'         => ['2020-05', 'Y-m', true, '2020-05-25', false],
        ];
    }

    /**
     * @return void
     */
    public function getDataExceptions()
    {
        return [
            'isDate_Min_InvalidDateException'      => ['2020-06', 'Y-m-d', true, '2020-55-25'],
        ];
    }
}
