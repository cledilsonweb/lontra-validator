<?php

use Lontra\Validator\DateBetween;
use Lontra\Validator\Exception\InvalidDateException;
use PHPUnit\Framework\TestCase;

/**
 * Class DateBetweenTest
 */
class DateBetweenTest extends TestCase
{

    /**
     * Basic use of DateBetween
     *
     * @return void
     */
    public function testDateBetweenOptions()
    {
        $options = [
            'min'       => '2020-05-05',
            'max'       => '2020-06-05',
            'format'    => 'Y-m-d',
            'inclusive' => false,
        ];
        $validator = new DateBetween($options);

        $optionsValidator = [
            'min'       => $validator->getMin(),
            'max'       => $validator->getMax(),
            'format'    => $validator->getFormat(),
            'inclusive' => $validator->getInclusive(),
        ];

        $this->assertSame($options, $optionsValidator);
    }

    /**
     * Basic use of DateBetween
     * 
     * @dataProvider getData
     * @param        mixed $min
     * @param        mixed $max
     * @param        mixed $format
     * @param        mixed $inclusive
     * @param        mixed $date
     * @param        mixed $expected
     * 
     * @return void
     */
    public function testDateBetween($min, $max, $format, $inclusive, $date, $expected)
    {
        $validator = new DateBetween(
            [
                'min'       => $min,
                'max'       => $max,
                'format'    => $format,
                'inclusive' => $inclusive
            ]
        );

        $this->assertEquals($expected, $validator->isValid($date));
    }

    /**
     * @dataProvider getDataExceptions
     * @param        mixed $min
     * @param        mixed $max
     * @param        mixed $format
     * @param        mixed $inclusive
     * @param        mixed $date
     * 
     * @return void
     */
    public function testDateBetweenExceptions($min, $max, $format, $inclusive, $date)
    {

        $this->expectException(InvalidDateException::class);

        $validator = new DateBetween(
            [
                'min'       => $min,
                'max'       => $max,
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
            'isDate_Valid_true'                     => ['2020-05-05', '2020-06-05', 'Y-m-d', true, '2020-05-05', true],
            'isDate_Valid_false'                    => ['2020-05-05', '2020-06-05', 'Y-m-d', true, '2020-25-05', false],
            'isDate_Valid_InclusiveFalse_true'      => ['2020-05-05', '2020-06-05', 'Y-m-d', false, '2020-05-25', true],
            'isDate_Valid_InclusiveFalse_false'     => ['2020-05-05', '2020-06-05', 'Y-m-d', false, '2020-05-05', false],
            'isDate_Valid_DateFormat_false'         => ['2020-05', '2020-06', 'Y-m', true, '2020-05-25', false],
        ];
    }

    /**
     * @return void
     */
    public function getDataExceptions()
    {
        return [
            'isDate_Min_InvalidDateException'      => ['2020-06', '2020-06-05', 'Y-m-d', true, '2020-05-05'],
            'isDate_Max_InvalidDateException'      => ['2020-05-05', '2020-06', 'Y-m-d', true, '2020-55-25'],
        ];
    }
}
