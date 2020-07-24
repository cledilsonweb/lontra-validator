<?php

use Lontra\Validator\Password;
use PHPUnit\Framework\TestCase;

/**
 * Class PasswordTest
 */
class PasswordTest extends TestCase
{

    /**
     * Basic use of Password
     *
     * @return void
     */
    public function testPasswordOptions()
    {
        $options = [
            'uppercase' => false,
            'lowercase' => false,
            'number' => false,
            'specialChars' => false,
        ];
        $validator = new Password($options);

        $optionsValidator = [
            'uppercase' => $validator->getUppercase(),
            'lowercase' => $validator->getLowercase(),
            'number' => $validator->getNumber(),
            'specialChars' => $validator->getSpecialChars(),
        ];

        $this->assertSame($options, $optionsValidator);
    }

    /**
     * Basic use of Password
     * 
     * @dataProvider getData
     * @param        mixed $upper
     * @param        mixed $lower
     * @param        mixed $number
     * @param        mixed $special
     * @param        mixed $pass
     * @param        mixed $expected
     *
     * @return void
     */
    public function testPassword($upper, $lower, $number, $special, $pass, $expected)
    {
        $validator = new Password(
            [
                'uppercase' => $upper,
                'lowercase' => $lower,
                'number' => $number,
                'specialChars' => $special,
            ]
        );

        $this->assertEquals($expected, $validator->isValid($pass));
    }

    /**
     * @return void
     */
    public function getData()
    {
        return [
            'isPassword_AllFalse_Valid_true' => [
                'uppercase' => false,
                'lowercase' => false,
                'number' => false,
                'specialChars' => false,
                'password' => '    ',
                'expected' => true,
            ],
            'isPassword_AllTrue_Valid_true' => [
                'uppercase' => true,
                'lowercase' => true,
                'number' => true,
                'specialChars' => true,
                'password' => 'AbaCadaba123#$',
                'expected' => true,
            ],
            'isPassword_UpperFalse_Valid_true' => [
                'uppercase' => false,
                'lowercase' => true,
                'number' => true,
                'specialChars' => true,
                'password' => 'abacadaba123#$',
                'expected' => true,
            ],
            'isPassword_LowerFalse_Valid_true' => [
                'uppercase' => true,
                'lowercase' => false,
                'number' => true,
                'specialChars' => true,
                'password' => 'ABACADABA123#$',
                'expected' => true,
            ],
            'isPassword_NumberFalse_Valid_true' => [
                'uppercase' => true,
                'lowercase' => true,
                'number' => false,
                'specialChars' => true,
                'password' => 'AbaCadaba#$',
                'expected' => true,
            ],
            'isPassword_SpecialFalse_Valid_true' => [
                'uppercase' => true,
                'lowercase' => true,
                'number' => true,
                'specialChars' => false,
                'password' => 'AbaCadaba123',
                'expected' => true,
            ],
        ];
    }
}
