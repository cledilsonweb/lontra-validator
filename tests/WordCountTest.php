<?php

use Lontra\Validator\WordCount;
use PHPUnit\Framework\TestCase;

/**
 * Class WordCountTest
 */
class WordCountTest extends TestCase
{

    /**
     * Basic use of WordCount
     *
     * @return void
     */
    public function testWordCountOptions()
    {
        $options = [
            'min' => 1,
            'max' => 1,
            'total' => 1,
        ];
        $validator = new WordCount($options);

        $optionsValidator = [
            'min' => $validator->getMin(),
            'max' => $validator->getMax(),
            'total' => $validator->getTotal(),
        ];

        $this->assertSame($options, $optionsValidator);
    }

    /**
     * @return void
     */
    public function testWordCountIntParamOptions()
    {
        $validator = new WordCount(2);

        $this->assertTrue($validator->isValid('Two words'));
        $this->assertFalse($validator->isValid('Greater than two words'));
    }

    /**
     * Basic use of WordCount
     * 
     * @dataProvider getData
     * @param        mixed $min
     * @param        mixed $max
     * @param        mixed $total
     * @param        mixed $value
     * @param        mixed $expected
     *
     * @return void
     */
    public function testWordCount($min, $max, $total, $value, $expected)
    {
        $validator = new WordCount(
            [
                'min' => $min,
                'max' => $max,
                'total' => $total,
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
            'isWordCount_true' => [
                'min' => 1,
                'max' => 5,
                'total' => 0,
                'value' => 4,
                'expected' => true,
            ],
            'isWordCount_Total_false' => [
                'min' => 1,
                'max' => 5,
                'total' => 3,
                'value' => 'Lorem ipsum dolor sit amet',
                'expected' => false,
            ],
            'isWordCount_Total_Special_true' => [
                'min' => 1,
                'max' => 5,
                'total' => 5,
                'value' => 'Lorem ipsum dolor sit amet.',
                'expected' => true,
            ],
            'isWordCount_Total_Special2_true' => [
                'min' => 1,
                'max' => 5,
                'total' => 6,
                'value' => 'Lorem ipsum dôlor sit ãmet@ipsum',
                'expected' => true,
            ],
            'isWordCount_Total_Special_Email_true' => [
                'min' => 1,
                'max' => 5,
                'total' => 5,
                'value' => 'Lorem ipsum dôlor sit amet@ipsum',
                'expected' => true,
            ],
            'isWordCount_Min_Special_Email_true' => [
                'min' => 5,
                'max' => 0,
                'total' => 0,
                'value' => 'Lorem ipsum dôlor sit amet@ipsum at',
                'expected' => true,
            ],
            'isWordCount_Min_Special_Email_true' => [
                'min' => 6,
                'max' => 0,
                'total' => 0,
                'value' => 'Lorem ipsum dôlor sit amet@ipsum at',
                'expected' => true,
            ],
            'isWordCount_Min_Special_Email_false' => [
                'min' => 7,
                'max' => 0,
                'total' => 0,
                'value' => 'Lorem ipsum dôlor sit amet@ipsum at',
                'expected' => false,
            ],
            'isWordCount_Min_Special_Email_apostrophe_true' => [
                'min' => 7,
                'max' => 0,
                'total' => 0,
                'value' => 'Lorem ipsum dôlor sit amet@ipsum at you\'re',
                'expected' => true,
            ],
        ];
    }
}
