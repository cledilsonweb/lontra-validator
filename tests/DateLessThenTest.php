<?php

use Lontra\Validator\DateLessThan;
use Lontra\Validator\Exception\InvalidDateException;
use PHPUnit\Framework\TestCase;

/**
 * Class DateLessThanTest
 */
class DateLessThanTest extends TestCase
{

    /**
     * Basic use of DateLessThan
     *
     * @return void
     */
    public function testDateLessThanOptions()
    {
        $options = [
            'max'       => '2020-06-05',
            'format'    => 'Y-m-d',
            'inclusive' => false,
        ];
        $validator = new DateLessThan($options);

        $optionsValidator = [
            'max'       => $validator->getMax(),
            'format'    => $validator->getFormat(),
            'inclusive' => $validator->getInclusive(),
        ];

        $this->assertSame($options, $optionsValidator);
    }

    /**
     * Basic use of DateLessThan
     * 
     * @dataProvider getData
     * @param        mixed $max
     * @param        mixed $format
     * @param        mixed $inclusive
     * @param        mixed $date
     * @param        mixed $expected
     *
     * @return void
     */
    public function testDateLessThan($max, $format, $inclusive, $date, $expected)
    {
        $validator = new DateLessThan(
            [
                'max'       => $max,
                'format'    => $format,
                'inclusive' => $inclusive
            ]
        );

        $this->assertEquals($expected, $validator->isValid($date));
    }

    /**
     * @dataProvider getDataExceptions
     * @param        mixed $max
     * @param        mixed $format
     * @param        mixed $inclusive
     * @param        mixed $date
     *
     * @return void
     */
    public function testDateLessThanExceptions($max, $format, $inclusive, $date)
    {
        $this->expectException(InvalidDateException::class);

        $validator = new DateLessThan(
            [
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
            'isDate_Valid_true'                     => ['2020-05-05', 'Y-m-d', true, '2019-03-03', true],
            'isDate_Now_Valid_true'                 => ['now', 'Y-m-d', true, '2019-03-03', true],
            'isDate_Valid_Inclusive_true'           => ['2020-05-05', 'Y-m-d', true, '2020-05-05', true],
            'isDate_Valid_false'                    => ['2020-05-05', 'Y-m-d', true, '2020-25-05', false],
            'isDate_Valid_InclusiveFalse_true'      => ['2020-05-05', 'Y-m-d', false, '2020-05-04', true],
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
            'isDate_Max_InvalidDateException'      => ['2020-06', 'Y-m-d', true, '2020-55-25'],
        ];
    }
}
